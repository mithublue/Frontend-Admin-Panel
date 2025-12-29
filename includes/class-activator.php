<?php
/**
 * Fired during plugin activation.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Activator {

    /**
     * Plugin activation handler.
     *
     * Creates the admin dashboard page with shortcode if it doesn't exist.
     *
     * @since 1.0.0
     */
    public static function activate() {
        self::create_dashboard_page();
        
        // Set default options
        self::set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create the admin dashboard page.
     *
     * @since 1.0.0
     */
    private static function create_dashboard_page() {
        // Check if page already exists
        $page_id = get_option( 'uad_dashboard_page_id' );
        
        if ( $page_id && get_post( $page_id ) ) {
            // Page exists, no need to create
            return;
        }

        // Create the page
        $page_data = array(
            'post_title'    => __( 'Admin Dashboard', 'user-admin-dashboard' ),
            'post_content'  => '[user_admin_dashboard]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
            'comment_status' => 'closed',
            'ping_status'   => 'closed',
        );

        $page_id = wp_insert_post( $page_data );

        if ( $page_id && ! is_wp_error( $page_id ) ) {
            // Store the page ID for future reference
            update_option( 'uad_dashboard_page_id', $page_id );
            
            // Add custom meta to identify this page
            update_post_meta( $page_id, '_uad_dashboard_page', '1' );
        }
    }

    /**
     * Set default plugin options.
     *
     * @since 1.0.0
     */
    private static function set_default_options() {
        $defaults = array(
            'uad_version' => UAD_VERSION,
            'uad_installed_date' => current_time( 'mysql' ),
            'uad_min_user_role' => 'administrator', // Default: only admins can access
        );

        foreach ( $defaults as $key => $value ) {
            if ( ! get_option( $key ) ) {
                add_option( $key, $value );
            }
        }
    }
}
