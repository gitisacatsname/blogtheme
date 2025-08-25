<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

require_once __DIR__ . '/../page/functions.php';
require_once __DIR__ . '/../tools/download-shareware-wad.php';

class DoomOverlayTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_enqueue_doom_overlay_assets() {
        $tempDir = sys_get_temp_dir() . '/theme_' . uniqid();
        mkdir($tempDir . '/assets/doom/iwads', 0777, true);

        Monkey\Functions\expect('get_stylesheet_directory_uri')->andReturn('http://example.com/theme');
        Monkey\Functions\expect('get_stylesheet_directory')->andReturn($tempDir);
        Monkey\Functions\expect('wp_enqueue_style')->once()->with('doom-overlay', 'http://example.com/theme/assets/doom/overlay/doom-overlay.css', [], '1.0');
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('doom-overlay', 'http://example.com/theme/assets/doom/overlay/doom-overlay.js', [], '1.0', true);
        Monkey\Functions\expect('wp_localize_script')->once()->with('doom-overlay', 'DOOM_OVERLAY_CFG', [
            'engineUrl' => 'http://example.com/theme/assets/doom/engine/index.html',
            'freedoomUrl' => 'http://example.com/theme/assets/doom/iwads/freedoom1.wad',
            'sharewareUrl' => '',
        ]);
        nc_enqueue_doom_overlay_assets();
        $this->addToAssertionCount(1);

        // cleanup
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
