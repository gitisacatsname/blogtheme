<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

require_once __DIR__ . '/../tools/download-shareware-wad.php';

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

        Monkey\Functions\expect('get_stylesheet_directory_uri')->never();
        Monkey\Functions\expect('get_stylesheet_directory')->times(5)->andReturn('/path/theme/page');

        Monkey\Functions\expect('get_theme_file_uri')->once()->with('assets/doom/overlay/doom-overlay.css')->andReturn($themeUri . '/assets/doom/overlay/doom-overlay.css');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('assets/doom/overlay/doom-overlay.js')->andReturn($themeUri . '/assets/doom/overlay/doom-overlay.js');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('assets/doom/engine/index.html')->andReturn($themeUri . '/assets/doom/engine/index.html');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('assets/doom/overlay/doom-overlay.css')->andReturn($tempDir . '/assets/doom/overlay/doom-overlay.css');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('assets/doom/overlay/doom-overlay.js')->andReturn($tempDir . '/assets/doom/overlay/doom-overlay.js');

        Monkey\Functions\expect('wp_enqueue_style')->once()->with('doom-overlay', $themeUri . '/assets/doom/overlay/doom-overlay.css', [], $cssMtime);
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('doom-overlay', $themeUri . '/assets/doom/overlay/doom-overlay.js', ['jquery'], $jsMtime, true);
        Monkey\Functions\expect('wp_localize_script')->once()->with('doom-overlay', 'DOOM_OVERLAY_CFG', [
            'engineUrl' => $themeUri . '/assets/doom/engine/index.html',
            'freedoomUrl' => NC_FREEDOOM_URL,
            'sharewareUrl' => NC_SHAREWARE_URL,
        ]);

        nc_enqueue_doom_overlay_assets();
        $this->addToAssertionCount(1);

        // cleanup
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
        $this->assertStringContainsString('iframe id="doom-frame"', $out);
        $this->assertStringContainsString('Procrestenate', $out);
        $this->assertStringContainsString('class="doom-here"', $out);
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

    public function test_download_shareware_wad_extracts_file() {
        $tempDir = sys_get_temp_dir() . '/wadtest_' . uniqid();
        mkdir($tempDir);

        file_put_contents($tempDir . '/doom1.wad', 'wad');
        shell_exec('tar -czf ' . escapeshellarg($tempDir . '/shareware.tar.gz') . ' -C ' . escapeshellarg($tempDir) . ' doom1.wad');
        unlink($tempDir . '/doom1.wad');
        $tarGz = $tempDir . '/shareware.tar.gz';

        nc_download_shareware_wad($tempDir, 'file://' . $tarGz);
        $this->assertFileExists($tempDir . '/doom1.wad');
        $this->assertSame('wad', file_get_contents($tempDir . '/doom1.wad'));

        // cleanup
        unlink($tarGz);
        unlink($tempDir . '/doom1.wad');
        rmdir($tempDir);
    }
}
