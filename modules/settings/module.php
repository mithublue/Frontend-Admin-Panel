<?php
/**
 * Settings module.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Module_Settings extends UAD_Module {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = 'settings';
        $this->name = __( 'Settings', 'user-admin-dashboard' );
        $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        $this->priority = 40;
        $this->capability = 'manage_options';
    }

    /**
     * Render the settings module.
     */
    public function render() {
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php esc_html_e( 'Settings', 'user-admin-dashboard' ); ?></h3>
            </div>
            <div class="uad-card-body">
                <p><?php esc_html_e( 'Settings and configuration options will be displayed here. You can add forms for customizing the dashboard behavior.', 'user-admin-dashboard' ); ?></p>
                <div class="mt-4 p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <?php esc_html_e( 'This is a placeholder module. Customize this file to add your own settings and configuration options.', 'user-admin-dashboard' ); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}
