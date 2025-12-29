<?php
/**
 * The core plugin class.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Core {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @var UAD_Loader
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var string
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @var string
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     */
    public function __construct() {
        $this->plugin_name = 'user-admin-dashboard';
        $this->version = UAD_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Load the loader
        require_once UAD_PLUGIN_DIR . 'includes/class-loader.php';

        // Load module system
        require_once UAD_PLUGIN_DIR . 'includes/class-module-loader.php';

        // Load admin classes
        require_once UAD_PLUGIN_DIR . 'admin/class-admin.php';
        require_once UAD_PLUGIN_DIR . 'admin/class-shortcode.php';

        // Load public classes
        require_once UAD_PLUGIN_DIR . 'public/class-public.php';
        require_once UAD_PLUGIN_DIR . 'public/class-auth.php';

        $this->loader = new UAD_Loader();
    }

    /**
     * Register all hooks related to the admin area functionality.
     */
    private function define_admin_hooks() {
        $plugin_admin = new UAD_Admin( $this->get_plugin_name(), $this->get_version() );
        $plugin_shortcode = new UAD_Shortcode( $this->get_plugin_name(), $this->get_version() );

        // Enqueue admin styles and scripts
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // Register shortcode
        $this->loader->add_action( 'init', $plugin_shortcode, 'register_shortcode' );
    }

    /**
     * Register all hooks related to the public-facing functionality.
     */
    private function define_public_hooks() {
        $plugin_public = new UAD_Public( $this->get_plugin_name(), $this->get_version() );

        // Enqueue public styles and scripts
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    /**
     * Run the loader to execute all hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Get the module loader instance.
     *
     * @return UAD_Module_Loader
     */
    public function get_module_loader() {
        return $this->module_loader;
    }

    /**
     * The name of the plugin.
     *
     * @return string
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks.
     *
     * @return UAD_Loader
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }
}
