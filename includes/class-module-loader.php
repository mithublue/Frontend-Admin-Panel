<?php
/**
 * Module loader and registry.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Module_Loader {

    /**
     * Registered modules.
     *
     * @var array
     */
    private $modules = array();

    /**
     * Modules directory path.
     *
     * @var string
     */
    private $modules_dir;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->modules_dir = UAD_PLUGIN_DIR . 'modules/';
        $this->load_base_module();
        $this->discover_modules();
    }

    /**
     * Load the base module class.
     */
    private function load_base_module() {
        require_once UAD_PLUGIN_DIR . 'includes/class-module.php';
    }

    /**
     * Discover and load all modules.
     */
    private function discover_modules() {
        if ( ! is_dir( $this->modules_dir ) ) {
            return;
        }

        $module_folders = glob( $this->modules_dir . '*', GLOB_ONLYDIR );

        foreach ( $module_folders as $module_folder ) {
            $this->load_module( $module_folder );
        }

        // Allow external plugins to register modules
        $this->load_external_modules();

        // Sort modules by priority
        uasort( $this->modules, function( $a, $b ) {
            return $a->get_priority() - $b->get_priority();
        });
    }

    /**
     * Load modules registered by external plugins.
     */
    private function load_external_modules() {
        /**
         * Filter to allow external plugins to register custom modules.
         *
         * @param array $modules Array of module instances to register.
         */
        $external_modules = apply_filters( 'uad_register_modules', array() );

        if ( ! is_array( $external_modules ) ) {
            return;
        }

        foreach ( $external_modules as $module ) {
            if ( $module instanceof UAD_Module ) {
                // Initialize the module
                $module->init();

                // Register the module
                $this->modules[ $module->get_id() ] = $module;
            }
        }
    }

    /**
     * Register a module programmatically.
     *
     * This method allows external plugins to register modules at any time.
     *
     * @param UAD_Module $module The module instance to register.
     * @return bool True if registered successfully, false otherwise.
     */
    public function register_module( UAD_Module $module ) {
        if ( ! $module instanceof UAD_Module ) {
            return false;
        }

        // Initialize the module
        $module->init();

        // Register the module
        $this->modules[ $module->get_id() ] = $module;

        // Re-sort modules by priority
        uasort( $this->modules, function( $a, $b ) {
            return $a->get_priority() - $b->get_priority();
        });

        return true;
    }

    /**
     * Load a single module from a folder.
     *
     * @param string $module_folder Path to module folder.
     */
    private function load_module( $module_folder ) {
        $module_name = basename( $module_folder );
        $module_file = $module_folder . '/module.php';

        if ( ! file_exists( $module_file ) ) {
            return;
        }

        require_once $module_file;

        // Expected class name: UAD_Module_{ModuleName}
        $class_name = 'UAD_Module_' . str_replace( '-', '_', ucwords( $module_name, '-' ) );

        if ( ! class_exists( $class_name ) ) {
            return;
        }

        $module = new $class_name();

        if ( ! $module instanceof UAD_Module ) {
            return;
        }

        // Initialize the module
        $module->init();

        // Register the module
        $this->modules[ $module->get_id() ] = $module;
    }

    /**
     * Get all registered modules.
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get accessible modules for current user.
     *
     * @return array
     */
    public function get_accessible_modules() {
        return array_filter( $this->modules, function( $module ) {
            return $module->can_access();
        });
    }

    /**
     * Get a specific module by ID.
     *
     * @param string $module_id Module ID.
     * @return UAD_Module|null
     */
    public function get_module( $module_id ) {
        return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : null;
    }

    /**
     * Render a module by ID.
     *
     * @param string $module_id Module ID.
     */
    public function render_module( $module_id ) {
        $module = $this->get_module( $module_id );

        if ( ! $module || ! $module->can_access() ) {
            echo '<p>' . esc_html__( 'Module not found or access denied.', 'user-admin-dashboard' ) . '</p>';
            return;
        }

        $module->render();
    }
}
