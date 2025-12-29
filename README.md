# User Admin Dashboard - WordPress Plugin

A modern, modular WordPress plugin that provides a beautiful admin dashboard accessible via shortcode, built with TailwindCSS.

## Features

- ✨ **Modern UI**: Beautiful dashboard with TailwindCSS styling, gradients, and smooth animations
- 🔐 **Authentication**: Secure login system with role-based access control
- 📱 **Responsive Design**: Mobile-first approach that works on all devices
- 🎯 **Shortcode Based**: Easy integration using `[user_admin_dashboard]` shortcode
- 🏗️ **Modular Architecture**: Clean, object-oriented code structure
- ⚡ **Auto Page Creation**: Automatically creates dashboard page on activation
- 🎨 **Premium Design**: Gradient backgrounds, glassmorphism effects, and micro-animations

## Installation

1. Upload the `user-admin-dashboard` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin will automatically create an "Admin Dashboard" page with the shortcode
4. Visit the page to see your dashboard (login required)

## Development

### Prerequisites

- Node.js and npm (for building TailwindCSS)
- WordPress 5.0 or higher
- PHP 7.4 or higher

### Building Assets

```bash
# Install dependencies
npm install

# Build CSS for production
npm run build

# Watch for changes during development
npm run dev
```

## File Structure

```
user-admin-dashboard/
├── user-admin-dashboard.php    # Main plugin file
├── uninstall.php                # Cleanup on uninstall
├── includes/                    # Core classes
│   ├── class-core.php
│   ├── class-loader.php
│   ├── class-activator.php
│   └── class-deactivator.php
├── admin/                       # Admin functionality
│   ├── class-admin.php
│   └── class-shortcode.php
├── public/                      # Public functionality
│   ├── class-public.php
│   └── class-auth.php
├── assets/
│   ├── src/                     # Source files
│   │   └── styles.css
│   ├── dist/                    # Compiled files
│   │   └── styles.css
│   └── js/
│       └── dashboard.js
└── package.json                 # Node dependencies
```

## Usage

### Shortcode

Simply add the shortcode to any page or post:

```
[user_admin_dashboard]
```

### Access Control

By default, only administrators can access the dashboard. To change this, modify the `uad_min_user_role` option:

```php
update_option( 'uad_min_user_role', 'editor' ); // Allow editors and above
```

Available roles:
- `administrator` - Full access
- `editor` - Can edit pages
- `author` - Can publish posts
- `contributor` - Can edit posts
- `subscriber` - Basic access

### Customization

The dashboard includes several sections:
- **Dashboard**: Overview with statistics and recent activity
- **Analytics**: Analytics overview (customizable)
- **Users**: User management (customizable)
- **Settings**: Settings panel (customizable)

## Dashboard Features

### Statistics Cards
- Total Users
- Active Sessions
- Page Views
- Growth Rate

### Recent Activity
- Real-time activity feed
- Color-coded activity types
- Timestamp display

### Quick Actions
- Add New User
- Export Data
- View Reports

## JavaScript API

The plugin includes a JavaScript API for custom interactions:

```javascript
// Show notification
UADashboard.showNotification('Success!', 'success');

// Make AJAX request
UADashboard.ajax('custom_action', { data: 'value' })
  .done(function(response) {
    console.log(response);
  });
```

## Hooks & Filters

### Actions

```php
// After dashboard is rendered
do_action( 'uad_after_dashboard_render' );
```

### Filters

```php
// Modify minimum user role
add_filter( 'uad_min_user_role', function( $role ) {
    return 'editor';
});
```

## Security

- Nonce verification for AJAX requests
- Role-based access control
- Sanitized inputs and escaped outputs
- Secure authentication checks

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## License

GPL-2.0+

## Author

Your Name

## Support

For support, please visit [your support URL]

## Changelog

### 1.0.0
- Initial release
- Dashboard with statistics
- User authentication
- TailwindCSS integration
- Responsive design
- Auto page creation
