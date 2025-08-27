<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class DoomOverlayTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_enqueue_doom_overlay_assets() {
        $tempDir = sys_get_temp_dir() . '/theme_' . uniqid();
        mkdir($tempDir . '/assets/doom/overlay', 0777, true);

        file_put_contents($tempDir . '/assets/doom/overlay/doom-overlay.css', '');
        file_put_contents($tempDir . '/assets/doom/overlay/doom-overlay.js', '');
        $cssMtime = filemtime($tempDir . '/assets/doom/overlay/doom-overlay.css');
        $jsMtime = filemtime($tempDir . '/assets/doom/overlay/doom-overlay.js');

        $themeUri = 'http://example.com/theme/page';

        Monkey\Functions\expect('get_stylesheet_directory')->times(4)->andReturn('/path/theme/page');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('assets/doom/overlay/doom-overlay.css')->andReturn($themeUri . '/assets/doom/overlay/doom-overlay.css');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('assets/doom/overlay/doom-overlay.js')->andReturn($themeUri . '/assets/doom/overlay/doom-overlay.js');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('assets/doom/overlay/doom-overlay.css')->andReturn($tempDir . '/assets/doom/overlay/doom-overlay.css');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('assets/doom/overlay/doom-overlay.js')->andReturn($tempDir . '/assets/doom/overlay/doom-overlay.js');

        Monkey\Functions\expect('wp_enqueue_style')->once()->with('doom-overlay', $themeUri . '/assets/doom/overlay/doom-overlay.css', [], $cssMtime);
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('doom-overlay', $themeUri . '/assets/doom/overlay/doom-overlay.js', ['jquery'], $jsMtime, true);
        Monkey\Functions\expect('wp_localize_script')->once()->with('doom-overlay', 'DOOM_OVERLAY_CFG', [
            'engineUrl' => 'https://raz0red.github.io/webprboom/',
        ]);

        nc_enqueue_doom_overlay_assets();
        $this->addToAssertionCount(1);

        unlink($tempDir . '/assets/doom/overlay/doom-overlay.css');
        unlink($tempDir . '/assets/doom/overlay/doom-overlay.js');
        rmdir($tempDir . '/assets/doom/overlay');
        rmdir($tempDir . '/assets/doom');
        rmdir($tempDir . '/assets');
        rmdir($tempDir);
    }

    public function test_render_doom_overlay_outputs_markup() {
        ob_start();
        nc_render_doom_overlay();
        $out = ob_get_clean();
        $this->assertStringContainsString('id="doom-procrastinate"', $out);
        $this->assertStringContainsString('class="doom-iwad"', $out);
        $this->assertStringContainsString('value="doom1"', $out);
        $this->assertStringContainsString('value="freedoom1"', $out);
        $this->assertStringContainsString('value="freedoom2"', $out);
    }

    public function test_theme_file_helpers_resolve_paths() {
        Monkey\Functions\expect('get_stylesheet_directory')->times(4)->andReturn('/path/theme/page');
        Monkey\Functions\expect('get_theme_file_uri')->twice()->with('assets/doom/overlay/doom-overlay.css')->andReturn('http://example.com/theme/page/assets/doom/overlay/doom-overlay.css');
        $uri1 = nc_theme_file_uri('page/assets/doom/overlay/doom-overlay.css');
        $this->assertSame('http://example.com/theme/page/assets/doom/overlay/doom-overlay.css', $uri1);
        $uri2 = nc_theme_file_uri('/page/assets/doom/overlay/doom-overlay.css');
        $this->assertSame('http://example.com/theme/page/assets/doom/overlay/doom-overlay.css', $uri2);

        Monkey\Functions\expect('get_theme_file_path')->twice()->with('assets/doom/overlay/doom-overlay.css')->andReturn('/path/theme/page/assets/doom/overlay/doom-overlay.css');
        $path1 = nc_theme_file_path('page/assets/doom/overlay/doom-overlay.css');
        $this->assertSame('/path/theme/page/assets/doom/overlay/doom-overlay.css', $path1);
        $path2 = nc_theme_file_path('/page/assets/doom/overlay/doom-overlay.css');
        $this->assertSame('/path/theme/page/assets/doom/overlay/doom-overlay.css', $path2);
    }
}
