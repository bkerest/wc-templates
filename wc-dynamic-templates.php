<?php
/*
Plugin Name: WC Dynamic Templates
Description: Use dynamic HTML templates as shortcodes in WooCommerce products.
Version: 2.0.4
Author: vker
Text Domain: wc-dynamic-templates
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 5.0
Tested up to: 10.0.3
WC requires at least: 3.0
WC tested up to: 10.0.3
Requires PHP: 7.0
github: https://github.com/bkerest/wc-templates
*/

if (!defined('ABSPATH')) exit;

// Check if plugin folder name matches the declared slug
$plugin_folder_name = basename(dirname(__FILE__));
if ($plugin_folder_name !== 'wc-templates') {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-warning"><p><strong>WC Dynamic Templates:</strong> To enable auto-updates, please rename the plugin folder to <code>wc-templates</code>.</p></div>';
    });
}

define('WCDT_PATH', plugin_dir_path(__FILE__));
define('WCDT_URL', plugin_dir_url(__FILE__));

require_once WCDT_PATH . 'includes/post-type-template.php';
require_once WCDT_PATH . 'includes/shortcode-renderer.php';
require_once WCDT_PATH . 'includes/template-visibility.php';
require_once WCDT_PATH . 'includes/meta-box-visibility.php';
require_once WCDT_PATH . 'includes/template-style-loader.php';
require_once WCDT_PATH . 'includes/template-inline-css.php';
require_once WCDT_PATH . 'includes/attribute-shortcodes.php';
require_once WCDT_PATH . 'includes/template-groups.php';
require_once WCDT_PATH . 'includes/template-group-style.php';
require_once WCDT_PATH . 'includes/template-preview.php';
require_once WCDT_PATH . 'includes/template-taxonomy.php';
require_once WCDT_PATH . 'includes/shortcode-tester.php';
require_once WCDT_PATH . 'includes/template-shortcode-copy.php';
require_once WCDT_PATH . 'includes/template-auto-assign.php';
require_once WCDT_PATH . 'includes/template-conditional-shortcodes.php';
require_once WCDT_PATH . 'includes/admin-help-page.php';
require_once WCDT_PATH . 'includes/save-template-css.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-box-css.php';

// Plugin update checker (GitHub)
require_once WCDT_PATH . 'plugin-update-checker/Puc/v5p6/PucFactory.php';

use YahnisElsts\PluginUpdateChecker\v5p6\PucFactory;

$wcdt_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/bkerest/wc-templates/',
    __FILE__,
    'wc-templates'
);
$wcdt_update_checker->getVcsApi()->enableReleaseAssets();

add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            __FILE__,
            true
        );
    }
});
