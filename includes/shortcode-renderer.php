<?php
// includes/shortcode-renderer.php

if (!defined('ABSPATH')) exit;

function wcdt_render_template_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'wc_template');

    $template_id = intval($atts['id']);
    if (!$template_id || get_post_type($template_id) !== 'wc_template') {
        return '';
    }

    // Έλεγχος ρόλων
    if (!wcdt_user_can_see_template($template_id)) return '';

    // Περιεχόμενο template
    $post = get_post($template_id);
    if (!$post || $post->post_status !== 'publish') return '';
    $html = do_shortcode($post->post_content);

    // CSS
    $css_raw = get_post_meta($template_id, '_wcdt_custom_css', true);
    $css_scoped = '';

    if (!empty($css_raw)) {
        $css_trimmed = trim($css_raw);
        $css_scoped = preg_replace(
            '/(^|\\})\\s*([^\\{\\}]+?)\\s*\\{/',
            '$1 .template-' . $template_id . ' $2 {',
            $css_trimmed
        );
        $css_scoped = "<style>\n" . $css_scoped . "\n</style>\n";
    }

    return '<div class="wcdt-template-wrapper template-' . $template_id . '">' . $css_scoped . $html . '</div>';
}
add_shortcode('wc_template', 'wcdt_render_template_shortcode');
