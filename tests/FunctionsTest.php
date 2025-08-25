<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class FunctionsTest extends TestCase {
    protected function setUp(): void {
        Monkey\setUp();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
        \Mockery::close();
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
            ->justReturn(realpath(__DIR__ . '/..'));
        $result = nc_get_last_updated();
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $result);
    }

    public function test_include_pages_in_archives_sets_post_type() {
        Brain\Monkey\Functions\when('is_admin')->justReturn(false);
        $query = \Mockery::mock('WP_Query');
        $query->shouldReceive('is_main_query')->andReturn(true);
        $query->shouldReceive('is_date')->andReturn(true);
        $query->shouldReceive('is_category')->andReturn(false);
        $query->shouldReceive('is_tag')->andReturn(false);
        $query->shouldReceive('set')->with('post_type', 'page')->once();
        nc_include_pages_in_archives($query);
        $this->addToAssertionCount(1);
    }
}
