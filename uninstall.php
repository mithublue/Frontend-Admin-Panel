<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package User_Admin_Dashboard
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Delete plugin options.
 */
function uad_delete_plugin_options() {
    delete_option( 'uad_dashboard_page_id' );
    delete_option( 'uad_version' );
    delete_option( 'uad_installed_date' );
    delete_option( 'uad_min_user_role' );
}

/**
 * Optionally delete the dashboard page.
 * Uncomment if you want to remove the page on uninstall.
 */
function uad_delete_dashboard_page() {
    $page_id = get_option( 'uad_dashboard_page_id' );
    
    if ( $page_id ) {
        // Force delete the page (bypass trash)
        wp_delete_post( $page_id, true );
    }
}

// Clean up options
uad_delete_plugin_options();

// Uncomment the line below to also delete the dashboard page on uninstall
// uad_delete_dashboard_page();
