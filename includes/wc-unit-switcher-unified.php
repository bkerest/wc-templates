<?php
/*
Plugin Name: WC Unit Switcher – Unified All-in-One
Description: Ενιαίο plugin που διαχειρίζεται μονάδες WooCommerce attributes (με fractional, decimal), dropdown από μενού, και admin panel για unit ρυθμίσεις.
Version: 4.0
Author: ELVIAL Digital Solutions
*/

if (!defined('ABSPATH')) exit;

// === HANDLE COOKIE FROM URL ===
add_action('init', function () {
    if (isset($_GET['unit']) && in_array($_GET['unit'], ['metric', 'imperial'])) {
        setcookie('elvial_units', $_GET['unit'], time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        $_COOKIE['elvial_units'] = $_GET['unit'];
    }
});

// === ADMIN PANEL ===
add_action('admin_menu', function () {
    add_options_page('Attribute Units', 'Attribute Units', 'manage_options', 'wcuam', 'wcuam_settings_page');
});
add_action('admin_init', function () {
    register_setting('wcuam_settings', 'wcuam_attribute_units');
});
function wcuam_settings_page() {
    $attributes = wc_get_attribute_taxonomies();
    $saved = get_option('wcuam_attribute_units', []);
    echo '<div class="wrap"><h1>WC Attribute Units</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('wcuam_settings');
    echo '<table class="form-table"><tbody>';
    foreach ($attributes as $attr) {
        $slug = $attr->attribute_name;
        $label = $attr->attribute_label;
        $unit = $saved[$slug]['unit'] ?? '';
        $type = $saved[$slug]['type'] ?? '';
        echo '<tr>';
        echo '<th scope="row">' . esc_html($label) . ' (' . esc_html($slug) . ')</th>';
        echo '<td>';
        echo 'Unit: <input type="text" name="wcuam_attribute_units[' . esc_attr($slug) . '][unit]" value="' . esc_attr($unit) . '" /> ';
        echo 'Type: <select name="wcuam_attribute_units[' . esc_attr($slug) . '][type]">';
        echo '<option value="">--</option>';
        echo '<option value="fractional"' . selected($type, 'fractional', false) . '>mm → in (fractional)</option>';
        echo '<option value="decimal"' . selected($type, 'decimal', false) . '>Decimal Conversion</option>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    submit_button();
    echo '</form></div>';
}

// === HELPERS ===
function wcuam_get_attribute_unit($slug) {
    $map = get_option('wcuam_attribute_units', []);
    return $map[$slug]['unit'] ?? '';
}
function wcuam_get_attribute_type($slug) {
    $map = get_option('wcuam_attribute_units', []);
    return $map[$slug]['type'] ?? '';
}
function wcuam_convert_value($value, $unit, $type) {
    $val = floatval(str_replace(',', '.', $value));
    if ($type === 'fractional') {
        $inches = $val / 25.4;
        $whole = floor($inches);
        $fraction = $inches - $whole;
        $denominators = [16, 8, 4, 2];
        $closest = "";
        $min_diff = 1;
        foreach ($denominators as $d) {
            $n = round($fraction * $d);
            $diff = abs($fraction - $n / $d);
            if ($diff < $min_diff && $n <= $d) {
                $min_diff = $diff;
                $closest = [$n, $d];
            }
        }
        if (!$closest || $closest[0] == 0) return $whole . '″';
        if ($whole == 0) return $closest[0] . '/' . $closest[1] . '″';
        return $whole . ' ' . $closest[0] . '/' . $closest[1] . '″';
    } elseif ($type === 'decimal') {
        switch ($unit) {
            case 'kg': return round($val * 2.20462, 2);
            case 'W/m²K': return round($val * 0.1761, 2);
            case 'W/mK': return round($val * 0.5778, 2);
            default: return $val;
        }
    }
    return $val;
}

// === SHORTCODES ===
add_shortcode('pa_value', function ($atts) {
    $atts = shortcode_atts(['value' => ''], $atts);
    $slug = $atts['value'];
    if (!$slug) return '';

    $imperial = isset($_COOKIE['elvial_units']) && $_COOKIE['elvial_units'] === 'imperial';
    $terms = get_the_terms(get_the_ID(), 'pa_' . $slug);

    if (is_wp_error($terms) || empty($terms)) return '';

    $values = [];
    foreach ($terms as $term) {
        $value = $term->name;
        $unit = wcuam_get_attribute_unit($slug);
        $type = wcuam_get_attribute_type($slug);
        $converted_value = $value; // Initialize with the original value

        if ($imperial) {
            $converted_value = wcuam_convert_value($value, $unit, $type);
        }

        // Determine the unit to display
        $display_unit = $unit;
        if ($imperial) {
            switch ($unit) {
                case 'mm': $display_unit = ''; break;
                case 'kg': $display_unit = 'lbs'; break;
                case 'W/m²K': $display_unit = 'BTU/(h·ft²·°F)'; break;
                case 'W/mK': $display_unit = 'BTU/(hr·ft·°F)'; break;
            }
        }

        $values[] = $converted_value . ' ' . $display_unit;
    }

    return implode(' || ', $values);
});


// === MENU LABEL REPLACEMENT ===
add_filter('wp_nav_menu_objects', function ($items) {
    $current = isset($_COOKIE['elvial_units']) ? $_COOKIE['elvial_units'] : 'metric';
    $label = ucfirst($current);
    foreach ($items as $item) {
        if (strtolower(trim($item->title)) === 'units') {
            $item->title = $label;
        }
    }
    return $items;
}, 10);

// === MENU URL PLACEHOLDER FIX ===
add_filter('wp_nav_menu_items', function ($items) {
    $base = strtok(home_url(add_query_arg([], $_SERVER['REQUEST_URI'])), '?');
    $metric_link = esc_url(add_query_arg('unit', 'metric', $base));
    $imperial_link = esc_url(add_query_arg('unit', 'imperial', $base));
    $items = str_replace('#unit_metric', $metric_link, $items);
    $items = str_replace('#unit_imperial', $imperial_link, $items);
    return $items;
}, 11);

add_shortcode('pa_unit', function ($atts) {
    $atts = shortcode_atts(['value' => ''], $atts);
    $slug = $atts['value'];
    if (!$slug) return '';

    $unit = wcuam_get_attribute_unit($slug);
    $imperial = isset($_COOKIE['elvial_units']) && $_COOKIE['elvial_units'] === 'imperial';

    if ($imperial) {
        switch ($unit) {
            case 'mm': return '';
            case 'kg': return 'lbs';
            case 'W/m²K': return 'BTU/(h·ft²·°F)';
            case 'W/mK': return 'BTU/(hr·ft·°F)';
            default: return $unit;
        }
    }

    return $unit;
});