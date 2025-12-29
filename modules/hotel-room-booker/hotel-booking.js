/**
 * Hotel Room Booker module JavaScript
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Generate room charge fields based on number of rooms
        function generateRoomChargeFields() {
            const numRooms = parseInt($('#num-rooms').val()) || 1;
            const container = $('#room-charges-container');

            container.empty();

            for (let i = 1; i <= numRooms; i++) {
                const fieldHtml = `
                    <div class="room-charge-field mb-3">
                        <label for="charge-room-${i}" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Charge per Night for Room #${i}
                        </label>
                        <input type="number" id="charge-room-${i}" name="charge_room_${i}" min="0" step="0.01" value="0" 
                               class="room-charge-input w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="0.00" data-room="${i}">
                    </div>
                `;
                container.append(fieldHtml);
            }

            // Attach event listeners to new fields
            $('.room-charge-input').on('input change', calculateTotal);

            // Recalculate total
            calculateTotal();
        }

        // Calculate total nights
        function calculateNights() {
            const checkIn = $('#check-in').val();
            const checkOut = $('#check-out').val();

            if (checkIn && checkOut) {
                const start = new Date(checkIn);
                const end = new Date(checkOut);
                const diffTime = end - start;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    $('#total-nights').text(diffDays);
                    calculateTotal();
                } else {
                    $('#total-nights').text(0);
                    $('#total-amount').text('0.00');
                }
            } else {
                $('#total-nights').text(0);
                $('#total-amount').text('0.00');
            }
        }

        // Calculate total amount from all room charges
        function calculateTotal() {
            const nights = parseInt($('#total-nights').text()) || 0;
            let totalAmount = 0;

            // Sum up all room charges
            $('.room-charge-input').each(function () {
                const chargePerNight = parseFloat($(this).val()) || 0;
                totalAmount += nights * chargePerNight;
            });

            $('#total-amount').text(totalAmount.toFixed(2));
        }

        // Event listeners
        $('#check-in, #check-out').on('change', calculateNights);
        $('#num-rooms').on('input change', function () {
            generateRoomChargeFields();
        });

        // Initialize with 1 room by default
        generateRoomChargeFields();

        // Debug: Check if form exists
        console.log('Form found:', $('#uad-hotel-booking-form').length);
        console.log('Submit button found:', $('button[type="submit"]').length);

        // Form submission handler
        function handlePDFGeneration(e) {
            if (e) {
                e.preventDefault();
            }

            console.log('PDF Generation triggered!');

            // Collect room charges
            const roomCharges = [];
            $('.room-charge-input').each(function () {
                roomCharges.push({
                    room: $(this).data('room'),
                    charge: parseFloat($(this).val()) || 0
                });
            });

            const formData = {
                action: 'uad_generate_hotel_pdf',
                nonce: uadData.nonce,
                booking_date: $('#booking-date').val(),
                guest_name: $('#guest-name').val(),
                hotel_name: $('#hotel-name').val(),
                num_rooms: $('#num-rooms').val(),
                check_in: $('#check-in').val(),
                check_out: $('#check-out').val(),
                total_nights: $('#total-nights').text(),
                room_charges: JSON.stringify(roomCharges),
                total_amount: $('#total-amount').text(),
                pdf_type: $('input[name="pdf_type"]:checked').val()
            };

            console.log('Submitting form data:', formData);

            // Show loading state
            const $submitBtn = $('button[type="submit"]');
            const originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html(`
                <svg class="animate-spin w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `);

            $.ajax({
                url: uadData.ajaxUrl,
                type: 'POST',
                data: formData,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (blob, status, xhr) {
                    console.log('Response received');
                    console.log('Blob:', blob);
                    console.log('Blob type:', blob.type);
                    console.log('Blob size:', blob.size);
                    console.log('Status:', status);
                    console.log('Content-Type:', xhr.getResponseHeader('Content-Type'));
                    console.log('Content-Disposition:', xhr.getResponseHeader('Content-Disposition'));

                    // Get filename from Content-Disposition header or use default
                    const disposition = xhr.getResponseHeader('Content-Disposition');
                    let filename = 'hotel-reservation.pdf';
                    if (disposition && disposition.indexOf('filename=') !== -1) {
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    console.log('Filename:', filename);

                    // Create download link
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    console.log('Triggering download...');
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);

                    if (typeof UADashboard !== 'undefined') {
                        UADashboard.showNotification('PDF generated successfully!', 'success');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('XHR:', xhr);

                    let errorMsg = 'Failed to generate PDF';
                    if (xhr.responseText) {
                        console.error('Response text:', xhr.responseText);
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMsg = response.data?.message || errorMsg;
                        } catch (e) {
                            console.error('Could not parse error response');
                        }
                    }
                    if (typeof UADashboard !== 'undefined') {
                        UADashboard.showNotification(errorMsg, 'error');
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function () {
                    console.log('Request complete');
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        }

        // Attach to form submit
        $('#uad-hotel-booking-form').on('submit', function (e) {
            console.log('Form submit event fired');
            handlePDFGeneration(e);
        });

        // Also attach directly to button as fallback
        $('#uad-hotel-booking-form-submit').on('click', function (e) {
            console.log('Button click event fired');
            e.preventDefault(); // Prevent default form submission
            handlePDFGeneration(e);
        });

        // Reset form
        $('#uad-hotel-booking-form').on('reset', function () {
            setTimeout(function () {
                $('#total-nights').text('0');
                $('#total-amount').text('0.00');
                generateRoomChargeFields(); // Reset to 1 room
            }, 10);
        });
    });

})(jQuery);
