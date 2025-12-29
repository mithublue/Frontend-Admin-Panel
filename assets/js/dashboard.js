/**
 * User Admin Dashboard JavaScript
 * 
 * @package User_Admin_Dashboard
 */

(function ($) {
    'use strict';

    /**
     * Dashboard initialization
     */
    const UADashboard = {
        /**
         * Initialize the dashboard
         */
        init: function () {
            this.setupNavigation();
            this.setupSidebarToggle();
            this.setupUserMenu();
            this.setupQuickActions();
        },

        /**
         * Setup navigation between sections
         */
        setupNavigation: function () {
            $('.uad-nav-item').on('click', function (e) {
                e.preventDefault();

                const section = $(this).data('section');

                // Update active nav item
                $('.uad-nav-item').removeClass('active');
                $(this).addClass('active');

                // Update active section
                $('.uad-section').removeClass('active');
                $(`.uad-section[data-section="${section}"]`).addClass('active');

                // Update page title
                const sectionTitle = $(this).find('span').text();
                $('.uad-page-title').text(sectionTitle);

                // Update URL hash without scrolling
                if (history.pushState) {
                    history.pushState(null, null, `#${section}`);
                } else {
                    location.hash = `#${section}`;
                }
            });

            // Handle initial hash on page load
            const hash = window.location.hash.substring(1);
            if (hash) {
                $(`.uad-nav-item[data-section="${hash}"]`).trigger('click');
            }
        },

        /**
         * Setup sidebar toggle for mobile
         */
        setupSidebarToggle: function () {
            $('#uad-sidebar-toggle').on('click', function () {
                $('.uad-sidebar').toggleClass('active');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function (e) {
                if ($(window).width() < 1024) {
                    if (!$(e.target).closest('.uad-sidebar, #uad-sidebar-toggle').length) {
                        $('.uad-sidebar').removeClass('active');
                    }
                }
            });
        },

        /**
         * Setup user menu dropdown
         */
        setupUserMenu: function () {
            $('#uad-user-menu-btn').on('click', function (e) {
                e.stopPropagation();
                // Future: Add dropdown menu functionality
                console.log('User menu clicked');
            });
        },

        /**
         * Setup quick action buttons
         */
        setupQuickActions: function () {
            $('.uad-action-btn').on('click', function (e) {
                e.preventDefault();

                const action = $(this).text().trim();

                // Show loading state
                const originalText = $(this).html();
                $(this).prop('disabled', true).html(`
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `);

                // Simulate action
                setTimeout(() => {
                    $(this).prop('disabled', false).html(originalText);
                    UADashboard.showNotification(`${action} completed!`, 'success');
                }, 1500);
            });
        },

        /**
         * Show notification
         */
        showNotification: function (message, type = 'info') {
            const colors = {
                success: 'bg-success-500',
                error: 'bg-danger-500',
                warning: 'bg-warning-500',
                info: 'bg-primary-500'
            };

            const notification = $(`
                <div class="fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-soft-lg animate-slide-up z-50">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>${message}</span>
                    </div>
                </div>
            `);

            $('body').append(notification);

            setTimeout(() => {
                notification.fadeOut(300, function () {
                    $(this).remove();
                });
            }, 3000);
        },

        /**
         * AJAX helper
         */
        ajax: function (action, data = {}) {
            return $.ajax({
                url: uadData.ajaxUrl,
                type: 'POST',
                data: {
                    action: `uad_${action}`,
                    nonce: uadData.nonce,
                    ...data
                }
            });
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function () {
        if ($('#uad-dashboard').length) {
            UADashboard.init();
        }
    });

    // Make UADashboard globally accessible
    window.UADashboard = UADashboard;

})(jQuery);
