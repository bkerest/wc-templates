<?php
/*
Plugin Name: WC Dynamic Templates
Description: Use dynamic HTML templates as shortcodes in WooCommerce products.
Version: 1.2
Author: vker
Text Domain: wc-dynamic-templates
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 5.0
Tested up to: 6.0
WC requires at least: 3.0
WC tested up to: 6.0
Requires PHP: 7.0
github: https://github.com/bkerest/wc-templates
*/

if (!defined('ABSPATH')) exit;

define('WCDT_PATH', plugin_dir_path(__FILE__));

require_once WCDT_PATH . 'includes/post-type-template.php';
require_once WCDT_PATH . 'includes/shortcode-renderer.php';
require_once WCDT_PATH . 'includes/template-visibility.php';
require_once WCDT_PATH . 'includes/meta-box-visibility.php';

require_once WCDT_PATH . 'plugin-update-checker/plugin-update-checker.php';

$wcdt_update_checker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/bkerest/wc-templates/',
    __FILE__,
    'wc-dynamic-templates'
);
$wcdt_update_checker->getVcsApi()->enableReleaseAssets();
