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
}
add_action('init', 'wcdt_register_template_post_type');
