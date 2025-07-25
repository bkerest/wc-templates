<?php
// includes/template-taxonomy.php

if (!defined('ABSPATH')) exit;

function wcdt_register_template_taxonomies() {
    $labels = array(
        'name'              => _x('Template Categories', 'taxonomy general name', 'wc-dynamic-templates'),
        'singular_name'     => _x('Template Category', 'taxonomy singular name', 'wc-dynamic-templates'),
        'search_items'      => __('Search Categories', 'wc-dynamic-templates'),
        'all_items'         => __('All Categories', 'wc-dynamic-templates'),
        'parent_item'       => __('Parent Category', 'wc-dynamic-templates'),
        'parent_item_colon' => __('Parent Category:', 'wc-dynamic-templates'),
        'edit_item'         => __('Edit Category', 'wc-dynamic-templates'),
        'update_item'       => __('Update Category', 'wc-dynamic-templates'),
        'add_new_item'      => __('Add New Category', 'wc-dynamic-templates'),
        'new_item_name'     => __('New Category Name', 'wc-dynamic-templates'),
        'menu_name'         => __('Categories', 'wc-dynamic-templates'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'template-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('template_category', ['wc_template'], $args);
}
add_action('init', 'wcdt_register_template_taxonomies');
