<?php
// includes/template-visibility.php

if (!defined('ABSPATH')) exit;

function wcdt_user_can_see_template($template_id) {
    $allowed_roles = get_post_meta($template_id, '_wcdt_allowed_roles', true);
    if (!is_array($allowed_roles) || empty($allowed_roles)) {
        return true; // No restriction set, allow all
    }

    $current_user = wp_get_current_user();
    foreach ($current_user->roles as $role) {
        if (in_array($role, $allowed_roles)) {
            return true;
        }
    }

    return false; // No matching role
}
