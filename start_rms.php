<?php
/**
 * RMS Quick Start Script
 * Helps users get started with the Real Estate Management System
 */

echo "=== Real Estate Management System (RMS) ===\n";
echo "Quick Start Guide\n\n";

// Check PHP version
$php_version = phpversion();
echo "✓ PHP Version: $php_version\n";

if (version_compare($php_version, '7.4.0', '<')) {
    echo "⚠ Warning: PHP 7.4 or higher is recommended\n";
}

// Check required extensions
$required_extensions = ['mysqli', 'json', 'mbstring', 'curl'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ Extension $ext: Available\n";
    } else {
        echo "✗ Extension $ext: Missing\n";
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    echo "\n⚠ Please install missing PHP extensions: " . implode(', ', $missing_extensions) . "\n";
}

// Check database connection
echo "\n--- Database Connection Check ---\n";

try {
    $config_file = 'application/config/database.php';
    if (file_exists($config_file)) {
        include $config_file;
        
        $db_config = $db['default'];
        echo "Database Host: {$db_config['hostname']}\n";
        echo "Database Name: {$db_config['database']}\n";
        echo "Database User: {$db_config['username']}\n";
        
        // Test connection
        $connection = new mysqli(
            $db_config['hostname'],
            $db_config['username'],
            $db_config['password'],
            $db_config['database']
        );
        
        if ($connection->connect_error) {
            echo "✗ Database Connection: Failed - " . $connection->connect_error . "\n";
            echo "\n🔧 To fix this:\n";
            echo "1. Make sure MySQL/MariaDB is running\n";
            echo "2. Check database credentials in application/config/database.php\n";
            echo "3. Create the database if it doesn't exist\n";
            echo "4. Run the installer: http://localhost/rms/install/database_installer.php\n";
        } else {
            echo "✓ Database Connection: Success\n";
            
            // Check if tables exist
            $tables = ['properties', 'customers', 'staff', 'registrations', 'transactions'];
            $existing_tables = [];
            
            foreach ($tables as $table) {
                $result = $connection->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    $existing_tables[] = $table;
                }
            }
            
            if (count($existing_tables) === count($tables)) {
                echo "✓ Database Tables: All tables exist\n";
                
                // Check for sample data
                $result = $connection->query("SELECT COUNT(*) as count FROM properties");
                $row = $result->fetch_assoc();
                echo "✓ Sample Data: {$row['count']} properties found\n";
                
            } else {
                echo "⚠ Database Tables: Missing tables - " . implode(', ', array_diff($tables, $existing_tables)) . "\n";
                echo "🔧 Run the installer to create tables: http://localhost/rms/install/database_installer.php\n";
            }
        }
        
        $connection->close();
        
    } else {
        echo "✗ Database config file not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database check failed: " . $e->getMessage() . "\n";
}

// Check file permissions
echo "\n--- File Permissions Check ---\n";

$writable_dirs = [
    'application/logs',
    'application/cache',
    'uploads'
];

foreach ($writable_dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✓ Created directory: $dir\n";
    }
    
    if (is_writable($dir)) {
        echo "✓ Directory writable: $dir\n";
    } else {
        echo "✗ Directory not writable: $dir\n";
        echo "  Fix with: chmod 755 $dir\n";
    }
}

// Check web server
echo "\n--- Web Server Check ---\n";

if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "✓ Web Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
} else {
    echo "ℹ Running from command line\n";
}

// Final recommendations
echo "\n=== Next Steps ===\n";
echo "1. 🌐 Open your web browser\n";
echo "2. 📍 Navigate to: http://localhost/rms/\n";
echo "3. 🔧 If you see errors, run: http://localhost/rms/install/database_installer.php\n";
echo "4. 📊 Explore the modern dashboard\n";
echo "5. ➕ Start adding your properties, customers, and staff\n";

echo "\n=== Troubleshooting ===\n";
echo "• Database errors: Run the installer or check MySQL service\n";
echo "• Permission errors: Check file permissions on logs/cache directories\n";
echo "• CSS/JS not loading: Verify assets folder is accessible\n";
echo "• 404 errors: Check .htaccess file and mod_rewrite\n";

echo "\n=== Documentation ===\n";
echo "• Installation Guide: INSTALLATION.md\n";
echo "• Testing Framework: tests/README.md\n";
echo "• API Documentation: Available in the application\n";

echo "\n🎉 RMS is ready to use! Happy property management!\n";
?>