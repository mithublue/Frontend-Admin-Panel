# External Module Registration Guide

This guide explains how external plugins can register custom modules in the User Admin Dashboard.

## Two Methods for Registration

### Method 1: Using the Filter Hook (Recommended)

The easiest way to register modules from an external plugin is using the `uad_register_modules` filter hook.

```php
<?php
/**
 * Plugin Name: My Custom Dashboard Module
 * Description: Adds a custom module to User Admin Dashboard
 * Version: 1.0.0
 */

// Hook into the module registration filter
add_filter( 'uad_register_modules', 'my_plugin_register_modules' );

function my_plugin_register_modules( $modules ) {
    // Make sure base module class is available
    if ( ! class_exists( 'UAD_Module' ) ) {
        return $modules;
    }

    // Include your module file
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-custom-module.php';

    // Add your module instance to the array
    $modules[] = new My_Custom_Module();

    return $modules;
}
```

#### Your Module Class Example

```php
<?php
/**
 * Custom Module Class
 * File: includes/class-my-custom-module.php
 */

class My_Custom_Module extends UAD_Module {

    /**
     * Constructor
     */
    public function __construct() {
        // Set required properties
        $this->id = 'my-custom-module';
        $this->name = __( 'My Custom Module', 'my-plugin' );
        $this->icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />';
        $this->priority = 20; // Higher number = lower in menu
        $this->capability = 'manage_options'; // Required user capability
    }

    /**
     * Initialize module (optional)
     */
    public function init() {
        // Register AJAX handlers, hooks, etc.
        add_action( 'wp_ajax_my_custom_action', array( $this, 'handle_ajax' ) );
    }

    /**
     * Render the module content
     */
    public function render() {
        ?>
        <div class="uad-card">
            <div class="uad-card-header">
                <h3><?php echo esc_html( $this->name ); ?></h3>
                <p class="text-sm text-gray-600 mt-1">
                    <?php esc_html_e( 'Custom module description', 'my-plugin' ); ?>
                </p>
            </div>
            <div class="uad-card-body">
                <!-- Your module content here -->
                <p>Hello from my custom module!</p>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler example
     */
    public function handle_ajax() {
        check_ajax_referer( 'uad_dashboard_nonce', 'nonce' );
        
        // Your AJAX logic here
        wp_send_json_success( array( 'message' => 'Success!' ) );
    }
}
```

### Method 2: Programmatic Registration

You can also register modules programmatically at any time:

```php
<?php
/**
 * Register module after UAD is loaded
 */
add_action( 'plugins_loaded', 'my_plugin_register_module', 20 );

function my_plugin_register_module() {
    // Check if UAD is active
    if ( ! class_exists( 'User_Admin_Dashboard' ) ) {
        return;
    }

    // Get the plugin instance
    global $uad_plugin;
    
    if ( ! $uad_plugin ) {
        return;
    }

    // Get the module loader
    $module_loader = $uad_plugin->get_module_loader();

    // Include your module
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-custom-module.php';

    // Register the module
    $module_loader->register_module( new My_Custom_Module() );
}
```

## Module Properties

### Required Properties

- **`$id`** (string) - Unique identifier for the module (kebab-case)
- **`$name`** (string) - Display name shown in the menu
- **`$icon`** (string) - SVG path for the menu icon

### Optional Properties

- **`$priority`** (int) - Menu order (default: 10, lower = higher in menu)
- **`$capability`** (string) - Required WordPress capability (default: 'read')

## Module Methods

### Required Methods

- **`render()`** - Outputs the module content (HTML)

### Optional Methods

- **`init()`** - Called when module is registered (for hooks, AJAX, etc.)
- **`can_access()`** - Override to add custom access control
- **`get_js_data()`** - Return data to pass to JavaScript

## Access Control

By default, modules check if the current user has the required capability. You can override this:

```php
public function can_access() {
    // Custom access logic
    return current_user_can( 'manage_options' ) && is_user_admin();
}
```

## Available CSS Classes

Use these pre-defined classes for consistent styling:

- `.uad-card` - Card container
- `.uad-card-header` - Card header section
- `.uad-card-body` - Card content area
- `.uad-action-btn` - Button styling
- `.uad-action-btn-primary` - Primary button
- `.uad-action-btn-danger` - Danger/delete button

## JavaScript Integration

Your module can enqueue its own JavaScript:

```php
public function init() {
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
}

public function enqueue_scripts() {
    global $post;
    if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'user_admin_dashboard' ) ) {
        return;
    }

    wp_enqueue_script(
        'my-module-script',
        plugins_url( 'assets/js/my-module.js', __FILE__ ),
        array( 'jquery' ),
        '1.0.0',
        true
    );
}
```

## Complete Example Plugin

See the `hotel-room-booker` module in the User Admin Dashboard plugin for a complete working example:

```
user-admin-dashboard/
  └── modules/
      └── hotel-room-booker/
          ├── module.php
          ├── hotel-booking.js
          └── README.md
```

## Checking if Dashboard is Active

Always check if the dashboard plugin is active before registering:

```php
if ( ! class_exists( 'UAD_Module' ) ) {
    // Dashboard not active, show admin notice
    add_action( 'admin_notices', 'my_plugin_missing_dashboard_notice' );
    return;
}
```

## Testing Your Module

1. Activate the User Admin Dashboard plugin
2. Activate your external plugin
3. Visit a page with the `[user_admin_dashboard]` shortcode
4. Your module should appear in the sidebar menu
5. Click it to view your module content

## Troubleshooting

**Module not appearing?**
- Check that UAD_Module class exists
- Verify your module extends UAD_Module
- Check user has the required capability
- Look for PHP errors in debug log

**Can't access module loader?**
- Ensure UAD plugin is loaded first (use `plugins_loaded` hook with priority 20+)
- Check that global `$uad_plugin` variable exists

## Support

For more examples and support, see:
- Main plugin documentation: `MODULES.md`
- Example modules in: `modules/` directory
