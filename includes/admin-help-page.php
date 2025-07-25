<?php
// includes/admin-help-page.php

if (!defined('ABSPATH')) exit;

// Add submenu under "WC Templates"
function wcdt_add_help_submenu() {
    add_submenu_page(
        'edit.php?post_type=wc_template',
        __('Usage Guide', 'wc-dynamic-templates'),
        __('📘 Usage Guide', 'wc-dynamic-templates'),
        'edit_posts',
        'wcdt-help',
        'wcdt_render_help_page'
    );
}
add_action('admin_menu', 'wcdt_add_help_submenu');

// Render the help page
function wcdt_render_help_page() {
    $readme_path = plugin_dir_path(__FILE__) . '../README.md';
    $readme = file_exists($readme_path) ? file_get_contents($readme_path) : 'README.md not found.';
    $readme_html = wpautop(esc_html($readme));
    echo '<div class="wrap"><h1>📘 WC Dynamic Templates - Usage Guide</h1>';  
    echo '<div style="background:#fff; padding:20px; line-height:1.6; font-family:monospace; font-size:14px; white-space:pre-wrap;">' . $readme_html . '</div></div>';
}
