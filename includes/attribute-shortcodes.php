<?php
// includes/attribute-shortcodes.php

if (!defined('ABSPATH')) exit;

// Επιστρέφει την τιμή ενός attribute
function wcdt_get_product_attribute_value($slug) {
    global $product;
    if (!is_a($product, 'WC_Product')) {
        $product = wc_get_product(get_the_ID());
    }
    if (!$product || !$slug) return '';
    $terms = wc_get_product_terms($product->get_id(), 'pa_' . $slug, ['fields' => 'names']);
    return implode(', ', $terms);
}

// Shortcode [pa value="sound"]
add_shortcode('pa', function($atts) {
    $a = shortcode_atts(['value' => ''], $atts);
    return wcdt_get_product_attribute_value($a['value']);
});

// Shortcode [pa_if value="sound"]...[/pa_if]
add_shortcode('pa_if', function($atts, $content = '') {
    $a = shortcode_atts(['value' => ''], $atts);
    $val = wcdt_get_product_attribute_value($a['value']);
    if (!$val) return '';
    return do_shortcode($content);
});
