<?php
require_once __DIR__ . '/../vendor/autoload.php';
if (!function_exists('__')) {
    function __($text, $domain = null) {
        return $text;
    }
}
if (!function_exists('add_action')) {
    function add_action($hook, $function_to_add, $priority = 10, $accepted_args = 1) {}
}
if (!function_exists('add_filter')) {
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {}
}
if (!function_exists('esc_html')) {
    function esc_html($text) {
        return $text;
    }
}
require_once __DIR__ . '/../functions.php';
