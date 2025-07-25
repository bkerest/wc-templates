<?php
// includes/template-inline-css.php

if (!defined('ABSPATH')) exit;

// Add meta box
function wcdt_add_inline_css_meta_box() {
    add_meta_box(
        'wcdt_inline_css_box',
        __('Custom CSS (per template)', 'wc-dynamic-templates'),
        'wcdt_render_inline_css_meta_box',
        'wc_template',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'wcdt_add_inline_css_meta_box');

function wcdt_render_inline_css_meta_box($post) {
    $css = get_post_meta($post->ID, '_wcdt_inline_css', true);
    echo '<textarea name="wcdt_inline_css" style="width:100%;min-height:180px;font-family:monospace;">' . esc_textarea($css) . '</textarea>';
    echo '<p style="margin-top:6px;font-size:11px;color:#555">' . __('No &lt;style&gt; tags needed. Just write valid CSS.', 'wc-dynamic-templates') . '</p>';
}

// Save CSS
function wcdt_save_inline_css_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['wcdt_inline_css'])) {
        $css = wp_kses_post($_POST['wcdt_inline_css']);
        update_post_meta($post_id, '_wcdt_inline_css', $css);
    }
}
add_action('save_post', 'wcdt_save_inline_css_meta_box');

// Output CSS inline if template is used
function wcdt_output_inline_css() {
    if (!is_singular('product')) return;

    global $post;
    if (!isset($post->post_content)) return;

    preg_match_all('/\[wc_template[^\]]*id=["\']?(\d+)["\']?[^\]]*\]/', $post->post_content, $matches);
    $template_ids = array_map('intval', $matches[1]);

    foreach ($template_ids as $template_id) {
        $css = get_post_meta($template_id, '_wcdt_inline_css', true);
        if (!empty($css)) {
            echo "<style id='wcdt-template-css-$template_id'>\n$css\n</style>";
        }
    }
}
add_action('wp_head', 'wcdt_output_inline_css');
