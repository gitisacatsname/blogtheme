<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class FooterTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_footer_shows_last_updated() {
        Brain\Monkey\Functions\when('wp_nav_menu')->justReturn('');
        Brain\Monkey\Functions\when('get_template_directory')->justReturn(realpath(__DIR__ . '/../page'));
        Brain\Monkey\Functions\when('get_sidebar')->justReturn('');
        Brain\Monkey\Functions\when('wp_footer')->justReturn('');

        ob_start();
        include __DIR__ . '/../page/footer.php';
        $output = ob_get_clean();
        $this->assertMatchesRegularExpression('/Last updated: \d{4}-\d{2}-\d{2}T/', $output);
    }
}
