<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

require_once __DIR__ . '/../tools/download-shareware-wad.php';

class DoomOverlayTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_enqueue_procrastinate_assets() {
        $tempDir = sys_get_temp_dir() . '/theme_' . uniqid();
        mkdir($tempDir . '/css', 0777, true);
        mkdir($tempDir . '/js', 0777, true);
        mkdir($tempDir . '/js/lotties', 0777, true);

        file_put_contents($tempDir . '/css/procrastinate.css', '');
        file_put_contents($tempDir . '/js/procrastinate.js', '');
        file_put_contents($tempDir . '/js/lotties/procrastination.json', '{}');
        $cssMtime = filemtime($tempDir . '/css/procrastinate.css');
        $jsMtime = filemtime($tempDir . '/js/procrastinate.js');

        $themeUri = 'http://example.com/theme/page';

        Monkey\Functions\expect('get_stylesheet_directory_uri')->never();
        Monkey\Functions\expect('get_stylesheet_directory')->times(5)->andReturn('/path/theme/page');

        Monkey\Functions\expect('get_theme_file_uri')->once()->with('css/procrastinate.css')->andReturn($themeUri . '/css/procrastinate.css');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('js/procrastinate.js')->andReturn($themeUri . '/js/procrastinate.js');
        Monkey\Functions\expect('get_theme_file_uri')->once()->with('js/lotties/procrastination.json')->andReturn($themeUri . '/js/lotties/procrastination.json');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('css/procrastinate.css')->andReturn($tempDir . '/css/procrastinate.css');
        Monkey\Functions\expect('get_theme_file_path')->once()->with('js/procrastinate.js')->andReturn($tempDir . '/js/procrastinate.js');

        Monkey\Functions\expect('wp_enqueue_style')->once()->with('procrastinate', $themeUri . '/css/procrastinate.css', [], $cssMtime);
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('lottie', 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js', [], null, true);
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('js-dos', 'https://js-dos.com/6.22/current/js-dos.js', [], null, true);
        Monkey\Functions\expect('wp_enqueue_script')->once()->with('procrastinate', $themeUri . '/js/procrastinate.js', ['lottie', 'js-dos'], $jsMtime, true);
        Monkey\Functions\expect('wp_localize_script')->once()->with('procrastinate', 'PROCRASTINATE_CFG', [
            'lottieUrl' => $themeUri . '/js/lotties/procrastination.json',
        ]);

        nc_enqueue_procrastinate_assets();
        $this->addToAssertionCount(1);

        // cleanup
        unlink($tempDir . '/css/procrastinate.css');
        unlink($tempDir . '/js/procrastinate.js');
        unlink($tempDir . '/js/lotties/procrastination.json');
        rmdir($tempDir . '/js/lotties');
        rmdir($tempDir . '/js');
        rmdir($tempDir . '/css');
        rmdir($tempDir);
    }

    public function test_render_procrastinate_markup_outputs_markup() {
        ob_start();
        nc_render_procrastinate_markup();
        $out = ob_get_clean();
        $this->assertStringContainsString('id="procrastinate-btn"', $out);
        $this->assertStringContainsString('id="doom-overlay"', $out);
        $this->assertStringContainsString('id="doom-container"', $out);
    }

    public function test_theme_file_helpers_resolve_paths() {
        Monkey\Functions\expect('get_stylesheet_directory')->times(4)->andReturn('/path/theme/page');
        Monkey\Functions\expect('get_theme_file_uri')->twice()->with('css/procrastinate.css')->andReturn('http://example.com/theme/page/css/procrastinate.css');
        $uri1 = nc_theme_file_uri('page/css/procrastinate.css');
        $this->assertSame('http://example.com/theme/page/css/procrastinate.css', $uri1);
        $uri2 = nc_theme_file_uri('/page/css/procrastinate.css');
        $this->assertSame('http://example.com/theme/page/css/procrastinate.css', $uri2);

        Monkey\Functions\expect('get_theme_file_path')->twice()->with('css/procrastinate.css')->andReturn('/path/theme/page/css/procrastinate.css');
        $path1 = nc_theme_file_path('page/css/procrastinate.css');
        $this->assertSame('/path/theme/page/css/procrastinate.css', $path1);
        $path2 = nc_theme_file_path('/page/css/procrastinate.css');
        $this->assertSame('/path/theme/page/css/procrastinate.css', $path2);
    }

    public function test_wad_urls_use_raw_github() {
        $this->assertSame('raw.githubusercontent.com', parse_url(NC_FREEDOOM_URL, PHP_URL_HOST));
        $this->assertSame('raw.githubusercontent.com', parse_url(NC_SHAREWARE_URL, PHP_URL_HOST));
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

    public function test_download_shareware_wad_copies_direct_file() {
        $tempDir = sys_get_temp_dir() . '/wadtest_' . uniqid();
        mkdir($tempDir);

        $wad = $tempDir . '/doom1.wad';
        file_put_contents($wad, 'wad');

        $destDir = $tempDir . '/dest';
        nc_download_shareware_wad($destDir, 'file://' . $wad);
        $this->assertFileExists($destDir . '/doom1.wad');
        $this->assertSame('wad', file_get_contents($destDir . '/doom1.wad'));

        // cleanup
        unlink($wad);
        unlink($destDir . '/doom1.wad');
        rmdir($destDir);
        rmdir($tempDir);
    }
}
