<?php
// Add meta box in WC Template editor
function wcdt_auto_add_product_category_box() {
    add_meta_box(
        'wcdt_template_categories_box',
        __('Auto-Apply to Product Categories', 'wc-dynamic-templates'),
        'wcdt_auto_render_category_box',
        'wc_template',
        'side'
    );
}
add_action('add_meta_boxes', 'wcdt_auto_add_product_category_box');

function wcdt_auto_render_category_box($post) {
    $selected = (array)get_post_meta($post->ID, '_wcdt_auto_categories', true);
    $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
    echo '<ul>';
    foreach ($terms as $term) {
        $checked = in_array($term->term_id, $selected) ? 'checked' : '';
        echo '<li><label><input type="checkbox" name="wcdt_auto_categories[]" value="' . esc_attr($term->term_id) . '" ' . $checked . '> ' . esc_html($term->name) . '</label></li>';
    }
    echo '</ul>';
}

function wcdt_auto_save_auto_categories($post_id) {
    if (isset($_POST['wcdt_auto_categories'])) {
        update_post_meta($post_id, '_wcdt_auto_categories', array_map('intval', $_POST['wcdt_auto_categories']));
    } else {
        delete_post_meta($post_id, '_wcdt_auto_categories');
    }
}
add_action('save_post_wc_template', 'wcdt_auto_save_auto_categories');

function wcdt_auto_inject_templates($content) {
    if (!is_product() || !in_the_loop() || !is_main_query()) return $content;

    global $post;
    $product_cats = wp_get_post_terms($post->ID, 'product_cat', ['fields' => 'ids']);
    if (empty($product_cats)) return $content;

    $args = array(
        'post_type' => 'wc_template',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_wcdt_auto_categories',
                'compare' => 'EXISTS',
            ),
        ),
    );
    $templates = get_posts($args);

    $output = '';
    foreach ($templates as $tpl) {
        $tpl_cats = (array)get_post_meta($tpl->ID, '_wcdt_auto_categories', true);
        if (array_intersect($tpl_cats, $product_cats)) {
            $output .= do_shortcode('[wc_template id="' . $tpl->ID . '"]');
        }
    }

    return $content . $output;
}
add_filter('the_content', 'wcdt_auto_inject_templates', 20);