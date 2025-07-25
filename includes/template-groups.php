<?php
// includes/template-groups.php

if (!defined('ABSPATH')) exit;

// Register taxonomy for template groups
function wcdt_register_template_group_taxonomy() {
    $labels = array(
        'name'              => _x('Template Groups', 'taxonomy general name', 'wc-dynamic-templates'),
        'singular_name'     => _x('Template Group', 'taxonomy singular name', 'wc-dynamic-templates'),
        'search_items'      => __('Search Groups', 'wc-dynamic-templates'),
        'all_items'         => __('All Groups', 'wc-dynamic-templates'),
        'edit_item'         => __('Edit Group', 'wc-dynamic-templates'),
        'update_item'       => __('Update Group', 'wc-dynamic-templates'),
        'add_new_item'      => __('Add New Group', 'wc-dynamic-templates'),
        'new_item_name'     => __('New Group Name', 'wc-dynamic-templates'),
        'menu_name'         => __('Template Groups', 'wc-dynamic-templates'),
    );

    register_taxonomy('wc_template_group', 'wc_template', array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'wc-template-group'),
    ));
}
add_action('init', 'wcdt_register_template_group_taxonomy');

// Shortcode to render all templates in a group
function wcdt_render_template_group_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => '',
    ], $atts, 'wc_template_group');

    $term_id = intval($atts['id']);
    if (!$term_id) return '';

    $term = get_term($term_id, 'wc_template_group');
    if (!$term || is_wp_error($term)) return '';

    $templates = get_posts([
        'post_type' => 'wc_template',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => 'wc_template_group',
                'field' => 'term_id',
                'terms' => $term_id,
            ]
        ]
    ]);

    $output = '';
    foreach ($templates as $template) {
        if (!wcdt_user_can_see_template($template->ID)) continue;
        $output .= '<div class="wc-template-group-item">' . do_shortcode($template->post_content) . '</div>';
    }

    return '<div class="wc-template-group">' . $output . '</div>';
}
add_shortcode('wc_template_group', 'wcdt_render_template_group_shortcode');
