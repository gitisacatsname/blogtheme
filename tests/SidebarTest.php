<?php
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class SidebarTest extends TestCase {
    protected function setUp(): void { Monkey\setUp(); }
    protected function tearDown(): void { Monkey\tearDown(); }

    public function test_sidebar_outputs_taxonomy_lists() {
        Brain\Monkey\Functions\when('wp_list_pages')->justReturn('');
        Brain\Monkey\Functions\when('get_terms')->alias(function($args){ return ['dummy']; });
        Brain\Monkey\Functions\when('is_wp_error')->justReturn(false);
        $categoryArgs = null;
        Brain\Monkey\Functions\when('wp_list_categories')->alias(function($args) use (&$categoryArgs){ $categoryArgs = $args; return '<li>cat</li>'; });
        $archiveArgs = null;
        Brain\Monkey\Functions\when('wp_get_archives')->alias(function($args) use (&$archiveArgs){ $archiveArgs = $args; return '<li>2024</li>'; });
        $tagArgs = null;
        Brain\Monkey\Functions\when('wp_tag_cloud')->alias(function($args) use (&$tagArgs){ $tagArgs = $args; return '<li>tag</li>'; });

        ob_start();
        include __DIR__ . '/../page/sidebar.php';
        $output = ob_get_clean();
        $this->assertStringNotContainsString('Recent Posts', $output);
        $this->assertStringContainsString('category-list', $output);
        $this->assertStringContainsString('archive-list', $output);
        $this->assertStringContainsString('tag-list', $output);
        $this->assertSame('category', $categoryArgs['taxonomy']);
        $this->assertSame(0, $categoryArgs['hide_empty']);
        $this->assertSame('page', $archiveArgs['post_type']);
        $this->assertSame(0, $archiveArgs['echo']);
        $this->assertSame('post_tag', $tagArgs['taxonomy']);
        $this->assertSame('list', $tagArgs['format']);
        $this->assertSame(0, $tagArgs['hide_empty']);
    }
}
