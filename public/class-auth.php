<?php
/**
 * Authentication handler for the plugin.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Auth {

    /**
     * Check if user is logged in.
     *
     * @return bool
     */
    public static function is_user_logged_in() {
        return is_user_logged_in();
    }

    /**
     * Check if current user has required capability.
     *
     * @param string $capability The capability to check.
     * @return bool
     */
    public static function user_can( $capability = 'manage_options' ) {
        return current_user_can( $capability );
    }

    /**
     * Check if user can access the dashboard.
     *
     * @return bool
     */
    public static function can_access_dashboard() {
        if ( ! self::is_user_logged_in() ) {
            return false;
        }

        // Get minimum required role from settings
        $min_role = get_option( 'uad_min_user_role', 'administrator' );

        // Map roles to capabilities
        $role_capabilities = array(
            'administrator' => 'manage_options',
            'editor'        => 'edit_pages',
            'author'        => 'publish_posts',
            'contributor'   => 'edit_posts',
            'subscriber'    => 'read',
        );

        $required_capability = isset( $role_capabilities[ $min_role ] ) 
            ? $role_capabilities[ $min_role ] 
            : 'manage_options';

        return self::user_can( $required_capability );
    }

    /**
     * Get current user data.
     *
     * @return WP_User|false
     */
    public static function get_current_user() {
        return wp_get_current_user();
    }

    /**
     * Get login URL.
     *
     * @param string $redirect Optional redirect URL after login.
     * @return string
     */
    public static function get_login_url( $redirect = '' ) {
        if ( empty( $redirect ) ) {
            $redirect = self::get_current_url();
        }
        return wp_login_url( $redirect );
    }

    /**
     * Get logout URL.
     *
     * @param string $redirect Optional redirect URL after logout.
     * @return string
     */
    public static function get_logout_url( $redirect = '' ) {
        if ( empty( $redirect ) ) {
            $redirect = home_url();
        }
        return wp_logout_url( $redirect );
    }

    /**
     * Get current URL.
     *
     * @return string
     */
    private static function get_current_url() {
        global $wp;
        return home_url( add_query_arg( array(), $wp->request ) );
    }

    /**
     * Render login form.
     *
     * @return string
     */
    public static function render_login_form() {
        ob_start();
        ?>
        <div class="uad-login-wrapper">
            <div class="uad-login-container">
                <div class="uad-login-header">
                    <h2><?php esc_html_e( 'Please Log In', 'user-admin-dashboard' ); ?></h2>
                    <p><?php esc_html_e( 'You need to be logged in to access the dashboard.', 'user-admin-dashboard' ); ?></p>
                </div>
                <div class="uad-login-content">
                    <?php
                    wp_login_form( array(
                        'redirect'       => self::get_current_url(),
                        'form_id'        => 'uad-loginform',
                        'label_username' => __( 'Username or Email', 'user-admin-dashboard' ),
                        'label_password' => __( 'Password', 'user-admin-dashboard' ),
                        'label_remember' => __( 'Remember Me', 'user-admin-dashboard' ),
                        'label_log_in'   => __( 'Log In', 'user-admin-dashboard' ),
                        'remember'       => true,
                    ) );
                    ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render access denied message.
     *
     * @return string
     */
    public static function render_access_denied() {
        ob_start();
        ?>
        <div class="uad-access-denied">
            <div class="uad-access-denied-container">
                <div class="uad-access-denied-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2><?php esc_html_e( 'Access Denied', 'user-admin-dashboard' ); ?></h2>
                <p><?php esc_html_e( 'You do not have permission to access this dashboard.', 'user-admin-dashboard' ); ?></p>
                <a href="<?php echo esc_url( home_url() ); ?>" class="uad-btn-home">
                    <?php esc_html_e( 'Go to Homepage', 'user-admin-dashboard' ); ?>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
