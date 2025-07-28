<?php
// File: includes/save-template-css.php

if (!defined('ABSPATH')) exit;

// Κατά την αποθήκευση Template
add_action('save_post_wc_template', 'wcdt_save_template_css_file', 10, 3);

function wcdt_save_template_css_file($post_id, $post, $update) {
    // Μην συνεχίσεις αν είναι autosave ή χωρίς δικαιώματα
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Πάρε το custom CSS
    $custom_css = get_post_meta($post_id, '_wcdt_custom_css', true);
    if (!$custom_css) return;

    // Καθάρισε το CSS
    $clean_css = trim($custom_css); // δεν χρειάζεται wp_strip_all_tags — είναι CSS!

    // Φάκελος αποθήκευσης
    $css_dir = WCDT_PATH . 'assets/css/';
    $css_file = $css_dir . "template-$post_id.css";

    // Δημιούργησε φάκελο αν δεν υπάρχει
    if (!file_exists($css_dir)) {
        wp_mkdir_p($css_dir);
    }

    // Αποθήκευση CSS σε αρχείο
    file_put_contents($css_file, $clean_css);
}

// Διαγραφή του CSS αρχείου όταν διαγράφεται Template
add_action('before_delete_post', function ($post_id) {
    if (get_post_type($post_id) !== 'wc_template') return;

    $css_file = WCDT_PATH . 'assets/css/template-' . $post_id . '.css';
    if (file_exists($css_file)) {
        unlink($css_file);
    }
});
