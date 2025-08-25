# RMS Installation Guide

## Quick Setup Instructions

### 1. Database Setup

**Option A: Automatic Installation (Recommended)**
1. Open your web browser
2. Navigate to: `http://localhost/rms/install/database_installer.php`
3. The installer will automatically create the database and tables
4. Follow the on-screen instructions

**Option B: Manual Installation**
1. Create a MySQL database named `rms`
2. Import the SQL file: `database/rms_schema.sql`
3. Update database configuration in `application/config/database.php`

### 2. Configuration

Update the database configuration in `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'rms',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### 3. File Permissions

Ensure the following directories are writable:
- `application/logs/`
- `uploads/` (create if doesn't exist)
- `assets/cache/` (create if doesn't exist)

### 4. Web Server Setup

**For XAMPP/WAMP:**
1. Place the RMS folder in your `htdocs` directory
2. Start Apache and MySQL
3. Navigate to `http://localhost/rms/`

**For Production:**
1. Upload files to your web server
2. Point your domain to the RMS directory
3. Ensure mod_rewrite is enabled for clean URLs

### 5. Verification

After installation:
1. Navigate to `http://localhost/rms/`
2. You should see the modern dashboard
3. If you see database errors, run the installer again

## Troubleshooting

### Common Issues

**Database Connection Error:**
- Check your database credentials in `application/config/database.php`
- Ensure MySQL service is running
- Verify the database exists

**Table doesn't exist errors:**
- Run the database installer: `http://localhost/rms/install/database_installer.php`
- Or manually import `database/rms_schema.sql`

**Permission Denied:**
- Check file permissions on logs and upload directories
- Ensure web server has write access

**CSS/JS not loading:**
- Check that the `assets` folder is accessible
- Verify the base URL in `application/config/config.php`

### System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for clean URLs)

### Default Data

The installer creates sample data including:
- 5 sample properties
- 5 sample customers  
- 5 sample staff members
- Sample registrations and transactions

You can delete this sample data after testing.

## Next Steps

After successful installation:

1. **Explore the Dashboard**: Navigate to see the modern interface
2. **Add Real Data**: Start adding your actual properties, customers, and staff
3. **Configure Settings**: Customize the system for your needs
4. **Set Up Users**: Create user accounts for your team (coming in future updates)
5. **Backup**: Set up regular database backups

## Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review the error logs in `application/logs/`
3. Ensure all requirements are met
4. Try the automatic installer if manual setup fails

## Security Notes

For production deployment:
1. Change default database passwords
2. Set `ENVIRONMENT` to 'production' in `index.php`
3. Remove or secure the `install/` directory
4. Enable HTTPS
5. Set up proper file permissions
6. Configure firewall rules

The system includes built-in security features:
- Input validation and sanitization
- CSRF protection
- XSS prevention
- SQL injection protection
- Secure file upload handling