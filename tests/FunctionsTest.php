<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class FunctionsTest extends TestCase {
    protected function setUp(): void {
        Monkey\setUp();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
    }

    public function test_unregister_recent_posts_widget() {
        Brain\Monkey\Functions\expect('unregister_widget')
            ->once()
            ->with('WP_Widget_Recent_Posts');
        nc_unregister_widgets();
        $this->addToAssertionCount(1);
    }

    public function test_enable_page_taxonomies() {
        $called = [];
        Brain\Monkey\Functions\when('register_taxonomy_for_object_type')
            ->alias(function($tax, $obj) use (&$called) {
                $called[] = [$tax, $obj];
            });
        nc_enable_page_taxonomies();
        $this->assertContains(['category', 'page'], $called);
        $this->assertContains(['post_tag', 'page'], $called);
    }

    public function test_get_last_updated_uses_git() {
        Brain\Monkey\Functions\when('get_template_directory')
            ->justReturn(realpath(__DIR__ . '/../page'));
        $result = nc_get_last_updated();
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $result);
    }
}
