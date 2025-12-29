<?php
/**
 * Base class for dashboard modules.
 *
 * @package User_Admin_Dashboard
 */

abstract class UAD_Module {

    /**
     * Module ID (slug).
     *
     * @var string
     */
    protected $id;

    /**
     * Module name/title.
     *
     * @var string
     */
    protected $name;

    /**
     * Module icon SVG.
     *
     * @var string
     */
    protected $icon;

    /**
     * Module priority (lower = higher in menu).
     *
     * @var int
     */
    protected $priority = 10;

    /**
     * Required capability to access this module.
     *
     * @var string
     */
    protected $capability = 'read';

    /**
     * Get module ID.
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get module name.
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get module icon.
     *
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * Get module priority.
     *
     * @return int
     */
    public function get_priority() {
        return $this->priority;
    }

    /**
     * Get required capability.
     *
     * @return string
     */
    public function get_capability() {
        return $this->capability;
    }

    /**
     * Check if current user can access this module.
     *
     * @return bool
     */
    public function can_access() {
        return current_user_can( $this->capability );
    }

    /**
     * Initialize the module.
     * Override this method to add hooks, enqueue scripts, etc.
     */
    public function init() {
        // Override in child class
    }

    /**
     * Render the module content.
     * This method must be implemented by child classes.
     *
     * @return void
     */
    abstract public function render();

    /**
     * Get module data for JavaScript.
     *
     * @return array
     */
    public function get_js_data() {
        return array();
    }
}
