<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

require_once __DIR__ . '/../page/functions.php';
require_once __DIR__ . '/../tools/download-shareware-wad.php';

class DoomOverlayTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_enqueue_doom_overlay_assets_uses_theme_file_api() {
        $tempDir = sys_get_temp_dir() . '/theme_' . uniqid();
        mkdir($tempDir . '/assets/doom/overlay', 0777, true);
        mkdir($tempDir . '/assets/doom/engine', 0777, true);
        mkdir($tempDir . '/assets/doom/iwads', 0777, true);

        $css = $tempDir . '/assets/doom/overlay/doom-overlay.css';
        $js  = $tempDir . '/assets/doom/overlay/doom-overlay.js';
        $engine = $tempDir . '/assets/doom/engine/index.html';
        $freedoom = $tempDir . '/assets/doom/iwads/freedoom1.wad';
        file_put_contents($css, '');
        file_put_contents($js, '');
        file_put_contents($engine, '');
        file_put_contents($freedoom, '');

        $cssMTime = filemtime($css);
        $jsMTime  = filemtime($js);

        Monkey\Functions\when('get_theme_file_uri')->alias(function($rel) {
            return 'http://example.com/wp-content/themes/blogtheme/' . $rel;
        });
        Monkey\Functions\when('get_theme_file_path')->alias(function($rel) use ($tempDir) {
            return $tempDir . '/' . $rel;
        });

        Monkey\Functions\expect('get_stylesheet_directory_uri')->never();
        Monkey\Functions\expect('get_stylesheet_directory')->never();

        Monkey\Functions\expect('wp_enqueue_style')->once()->with(
            'doom-overlay-css',
            'http://example.com/wp-content/themes/blogtheme/assets/doom/overlay/doom-overlay.css',
            [],
            $cssMTime
        );
        Monkey\Functions\expect('wp_enqueue_script')->once()->with(
            'doom-overlay-js',
            'http://example.com/wp-content/themes/blogtheme/assets/doom/overlay/doom-overlay.js',
            ['jquery'],
            $jsMTime,
            true
        );
        Monkey\Functions\expect('wp_localize_script')->once()->with('doom-overlay-js', 'DOOM_OVERLAY_CFG', [
            'engineUrl'   => 'http://example.com/wp-content/themes/blogtheme/assets/doom/engine/index.html',
            'freedoomUrl' => 'http://example.com/wp-content/themes/blogtheme/assets/doom/iwads/freedoom1.wad',
            'sharewareUrl'=> '',
        ]);

        nc_enqueue_doom_overlay_assets();
        $this->addToAssertionCount(1);

        // cleanup
        unlink($css);
        unlink($js);
        unlink($engine);
        unlink($freedoom);
        rmdir($tempDir . '/assets/doom/overlay');
        rmdir($tempDir . '/assets/doom/engine');
        rmdir($tempDir . '/assets/doom/iwads');
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
