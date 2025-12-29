<?php
/**
 * Hotel Room Booker module.
 *
 * @package User_Admin_Dashboard
 */

class UAD_Module_Hotel_Room_Booker extends UAD_Module {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = 'hotel-room-booker';
        $this->name = __( 'Hotel Room Booker', 'user-admin-dashboard' );
        $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
        $this->priority = 15;
        $this->capability = 'edit_posts';
    }

    /**
     * Initialize the module.
     */
    public function init() {
        // Register AJAX handlers
        add_action( 'wp_ajax_uad_generate_hotel_pdf', array( $this, 'generate_pdf' ) );
        
        // Print script in footer
        add_action( 'wp_footer', array( $this, 'print_footer_scripts' ) );
    }

    /**
     * Print module JavaScript in footer.
     */
    public function print_footer_scripts() {
        ?>
        <script src="<?php echo UAD_PLUGIN_URL . 'modules/hotel-room-booker/hotel-booking.js?v=' . time(); ?>"></script>
        <?php
    }


    /**
     * Render the hotel room booker module.
     */
    public function render() {
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php esc_html_e( 'Hotel Reservation', 'user-admin-dashboard' ); ?></h3>
                <p class="text-sm text-gray-600 mt-1"><?php esc_html_e( 'Generate professional reservation documents', 'user-admin-dashboard' ); ?></p>
            </div>
            <div class="uad-card-body">
                <form id="uad-hotel-booking-form" class="space-y-5">
                    <!-- Row 1: Date and Guest Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="booking-date" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php esc_html_e( 'Date', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="date" id="booking-date" name="booking_date" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                        </div>
                        <div>
                            <label for="guest-name" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <?php esc_html_e( 'Guest Name', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="text" id="guest-name" name="guest_name" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   placeholder="<?php esc_attr_e( 'Enter guest name', 'user-admin-dashboard' ); ?>">
                        </div>
                    </div>

                    <!-- Row 2: Hotel Name and Number of Rooms -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="hotel-name" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <?php esc_html_e( 'Hotel Name', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="text" id="hotel-name" name="hotel_name" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   placeholder="<?php esc_attr_e( 'Enter hotel name', 'user-admin-dashboard' ); ?>">
                        </div>
                        <div>
                            <label for="num-rooms" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                <?php esc_html_e( 'Number of Rooms (Qty)', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="number" id="num-rooms" name="num_rooms" min="1" value="1" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Row 3: Check In and Check Out -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="check-in" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php esc_html_e( 'Check In', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="date" id="check-in" name="check_in" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="check-out" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php esc_html_e( 'Check Out', 'user-admin-dashboard' ); ?>
                            </label>
                            <input type="date" id="check-out" name="check_out" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Total Nights Display -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"><?php esc_html_e( 'Total Nights:', 'user-admin-dashboard' ); ?></span>
                            <span id="total-nights" class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">0</span>
                        </div>
                    </div>

                    <!-- Payment Information Section -->
                    <div class="border-t border-gray-200 pt-5 mt-2">
                        <h4 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php esc_html_e( 'Payment Information (For Confirmation Only)', 'user-admin-dashboard' ); ?>
                        </h4>
                        
                        <!-- Dynamic room charges container -->
                        <div id="room-charges-container" class="space-y-4 mb-6">
                            <!-- Room charge fields will be inserted here by JavaScript -->
                        </div>

                        <!-- Total Amount (readonly) -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mt-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700"><?php esc_html_e( 'Total Amount:', 'user-admin-dashboard' ); ?></span>
                                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">$<span id="total-amount">0.00</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Type Selection -->
                    <div class="border-t border-gray-200 pt-5 mt-2">
                        <h4 class="text-base font-semibold text-gray-900 mb-3">
                            <?php esc_html_e( 'Select PDF Type', 'user-admin-dashboard' ); ?>
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all group">
                                <input type="radio" name="pdf_type" value="tentative" checked class="sr-only peer">
                                <div class="flex-1 flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 peer-checked:bg-blue-500 transition-colors">
                                        <svg class="w-5 h-5 text-blue-600 peer-checked:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900"><?php esc_html_e( 'Tentative', 'user-admin-dashboard' ); ?></p>
                                        <p class="text-xs text-gray-600"><?php esc_html_e( 'Without payment details', 'user-admin-dashboard' ); ?></p>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-400 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all">
                                    <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all group">
                                <input type="radio" name="pdf_type" value="confirmation" class="sr-only peer">
                                <div class="flex-1 flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 peer-checked:bg-green-500 transition-colors">
                                        <svg class="w-5 h-5 text-green-600 peer-checked:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900"><?php esc_html_e( 'Confirmation', 'user-admin-dashboard' ); ?></p>
                                        <p class="text-xs text-gray-600"><?php esc_html_e( 'With payment details', 'user-admin-dashboard' ); ?></p>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-400 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center transition-all">
                                    <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 uad-action-btn uad-action-btn-primary flex items-center justify-center gap-2" id="uad-hotel-booking-form-submit">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            <?php esc_html_e( 'Generate PDF', 'user-admin-dashboard' ); ?>
                        </button>
                        <button type="reset" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            <?php esc_html_e( 'Reset', 'user-admin-dashboard' ); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Generate PDF via AJAX.
     */
    public function generate_pdf() {
        check_ajax_referer( 'uad_dashboard_nonce', 'nonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        // Get form data
        $room_charges = json_decode( stripslashes( $_POST['room_charges'] ), true );
        
        $data = array(
            'booking_date'  => sanitize_text_field( $_POST['booking_date'] ),
            'guest_name'    => sanitize_text_field( $_POST['guest_name'] ),
            'hotel_name'    => sanitize_text_field( $_POST['hotel_name'] ),
            'num_rooms'     => intval( $_POST['num_rooms'] ),
            'check_in'      => sanitize_text_field( $_POST['check_in'] ),
            'check_out'     => sanitize_text_field( $_POST['check_out'] ),
            'total_nights'  => intval( $_POST['total_nights'] ),
            'room_charges'  => $room_charges,
            'total_amount'  => floatval( $_POST['total_amount'] ),
            'pdf_type'      => sanitize_text_field( $_POST['pdf_type'] ),
        );

        // Generate and output PDF directly
        // This function will exit after sending the PDF
        $this->create_pdf( $data );
        
        // If we reach here, PDF generation failed
        wp_send_json_error( array( 'message' => 'Failed to generate PDF' ) );
    }

    /**
     * Create PDF file.
     *
     * @param array $data Booking data.
     * @return bool Success status.
     */
    private function create_pdf( $data ) {
        // Try to load TCPDF from multiple locations
        $tcpdf_loaded = false;
        $tcpdf_paths = array(
            UAD_PLUGIN_DIR . 'vendor/tecnickcom/tcpdf/tcpdf.php', // Composer
            UAD_PLUGIN_DIR . 'includes/tcpdf/tcpdf.php',           // Manual install
            ABSPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php',         // WordPress root
        );

        foreach ( $tcpdf_paths as $path ) {
            if ( file_exists( $path ) ) {
                require_once $path;
                $tcpdf_loaded = true;
                break;
            }
        }

        if ( ! $tcpdf_loaded ) {
            error_log( 'TCPDF library not found. Please install it via Composer or manually.' );
            return false;
        }

        try {
            // Create new PDF document
            $pdf = new TCPDF( 'P', 'mm', 'A4', true, 'UTF-8', false );

            // Set document information
            $pdf->SetCreator( 'Hotel Room Booker' );
            $pdf->SetAuthor( get_bloginfo( 'name' ) );
            $pdf->SetTitle( 'Hotel Reservation - ' . $data['guest_name'] );

            // Remove default header/footer
            $pdf->setPrintHeader( false );
            $pdf->setPrintFooter( false );

            // Set margins
            $pdf->SetMargins( 15, 15, 15 );
            $pdf->SetAutoPageBreak( true, 15 );

            // Add a page
            $pdf->AddPage();

            // Set font
            $pdf->SetFont( 'helvetica', '', 11 );

            // Build HTML content
            $html = $this->get_pdf_html( $data );

            // Output the HTML content
            $pdf->writeHTML( $html, true, false, true, false, '' );

            // Generate filename
            $filename = 'hotel-reservation-' . sanitize_file_name( $data['guest_name'] ) . '-' . time() . '.pdf';

            // Clean any output buffers
            if ( ob_get_level() ) {
                ob_end_clean();
            }

            // Set headers for download
            header( 'Content-Type: application/pdf' );
            header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
            header( 'Cache-Control: private, max-age=0, must-revalidate' );
            header( 'Pragma: public' );

            // Output PDF directly to browser
            echo $pdf->Output( $filename, 'S' );
            
            // Exit to prevent WordPress from adding extra output
            exit;

        } catch ( Exception $e ) {
            error_log( 'PDF Generation Error: ' . $e->getMessage() );
            return false;
        }
    }

    /**
     * Get PDF HTML content.
     *
     * @param array $data Booking data.
     * @return string HTML content.
     */
    private function get_pdf_html( $data ) {
        $is_confirmation = ( $data['pdf_type'] === 'confirmation' );
        
        $html = '
        <style>
            h1 { color: #2563eb; font-size: 24px; margin-bottom: 5px; }
            h2 { color: #1e40af; font-size: 18px; margin-top: 20px; margin-bottom: 10px; }
            .header { text-align: center; margin-bottom: 30px; }
            .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .info-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
            .info-table td:first-child { font-weight: bold; width: 40%; color: #374151; }
            .total-box { background-color: #dbeafe; padding: 15px; border-radius: 5px; margin-top: 20px; }
            .footer { margin-top: 40px; text-align: center; color: #6b7280; font-size: 10px; }
        </style>

        <div class="header">
            <h1>Hotel Reservation ' . ( $is_confirmation ? 'Confirmation' : 'Tentative' ) . '</h1>
            <p style="color: #6b7280;">Generated on ' . date( 'F j, Y' ) . '</p>
        </div>

        <h2>Booking Information</h2>
        <table class="info-table">
            <tr>
                <td>Booking Date:</td>
                <td>' . esc_html( date( 'F j, Y', strtotime( $data['booking_date'] ) ) ) . '</td>
            </tr>
            <tr>
                <td>Guest Name:</td>
                <td>' . esc_html( $data['guest_name'] ) . '</td>
            </tr>
            <tr>
                <td>Hotel Name:</td>
                <td>' . esc_html( $data['hotel_name'] ) . '</td>
            </tr>
            <tr>
                <td>Number of Rooms:</td>
                <td>' . esc_html( $data['num_rooms'] ) . '</td>
            </tr>
        </table>

        <h2>Stay Details</h2>
        <table class="info-table">
            <tr>
                <td>Check-In Date:</td>
                <td>' . esc_html( date( 'F j, Y', strtotime( $data['check_in'] ) ) ) . '</td>
            </tr>
            <tr>
                <td>Check-Out Date:</td>
                <td>' . esc_html( date( 'F j, Y', strtotime( $data['check_out'] ) ) ) . '</td>
            </tr>
            <tr>
                <td>Total Nights:</td>
                <td>' . esc_html( $data['total_nights'] ) . '</td>
            </tr>
        </table>';

        if ( $is_confirmation ) {
            $html .= '
            <h2>Payment Information</h2>
            <table class="info-table">
                <tr>
                    <td><strong>Room</strong></td>
                    <td><strong>Charge per Night</strong></td>
                    <td><strong>Total Nights</strong></td>
                    <td><strong>Subtotal</strong></td>
                </tr>';
            
            // Loop through room charges
            if ( ! empty( $data['room_charges'] ) && is_array( $data['room_charges'] ) ) {
                foreach ( $data['room_charges'] as $room_charge ) {
                    $room_num = intval( $room_charge['room'] );
                    $charge = floatval( $room_charge['charge'] );
                    $subtotal = $charge * $data['total_nights'];
                    
                    $html .= '
                    <tr>
                        <td>Room #' . $room_num . '</td>
                        <td>$' . number_format( $charge, 2 ) . '</td>
                        <td>' . esc_html( $data['total_nights'] ) . '</td>
                        <td>$' . number_format( $subtotal, 2 ) . '</td>
                    </tr>';
                }
            }
            
            $html .= '
            </table>

            <div class="total-box">
                <h2 style="margin: 0; color: #1e40af;">Total Amount: $' . number_format( $data['total_amount'], 2 ) . '</h2>
            </div>';
        }

        $html .= '
        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated by CyberCraft and Mithu A Quayium</p>
        </div>';

        return $html;
    }
}
