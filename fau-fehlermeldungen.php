<?php

/*
Plugin Name:     FAU Fehlermeldungen
Plugin URI:      https://github.com/RRZE-Webteam/fau-fehlermeldungen
Description:     Ausgabe von zusätzlichen wissenschaftlich basierten Nebeninformationen zu den Standard Fehlermeldungen 401, 403, 404 und 500 in WordPress-Themes.
Version:         1.0.7
Author:          RRZE Webteam
Author URI:      https://blogs.fau.de/webworking/
License:         GNU General Public License v2
License URI:     http://www.gnu.org/licenses/gpl-2.0.html
Domain Path:     /languages
Text Domain:     fau-fehlermeldungen
*/

namespace FAU\Fehlermeldungen;

defined('ABSPATH') || exit;

use FAU\Fehlermeldungen\Main;

const RRZE_PHP_VERSION = '7.4';
const RRZE_WP_VERSION = '5.8';
const RRZE_PLUGIN_FILE = __FILE__;


spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');

add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * Load Text Domain for Translations
 * @return void
 */
function loadTextdomain() {
    load_plugin_textdomain('fau-fehlermeldungen', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Check for System requirements
 * @return string error message
 */
function systemRequirements() {
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
            $error = sprintf(__('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'fau-fehlermeldungen'), PHP_VERSION, RRZE_PHP_VERSION);
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'fau-fehlermeldungen'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }
    return $error;
}

/**
 * Handler for Activation
 * @return void
 */
function activation() {
    loadTextdomain();

    if ($error = systemRequirements()) {
        deactivate_plugins(plugin_basename(__FILE__), false, true);
        wp_die($error);
    }

    flush_rewrite_rules();
}

/**
 * Handler for Deactivation
 * @return void
 */
function deactivation() {
    flush_rewrite_rules();
}

/**
 * Once loaded, run
 */
function loaded() {
    loadTextdomain();

    if ($error = systemRequirements()) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_name = $plugin_data['Name'];
        $tag = is_network_admin() ? 'network_admin_notices' : 'admin_notices';
        add_action($tag, function () use ($plugin_name, $error) {
            printf('<div class="notice notice-error"><p>%1$s: %2$s</p></div>', esc_html($plugin_name), esc_html($error));
        });
    } else {
	// Hauptklasse (Main) wird instanziiert.
	$main = new Main(__FILE__);
	$main->onLoaded();
    }
}
