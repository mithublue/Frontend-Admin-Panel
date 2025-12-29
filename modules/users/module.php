<?php
/**
 * Users module.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Module_Users extends UAD_Module {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = 'users';
        $this->name = __( 'Users', 'user-admin-dashboard' );
        $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />';
        $this->priority = 30;
        $this->capability = 'list_users';
    }

    /**
     * Render the users module.
     */
    public function render() {
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php esc_html_e( 'User Management', 'user-admin-dashboard' ); ?></h3>
            </div>
            <div class="uad-card-body">
                <p><?php esc_html_e( 'User management features will be displayed here. You can add user lists, editing capabilities, and role management.', 'user-admin-dashboard' ); ?></p>
                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <?php esc_html_e( 'This is a placeholder module. Customize this file to add your own user management features.', 'user-admin-dashboard' ); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}
