<?php
// includes/shortcode-tester.php

if (!defined('ABSPATH')) exit;

// Add submenu page
function wcdt_add_shortcode_tester_page() {
    add_submenu_page(
        'edit.php?post_type=wc_template',
        __('Shortcode Tester', 'wc-dynamic-templates'),
        __('Shortcode Tester', 'wc-dynamic-templates'),
        'manage_options',
        'wcdt-shortcode-tester',
        'wcdt_render_shortcode_tester_page'
    );
}
add_action('admin_menu', 'wcdt_add_shortcode_tester_page');

// Render page
function wcdt_render_shortcode_tester_page() {
    echo '<div class="wrap">';
    echo '<h1>' . __('Shortcode Tester', 'wc-dynamic-templates') . '</h1>';
    echo '<form method="post">';
    echo '<textarea name="wcdt_shortcode_input" rows="8" style="width:100%;font-family:monospace;">' . esc_textarea($_POST['wcdt_shortcode_input'] ?? '') . '</textarea>';
    echo '<p><input type="submit" class="button button-primary" value="Run"></p>';
    echo '</form>';

    if (!empty($_POST['wcdt_shortcode_input'])) {
        echo '<h2>Output</h2><div style="padding:20px;border:1px solid #ccc;background:#fff">';
        echo do_shortcode(wp_kses_post(stripslashes($_POST['wcdt_shortcode_input'])));
        echo '</div>';
    }

    echo '</div>';
}
