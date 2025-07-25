<?php
// includes/template-preview.php

if (!defined('ABSPATH')) exit;

// Add preview button in admin editor for templates
function wcdt_add_preview_button($post) {
    if ($post->post_type !== 'wc_template') return;

    $preview_url = add_query_arg([
        'wcdt_preview_template' => $post->ID
    ], home_url('/'));

    echo '<div style="margin-top:10px;">
        <a href="' . esc_url($preview_url) . '" target="_blank" class="button button-secondary">
            ' . __('🔍 Preview as in Product', 'wc-dynamic-templates') . '
        </a>
    </div>';
}
add_action('edit_form_after_title', 'wcdt_add_preview_button');

// Show template preview if ?wcdt_preview_template=ID is set
function wcdt_render_template_preview() {
    if (!isset($_GET['wcdt_preview_template'])) return;

    $template_id = intval($_GET['wcdt_preview_template']);
    $post = get_post($template_id);
    if (!$post || $post->post_type !== 'wc_template') return;

    status_header(200);
    nocache_headers();

    echo '<!DOCTYPE html><html><head><meta charset="UTF-8">';
    echo '<title>' . esc_html($post->post_title) . ' – Preview</title>';
    wp_head();
    echo '<style>body{padding:40px;font-family:sans-serif;}</style>';
    echo '</head><body>';
    echo '<h1 style="margin-bottom:30px;">Template Preview: ' . esc_html($post->post_title) . '</h1>';
    echo do_shortcode($post->post_content);
    wp_footer();
    echo '</body></html>';
    exit;
}
add_action('template_redirect', 'wcdt_render_template_preview');
