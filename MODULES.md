# Creating Custom Dashboard Modules

This guide explains how to create custom modules for the User Admin Dashboard plugin.

## Quick Start

To add a new menu item to the dashboard, simply create a new folder in the `modules/` directory with a `module.php` file.

### Example: Creating a "Reports" Module

1. **Create the module directory**:
   ```
   modules/reports/
   ```

2. **Create `module.php`** in that directory:
   ```php
   <?php
   /**
    * Reports module.
    *
    * @package User_Admin_Dashboard
    */

   class UAD_Module_Reports extends UAD_Module {

       public function __construct() {
           $this->id = 'reports';
           $this->name = __( 'Reports', 'user-admin-dashboard' );
           $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
           $this->priority = 25;
           $this->capability = 'edit_posts';
       }

       public function render() {
           ?>
           <div class="uad-card">
               <div class="uad-card-header">
                   <h3><?php esc_html_e( 'Reports', 'user-admin-dashboard' ); ?></h3>
               </div>
               <div class="uad-card-body">
                   <p><?php esc_html_e( 'Your reports content goes here.', 'user-admin-dashboard' ); ?></p>
               </div>
           </div>
           <?php
       }
   }
   ```

3. **That's it!** The module will automatically appear in the dashboard menu.

## Module Properties

### Required Properties

- **`$id`** (string): Unique identifier for the module (slug format, e.g., 'reports')
- **`$name`** (string): Display name shown in the menu
- **`$icon`** (string): SVG path data for the menu icon
- **`render()`** (method): Function that outputs the module content

### Optional Properties

- **`$priority`** (int): Menu order (lower = higher, default: 10)
- **`$capability`** (string): WordPress capability required to access (default: 'read')

## Module Class Structure

```php
<?php
class UAD_Module_YourModule extends UAD_Module {
    
    public function __construct() {
        // Set module properties
        $this->id = 'your-module';
        $this->name = __( 'Your Module', 'user-admin-dashboard' );
        $this->icon = '<path ... />';
        $this->priority = 30;
        $this->capability = 'manage_options';
    }

    public function init() {
        // Optional: Add hooks, enqueue scripts, etc.
        add_action( 'wp_ajax_your_action', array( $this, 'handle_ajax' ) );
    }

    public function render() {
        // Required: Output your module content
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php echo esc_html( $this->name ); ?></h3>
            </div>
            <div class="uad-card-body">
                <!-- Your content here -->
            </div>
        </div>
        <?php
    }

    public function get_js_data() {
        // Optional: Return data for JavaScript
        return array(
            'setting1' => get_option( 'your_setting' ),
        );
    }

    public function handle_ajax() {
        // Optional: Handle AJAX requests
        check_ajax_referer( 'uad_dashboard_nonce', 'nonce' );
        // Your AJAX logic
        wp_send_json_success( array( 'message' => 'Success!' ) );
    }
}
```

## Finding Icons

Get SVG path data from [Heroicons](https://heroicons.com/):

1. Choose an icon
2. Copy the `<path>` element's content
3. Use it as the `$icon` property

Example:
```php
$this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />';
```

## Capabilities

Common WordPress capabilities:

- `read` - All logged-in users
- `edit_posts` - Contributors and above
- `publish_posts` - Authors and above
- `edit_pages` - Editors and above
- `manage_options` - Administrators only
- `list_users` - Administrators only

## Priority System

Modules are ordered by priority (lower number = higher in menu):

- 10 - Dashboard (default)
- 20 - Analytics
- 30 - Users
- 40 - Settings

## Available CSS Classes

Use these pre-styled classes in your module:

### Cards
```html
<div class="uad-card">
    <div class="uad-card-header">
        <h3>Title</h3>
    </div>
    <div class="uad-card-body">
        Content
    </div>
</div>
```

### Buttons
```html
<button class="uad-action-btn uad-action-btn-primary">Primary</button>
<button class="uad-action-btn uad-action-btn-secondary">Secondary</button>
```

### Stats Cards
```html
<div class="uad-stat-card">
    <div class="uad-stat-icon uad-stat-icon-blue">
        <svg>...</svg>
    </div>
    <div class="uad-stat-content">
        <h3 class="uad-stat-label">Label</h3>
        <p class="uad-stat-value">Value</p>
    </div>
</div>
```

Colors: `blue`, `green`, `purple`, `orange`

## Advanced: Module with AJAX

```php
<?php
class UAD_Module_Advanced extends UAD_Module {
    
    public function __construct() {
        $this->id = 'advanced';
        $this->name = __( 'Advanced', 'user-admin-dashboard' );
        $this->icon = '<path ... />';
        $this->capability = 'manage_options';
    }

    public function init() {
        // Register AJAX handlers
        add_action( 'wp_ajax_uad_save_settings', array( $this, 'save_settings' ) );
    }

    public function render() {
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php esc_html_e( 'Settings', 'user-admin-dashboard' ); ?></h3>
            </div>
            <div class="uad-card-body">
                <form id="uad-settings-form">
                    <input type="text" name="setting_name" value="<?php echo esc_attr( get_option( 'setting_name' ) ); ?>">
                    <button type="submit" class="uad-action-btn uad-action-btn-primary">
                        <?php esc_html_e( 'Save', 'user-admin-dashboard' ); ?>
                    </button>
                </form>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#uad-settings-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: uadData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'uad_save_settings',
                        nonce: uadData.nonce,
                        setting_name: $('[name="setting_name"]').val()
                    },
                    success: function(response) {
                        UADashboard.showNotification('Settings saved!', 'success');
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function save_settings() {
        check_ajax_referer( 'uad_dashboard_nonce', 'nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $setting_name = sanitize_text_field( $_POST['setting_name'] );
        update_option( 'setting_name', $setting_name );

        wp_send_json_success( array( 'message' => 'Settings saved!' ) );
    }
}
```

## Module File Organization

For complex modules, you can organize files:

```
modules/reports/
├── module.php          # Main module class
├── views/
│   ├── main.php       # Main view
│   └── settings.php   # Settings view
├── assets/
│   ├── script.js      # Module-specific JS
│   └── style.css      # Module-specific CSS
└── includes/
    └── functions.php  # Helper functions
```

Load additional files in `module.php`:
```php
require_once __DIR__ . '/includes/functions.php';
```

## Tips

1. **Keep it simple**: Start with basic HTML/PHP, add complexity as needed
2. **Use existing styles**: Leverage the built-in TailwindCSS classes
3. **Check capabilities**: Always verify user permissions
4. **Sanitize & escape**: Use `sanitize_*()` for input, `esc_*()` for output
5. **Test thoroughly**: Check with different user roles

## Examples

See the built-in modules for reference:
- `modules/dashboard/module.php` - Stats and activity
- `modules/analytics/module.php` - Simple placeholder
- `modules/users/module.php` - User management placeholder
- `modules/settings/module.php` - Settings placeholder

## Troubleshooting

**Module not appearing?**
- Check class name matches pattern: `UAD_Module_{FolderName}`
- Verify file is named `module.php`
- Check for PHP errors in the file

**Access denied?**
- Verify the `capability` property matches user's permissions
- Test with an administrator account first

**Icon not showing?**
- Ensure SVG path data is complete
- Check for syntax errors in the icon string
