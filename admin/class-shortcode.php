<?php
/**
 * Shortcode handler for the admin dashboard.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Shortcode {

    /**
     * The ID of this plugin.
     *
     * @var string
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @var string
     */
    private $version;

    /**
     * Module loader instance.
     *
     * @var UAD_Module_Loader
     */
    private $module_loader;

    /**
     * Initialize the class.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the shortcode.
     */
    public function register_shortcode() {
        add_shortcode( 'user_admin_dashboard', array( $this, 'render_dashboard' ) );
        
        // Initialize module loader
        $this->module_loader = new UAD_Module_Loader();
    }

    /**
     * Render the dashboard shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function render_dashboard( $atts = array() ) {
        // Parse attributes
        $atts = shortcode_atts( array(
            'title' => __( 'Admin Dashboard', 'user-admin-dashboard' ),
        ), $atts, 'user_admin_dashboard' );

        // Enqueue assets only when shortcode is used
        $this->enqueue_shortcode_assets();

        // Check if user is logged in
        if ( ! UAD_Auth::is_user_logged_in() ) {
            return UAD_Auth::render_login_form();
        }

        // Check if user has access
        if ( ! UAD_Auth::can_access_dashboard() ) {
            return UAD_Auth::render_access_denied();
        }

        // Render the dashboard
        return $this->render_dashboard_content( $atts );
    }

    /**
     * Enqueue shortcode-specific assets.
     */
    private function enqueue_shortcode_assets() {
        // Enqueue TailwindCSS
        wp_enqueue_style(
            $this->plugin_name . '-tailwind',
            UAD_PLUGIN_URL . 'assets/dist/styles.css',
            array(),
            $this->version,
            'all'
        );

        // Enqueue dashboard JavaScript
        wp_enqueue_script(
            $this->plugin_name . '-dashboard',
            UAD_PLUGIN_URL . 'assets/js/dashboard.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        // Enqueue hotel booking module JavaScript
        wp_enqueue_script(
            'uad-hotel-booking-module',
            UAD_PLUGIN_URL . 'modules/hotel-room-booker/hotel-booking.js',
            array( 'jquery', $this->plugin_name . '-dashboard' ),
            time(),
            true
        );


        // Localize script with AJAX URL and nonce
        wp_localize_script(
            $this->plugin_name . '-dashboard',
            'uadData',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'uad_dashboard_nonce' ),
                'user'    => array(
                    'id'           => get_current_user_id(),
                    'display_name' => wp_get_current_user()->display_name,
                    'email'        => wp_get_current_user()->user_email,
                ),
            )
        );
    }

    /**
     * Render the dashboard content.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    private function render_dashboard_content( $atts ) {
        $current_user = UAD_Auth::get_current_user();
        $modules = $this->module_loader->get_accessible_modules();
        
        // Get first module as default
        $first_module = reset( $modules );
        $default_section = $first_module ? $first_module->get_id() : 'dashboard';
        
        ob_start();
        ?>
        <div class="uad-dashboard-wrapper" id="uad-dashboard">
            <!-- Sidebar -->
            <aside class="uad-sidebar">
                <div class="uad-sidebar-header">
                    <div class="uad-logo">
                        <svg class="uad-logo-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
                        </svg>
                        <span class="uad-logo-text"><?php echo esc_html( $atts['title'] ); ?></span>
                    </div>
                    <button class="uad-sidebar-toggle" id="uad-sidebar-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <nav class="uad-nav">
                    <?php
                    $is_first = true;
                    foreach ( $modules as $module ) :
                        $active_class = $is_first ? ' active' : '';
                        $is_first = false;
                        ?>
                        <a href="#<?php echo esc_attr( $module->get_id() ); ?>" 
                           class="uad-nav-item<?php echo esc_attr( $active_class ); ?>" 
                           data-section="<?php echo esc_attr( $module->get_id() ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <?php echo $module->get_icon(); // Icon is already escaped in module ?>
                            </svg>
                            <span><?php echo esc_html( $module->get_name() ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="uad-sidebar-footer">
                    <a href="<?php echo esc_url( UAD_Auth::get_logout_url() ); ?>" class="uad-logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span><?php esc_html_e( 'Logout', 'user-admin-dashboard' ); ?></span>
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="uad-main">
                <!-- Header -->
                <header class="uad-header">
                    <div class="uad-header-left">
                        <h1 class="uad-page-title"><?php echo esc_html( $first_module ? $first_module->get_name() : __( 'Dashboard', 'user-admin-dashboard' ) ); ?></h1>
                        <p class="uad-page-subtitle"><?php esc_html_e( 'Welcome back!', 'user-admin-dashboard' ); ?></p>
                    </div>
                    <div class="uad-header-right">
                        <div class="uad-user-menu">
                            <button class="uad-user-menu-btn" id="uad-user-menu-btn">
                                <div class="uad-user-avatar">
                                    <?php echo get_avatar( $current_user->ID, 40 ); ?>
                                </div>
                                <div class="uad-user-info">
                                    <span class="uad-user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
                                    <span class="uad-user-role"><?php echo esc_html( ucfirst( reset( $current_user->roles ) ) ); ?></span>
                                </div>
                                <svg class="uad-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Content Area -->
                <div class="uad-content" id="uad-content">
                    <?php
                    $is_first = true;
                    foreach ( $modules as $module ) :
                        $active_class = $is_first ? ' active' : '';
                        $is_first = false;
                        ?>
                        <section class="uad-section<?php echo esc_attr( $active_class ); ?>" data-section="<?php echo esc_attr( $module->get_id() ); ?>">
                            <?php $module->render(); ?>
                        </section>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
        <?php
        return ob_get_clean();
    }
}
