<?php
/**
 * Dashboard module.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Module_Dashboard extends UAD_Module {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = 'dashboard';
        $this->name = __( 'Dashboard', 'user-admin-dashboard' );
        $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />';
        $this->priority = 10;
        $this->capability = 'read';
    }

    /**
     * Render the dashboard module.
     */
    public function render() {
        ?>
        <div class="uad-stats-grid">
            <div class="uad-stat-card">
                <div class="uad-stat-icon uad-stat-icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="uad-stat-content">
                    <h3 class="uad-stat-label"><?php esc_html_e( 'Total Users', 'user-admin-dashboard' ); ?></h3>
                    <p class="uad-stat-value"><?php echo esc_html( count_users()['total_users'] ); ?></p>
                    <span class="uad-stat-change uad-stat-up">+12%</span>
                </div>
            </div>

            <div class="uad-stat-card">
                <div class="uad-stat-icon uad-stat-icon-green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="uad-stat-content">
                    <h3 class="uad-stat-label"><?php esc_html_e( 'Active Sessions', 'user-admin-dashboard' ); ?></h3>
                    <p class="uad-stat-value">1,234</p>
                    <span class="uad-stat-change uad-stat-up">+8%</span>
                </div>
            </div>

            <div class="uad-stat-card">
                <div class="uad-stat-icon uad-stat-icon-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div class="uad-stat-content">
                    <h3 class="uad-stat-label"><?php esc_html_e( 'Page Views', 'user-admin-dashboard' ); ?></h3>
                    <p class="uad-stat-value">45.2K</p>
                    <span class="uad-stat-change uad-stat-up">+23%</span>
                </div>
            </div>

            <div class="uad-stat-card">
                <div class="uad-stat-icon uad-stat-icon-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="uad-stat-content">
                    <h3 class="uad-stat-label"><?php esc_html_e( 'Growth Rate', 'user-admin-dashboard' ); ?></h3>
                    <p class="uad-stat-value">+18.5%</p>
                    <span class="uad-stat-change uad-stat-up">+5%</span>
                </div>
            </div>
        </div>

        <div class="uad-content-grid">
            <div class="uad-card">
                <div class="uad-card-header">
                    <h3><?php esc_html_e( 'Recent Activity', 'user-admin-dashboard' ); ?></h3>
                </div>
                <div class="uad-card-body">
                    <div class="uad-activity-list">
                        <div class="uad-activity-item">
                            <div class="uad-activity-icon uad-activity-icon-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="uad-activity-content">
                                <p class="uad-activity-text"><?php esc_html_e( 'New user registered', 'user-admin-dashboard' ); ?></p>
                                <span class="uad-activity-time"><?php esc_html_e( '2 minutes ago', 'user-admin-dashboard' ); ?></span>
                            </div>
                        </div>
                        <div class="uad-activity-item">
                            <div class="uad-activity-icon uad-activity-icon-green">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="uad-activity-content">
                                <p class="uad-activity-text"><?php esc_html_e( 'Settings updated successfully', 'user-admin-dashboard' ); ?></p>
                                <span class="uad-activity-time"><?php esc_html_e( '1 hour ago', 'user-admin-dashboard' ); ?></span>
                            </div>
                        </div>
                        <div class="uad-activity-item">
                            <div class="uad-activity-icon uad-activity-icon-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <div class="uad-activity-content">
                                <p class="uad-activity-text"><?php esc_html_e( 'New comment received', 'user-admin-dashboard' ); ?></p>
                                <span class="uad-activity-time"><?php esc_html_e( '3 hours ago', 'user-admin-dashboard' ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uad-card">
                <div class="uad-card-header">
                    <h3><?php esc_html_e( 'Quick Actions', 'user-admin-dashboard' ); ?></h3>
                </div>
                <div class="uad-card-body">
                    <div class="uad-quick-actions">
                        <button class="uad-action-btn uad-action-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <?php esc_html_e( 'Add New User', 'user-admin-dashboard' ); ?>
                        </button>
                        <button class="uad-action-btn uad-action-btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <?php esc_html_e( 'Export Data', 'user-admin-dashboard' ); ?>
                        </button>
                        <button class="uad-action-btn uad-action-btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <?php esc_html_e( 'View Reports', 'user-admin-dashboard' ); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
