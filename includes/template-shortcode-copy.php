<?php
// includes/template-shortcode-copy.php

if (!defined('ABSPATH')) exit;

// Add custom column to list view
function wcdt_template_columns($columns) {
    $columns['wcdt_shortcode'] = __('Shortcode', 'wc-dynamic-templates');
    return $columns;
}
add_filter('manage_wc_template_posts_columns', 'wcdt_template_columns');

// Fill the column with copy button
function wcdt_template_column_content($column, $post_id) {
    if ($column === 'wcdt_shortcode') {
        $shortcode = '[wc_template id="' . $post_id . '"]';
        echo '<input type="text" readonly value="' . esc_attr($shortcode) . '" onclick="this.select();document.execCommand(\'copy\');" style="width:95%;font-family:monospace;background:#f9f9f9;border:1px solid #ccc;padding:2px 5px;cursor:pointer;" title="Click to copy">';
    }
}
add_action('manage_wc_template_posts_custom_column', 'wcdt_template_column_content', 10, 2);

// Make the column sortable (optional)
function wcdt_template_sortable_columns($columns) {
    $columns['wcdt_shortcode'] = 'wcdt_shortcode';
    return $columns;
}
add_filter('manage_edit-wc_template_sortable_columns', 'wcdt_template_sortable_columns');
