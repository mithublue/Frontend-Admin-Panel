<?php
/**
 * Plugin Name: User Admin Dashboard
 * Plugin URI: https://example.com/user-admin-dashboard
 * Description: A modular WordPress plugin providing a beautiful admin dashboard accessible via shortcode with TailwindCSS styling.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: user-admin-dashboard
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Current plugin version.
 */
define( 'UAD_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'UAD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'UAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'UAD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_user_admin_dashboard() {
    require_once UAD_PLUGIN_DIR . 'includes/class-activator.php';
    UAD_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_user_admin_dashboard() {
    require_once UAD_PLUGIN_DIR . 'includes/class-deactivator.php';
    UAD_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_user_admin_dashboard' );
register_deactivation_hook( __FILE__, 'deactivate_user_admin_dashboard' );

/**
 * The core plugin class.
 */
require_once UAD_PLUGIN_DIR . 'includes/class-core.php';

/**
 * Begins execution of the plugin.
 */
function run_user_admin_dashboard() {
    $plugin = new UAD_Core();
    $plugin->run();
}

run_user_admin_dashboard();
