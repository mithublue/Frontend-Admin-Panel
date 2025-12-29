<?php
/**
 * Fired during plugin deactivation.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Deactivator {

    /**
     * Plugin deactivation handler.
     *
     * @since 1.0.0
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Note: We don't delete the page or options here
        // This allows the plugin to be reactivated without losing data
        // Cleanup happens in uninstall.php if the plugin is deleted
    }
}
