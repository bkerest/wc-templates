<?php
// includes/template-conditional-shortcodes.php

if (!defined('ABSPATH')) exit;

// Check if product has attribute value
function wcdt_conditional_pa_if_shortcode($atts, $content = '') {
    $a = shortcode_atts(['value' => ''], $atts);
    $slug = $a['value'];
    if (!$slug) return '';

    global $product;
    if (!is_a($product, 'WC_Product')) {
        $product = wc_get_product(get_the_ID());
    }
    if (!$product) return '';

    $terms = wc_get_product_terms($product->get_id(), 'pa_' . $slug, ['fields' => 'names']);
    if (empty($terms)) return '';

    return do_shortcode($content);
}
add_shortcode('wcdt_if_pa', 'wcdt_conditional_pa_if_shortcode');

// Example:
// [wcdt_if_pa value="sound"]<tr><td>Sound</td><td>[pa value="sound"]db</td></tr>[/wcdt_if_pa]
