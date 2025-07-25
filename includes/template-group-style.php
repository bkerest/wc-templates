<?php
// includes/template-group-style.php

if (!defined('ABSPATH')) exit;

// Output default CSS styles for wc_template_group shortcode
function wcdt_output_template_group_styles() {
    if (!is_singular('product')) return;

    global $post;
    if (!isset($post->post_content)) return;

    if (strpos($post->post_content, '[wc_template_group') === false) return;

    echo '<style id="wcdt-template-group-styles">
        .wc-template-group {
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .wc-template-group-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .wc-template-group-item:nth-child(even) {
            background-color: #fafafa;
        }
        .wc-template-group-item:last-child {
            border-bottom: none;
        }
    </style>';
}
add_action('wp_head', 'wcdt_output_template_group_styles');
