<?php
// includes/post-type-template.php

if (!defined('ABSPATH')) exit;

function wcdt_register_template_post_type() {
    $labels = array(
        'name'               => _x('WC Templates', 'post type general name', 'wc-dynamic-templates'),
        'singular_name'      => _x('WC Template', 'post type singular name', 'wc-dynamic-templates'),
        'menu_name'          => _x('WC Templates', 'admin menu', 'wc-dynamic-templates'),
        'name_admin_bar'     => _x('WC Template', 'add new on admin bar', 'wc-dynamic-templates'),
        'add_new'            => _x('Add New', 'template', 'wc-dynamic-templates'),
        'add_new_item'       => __('Add New Template', 'wc-dynamic-templates'),
        'new_item'           => __('New Template', 'wc-dynamic-templates'),
        'edit_item'          => __('Edit Template', 'wc-dynamic-templates'),
        'view_item'          => __('View Template', 'wc-dynamic-templates'),
        'all_items'          => __('All Templates', 'wc-dynamic-templates'),
        'search_items'       => __('Search Templates', 'wc-dynamic-templates'),
        'not_found'          => __('No templates found.', 'wc-dynamic-templates'),
        'not_found_in_trash' => __('No templates found in Trash.', 'wc-dynamic-templates')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-media-code',
        'supports' => array('title', 'editor', 'revisions')
    );

    register_post_type('wc_template', $args);
$args['supports'][] = 'page-attributes';

// Add Duplicate Link
add_filter('post_row_actions', function($actions, $post){
    if ($post->post_type != 'wc_template') return $actions;
    $url = wp_nonce_url(admin_url('admin.php?action=duplicate_wc_template&post=' . $post->ID), 'duplicate_wc_template_' . $post->ID);
    $actions['duplicate'] = '<a href="' . esc_url($url) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
    return $actions;
}, 10, 2);

add_action('admin_action_duplicate_wc_template', function() {

// Enable sorting by Menu Order in admin list
add_filter('manage_edit-wc_template_sortable_columns', function($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
});

add_filter('request', function($vars) {
    if (isset($vars['post_type']) && $vars['post_type'] == 'wc_template') {
        if (isset($vars['orderby']) && $vars['orderby'] == 'menu_order') {
            $vars = array_merge($vars, array(
                'orderby' => 'menu_order'
            ));
        }
    }
    return $vars;
});

    if (empty($_GET['post']) || !current_user_can('edit_posts')) wp_die('No post to duplicate has been supplied!');

    $post_id = absint($_GET['post']);
    check_admin_referer('duplicate_wc_template_' . $post_id);

    $post = get_post($post_id);
    $new_post = array(
        'post_title'     => 'Copy of ' . $post->post_title,
        'post_content'   => $post->post_content,
        'post_status'    => 'draft',
        'post_type'      => 'wc_template',
        'post_author'    => get_current_user_id(),
        'menu_order'     => $post->menu_order,
    );
    $new_post_id = wp_insert_post($new_post);

    // Copy metadata
    $meta = get_post_meta($post_id);
    foreach ($meta as $key => $values) {
        foreach ($values as $value) {
            add_post_meta($new_post_id, $key, maybe_unserialize($value));
        }
    }

    wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
    exit;
});

}
add_action('init', 'wcdt_register_template_post_type');
