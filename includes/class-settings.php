<?php
/**
 * Settings page for User Admin Dashboard.
 *
 * Provides a modular settings page with tabs that can be extended by external plugins.
 *
 * @package User_Admin_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class UAD_Settings
 */
class UAD_Settings {

    /**
     * Option group name.
     *
     * @var string
     */
    private $option_group = 'uad_settings';

    /**
     * Option name for main settings.
     *
     * @var string
     */
    private $option_name = 'uad_options';

    /**
     * Current active tab.
     *
     * @var string
     */
    private $active_tab;

    /**
     * Registered tabs.
     *
     * @var array
     */
    private $tabs = array();

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }

    /**
     * Add settings page to admin menu.
     */
    public function add_settings_page() {
        add_options_page(
            __( 'User Admin Dashboard', 'user-admin-dashboard' ),
            __( 'User Admin Dashboard', 'user-admin-dashboard' ),
            'manage_options',
            'uad-settings',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Get all registered tabs.
     *
     * @return array
     */
    public function get_tabs() {
        // Default tabs
        $tabs = array(
            'general' => array(
                'title'    => __( 'General', 'user-admin-dashboard' ),
                'callback' => array( $this, 'render_general_tab' ),
                'priority' => 10,
            ),
        );

        /**
         * Filter to register additional settings tabs.
         *
         * @param array $tabs Array of tabs with 'id' => array('title', 'callback', 'priority').
         */
        $tabs = apply_filters( 'uad_settings_tabs', $tabs );

        // Sort by priority
        uasort( $tabs, function( $a, $b ) {
            return ( $a['priority'] ?? 50 ) - ( $b['priority'] ?? 50 );
        } );

        return $tabs;
    }

    /**
     * Register all settings.
     */
    public function register_settings() {
        // Register main option
        register_setting(
            $this->option_group,
            $this->option_name,
            array(
                'sanitize_callback' => array( $this, 'sanitize_options' ),
            )
        );

        // General section
        add_settings_section(
            'uad_general_section',
            __( 'General Settings', 'user-admin-dashboard' ),
            array( $this, 'render_section_description' ),
            'uad-settings-general'
        );

        add_settings_field(
            'dashboard_title',
            __( 'Dashboard Title', 'user-admin-dashboard' ),
            array( $this, 'render_text_field' ),
            'uad-settings-general',
            'uad_general_section',
            array(
                'id'          => 'dashboard_title',
                'description' => __( 'The title shown at the top of the dashboard.', 'user-admin-dashboard' ),
                'default'     => __( 'Admin Dashboard', 'user-admin-dashboard' ),
            )
        );

        add_settings_field(
            'welcome_message',
            __( 'Welcome Message', 'user-admin-dashboard' ),
            array( $this, 'render_text_field' ),
            'uad-settings-general',
            'uad_general_section',
            array(
                'id'          => 'welcome_message',
                'description' => __( 'The welcome message shown to users.', 'user-admin-dashboard' ),
                'default'     => __( 'Welcome back!', 'user-admin-dashboard' ),
            )
        );

        /**
         * Action to register additional settings fields.
         *
         * @param string $option_group The option group name.
         * @param string $option_name The option name.
         */
        do_action( 'uad_register_settings', $this->option_group, $this->option_name );
    }

    /**
     * Sanitize options.
     *
     * @param array $input Input values.
     * @return array Sanitized values.
     */
    public function sanitize_options( $input ) {
        $sanitized = array();

        if ( isset( $input['dashboard_title'] ) ) {
            $sanitized['dashboard_title'] = sanitize_text_field( $input['dashboard_title'] );
        }

        if ( isset( $input['welcome_message'] ) ) {
            $sanitized['welcome_message'] = sanitize_text_field( $input['welcome_message'] );
        }

        /**
         * Filter to allow external plugins to sanitize their settings.
         *
         * @param array $sanitized Sanitized values.
         * @param array $input Raw input values.
         */
        return apply_filters( 'uad_sanitize_settings', $sanitized, $input );
    }

    /**
     * Enqueue admin styles.
     *
     * @param string $hook Current admin page.
     */
    public function enqueue_styles( $hook ) {
        if ( 'settings_page_uad-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'uad-settings',
            UAD_PLUGIN_URL . 'admin/css/settings.css',
            array(),
            UAD_VERSION
        );
    }

    /**
     * Render the settings page.
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $this->tabs = $this->get_tabs();
        $tab_keys = array_keys( $this->tabs );
        $this->active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->tabs )
            ? sanitize_text_field( $_GET['tab'] )
            : ( ! empty( $tab_keys ) ? $tab_keys[0] : 'general' );

        ?>
        <div class="wrap uad-settings-wrap">
            <h1><?php esc_html_e( 'User Admin Dashboard Settings', 'user-admin-dashboard' ); ?></h1>

            <?php settings_errors( 'uad_settings_messages' ); ?>

            <!-- Tabs Navigation -->
            <nav class="nav-tab-wrapper uad-nav-tabs">
                <?php foreach ( $this->tabs as $tab_id => $tab ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'options-general.php?page=uad-settings&tab=' . $tab_id ) ); ?>"
                       class="nav-tab <?php echo $this->active_tab === $tab_id ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html( $tab['title'] ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Tab Content -->
            <div class="uad-settings-content">
                <?php
                if ( isset( $this->tabs[ $this->active_tab ] ) && is_callable( $this->tabs[ $this->active_tab ]['callback'] ) ) {
                    call_user_func( $this->tabs[ $this->active_tab ]['callback'] );
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render general tab content.
     */
    public function render_general_tab() {
        ?>
        <form method="post" action="options.php">
            <?php
            settings_fields( $this->option_group );
            do_settings_sections( 'uad-settings-general' );
            submit_button( __( 'Save Settings', 'user-admin-dashboard' ) );
            ?>
        </form>
        <?php
    }

    /**
     * Render section description.
     */
    public function render_section_description() {
        echo '<p>' . esc_html__( 'Configure the general settings for the User Admin Dashboard.', 'user-admin-dashboard' ) . '</p>';
    }

    /**
     * Render text field.
     *
     * @param array $args Field arguments.
     */
    public function render_text_field( $args ) {
        $options = get_option( $this->option_name, array() );
        $value = isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ] : ( $args['default'] ?? '' );
        ?>
        <input type="text"
               id="<?php echo esc_attr( $args['id'] ); ?>"
               name="<?php echo esc_attr( $this->option_name . '[' . $args['id'] . ']' ); ?>"
               value="<?php echo esc_attr( $value ); ?>"
               class="regular-text">
        <?php if ( ! empty( $args['description'] ) ) : ?>
            <p class="description"><?php echo esc_html( $args['description'] ); ?></p>
        <?php endif;
    }

    /**
     * Get option value.
     *
     * @param string $key Option key.
     * @param mixed  $default Default value.
     * @return mixed Option value.
     */
    public static function get_option( $key, $default = '' ) {
        $options = get_option( 'uad_options', array() );
        return isset( $options[ $key ] ) ? $options[ $key ] : $default;
    }
}
