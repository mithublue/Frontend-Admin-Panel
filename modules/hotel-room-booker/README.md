# Hotel Room Booker Module - PDF Generation Setup

The Hotel Room Booker module requires a PDF generation library. You have two options:

## Option 1: Install TCPDF via Composer (Recommended)

1. Navigate to the plugin directory:
   ```bash
   cd d:\laragon\www\projects\hotel-room-booker\site\wp-content\plugins\user-admin-dashboard
   ```

2. Install TCPDF:
   ```bash
   composer require tecnickcom/tcpdf
   ```

3. The module will automatically use the library from `vendor/tecnickcom/tcpdf/tcpdf.php`

## Option 2: Manual TCPDF Installation

1. Download TCPDF from: https://github.com/tecnickcom/TCPDF/releases
2. Extract the files
3. Create directory: `includes/tcpdf/`
4. Copy the TCPDF files to that directory
5. Ensure `tcpdf.php` is at: `includes/tcpdf/tcpdf.php`

## Option 3: Use Alternative PDF Library

If you prefer a different PDF library, you can modify the `create_pdf()` method in:
`modules/hotel-room-booker/module.php`

## Verification

After installing TCPDF, test the module by:
1. Activating the plugin
2. Going to the Admin Dashboard page
3. Clicking "Hotel Room Booker" in the menu
4. Filling out the form
5. Clicking "Generate PDF"

## Current Status

The module is fully functional except for PDF generation. Once you install TCPDF using one of the methods above, PDF generation will work automatically.
