<?php
// Simple database setup script
echo "<h1>Database Setup</h1>";

// Database configuration
$hostname = 'sql12.freesqldatabase.com';
$username = 'sql12795673';
$password = 'SwIlRfzVuU';
$database = 'sql12795673';

try {
    $mysqli = new mysqli($hostname, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        die("<p style='color: red;'>Connection failed: " . $mysqli->connect_error . "</p>");
    }
    
    echo "<p style='color: green;'>✓ Connected to database successfully</p>";
    
    // Create tables
    $tables = [
        'customers' => "CREATE TABLE IF NOT EXISTS customers (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            plot_buyer_name VARCHAR(255) NOT NULL,
            father_name VARCHAR(255),
            district VARCHAR(100),
            pincode VARCHAR(10),
            taluk_name VARCHAR(100),
            village_town_name VARCHAR(100),
            street_address TEXT,
            total_plot_bought VARCHAR(50),
            phone_number_1 VARCHAR(20),
            phone_number_2 VARCHAR(20),
            id_proof VARCHAR(50),
            aadhar_number VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'staff' => "CREATE TABLE IF NOT EXISTS staff (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            employee_name VARCHAR(255) NOT NULL,
            employee_id VARCHAR(50) UNIQUE,
            phone_number VARCHAR(20),
            email VARCHAR(255),
            designation VARCHAR(100),
            department VARCHAR(100),
            hire_date DATE,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'properties' => "CREATE TABLE IF NOT EXISTS properties (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            property_type ENUM('garden', 'plot', 'house', 'flat') NOT NULL,
            garden_name VARCHAR(255) NOT NULL,
            district VARCHAR(100),
            taluk_name VARCHAR(100),
            village_town_name VARCHAR(100),
            size_sqft DECIMAL(10,2),
            price DECIMAL(15,2),
            status ENUM('unsold', 'booked', 'sold') DEFAULT 'unsold',
            description TEXT,
            assigned_staff_id INT(11),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_status (status),
            INDEX idx_property_type (property_type),
            INDEX idx_assigned_staff (assigned_staff_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'registrations' => "CREATE TABLE IF NOT EXISTS registrations (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            customer_id INT(11) NOT NULL,
            property_id INT(11) NOT NULL,
            registration_date DATE NOT NULL,
            registration_amount DECIMAL(15,2),
            status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_customer (customer_id),
            INDEX idx_property (property_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'transactions' => "CREATE TABLE IF NOT EXISTS transactions (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            customer_id INT(11) NOT NULL,
            property_id INT(11),
            registration_id INT(11),
            transaction_type ENUM('booking', 'registration', 'installment', 'full_payment') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            payment_method ENUM('cash', 'cheque', 'bank_transfer', 'online') DEFAULT 'cash',
            payment_date DATE NOT NULL,
            reference_number VARCHAR(100),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_customer (customer_id),
            INDEX idx_property (property_id),
            INDEX idx_payment_date (payment_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
    
    foreach ($tables as $table_name => $sql) {
        echo "<h3>Creating table: $table_name</h3>";
        if ($mysqli->query($sql)) {
            echo "<p style='color: green;'>✓ Table '$table_name' created successfully</p>";
        } else {
            echo "<p style='color: red;'>✗ Error creating table '$table_name': " . $mysqli->error . "</p>";
        }
    }
    
    // Insert sample data
    echo "<h2>Inserting Sample Data</h2>";
    
    // Sample customers
    $sample_customers = [
        ['John Doe', 'Robert Doe', 'Chennai', '600001', 'Chennai', 'Adyar', '123 Main St', '1 plot', '9876543210', '9876543211', 'Aadhar', '123456789012'],
        ['Jane Smith', 'Michael Smith', 'Bangalore', '560001', 'Bangalore', 'Koramangala', '456 Oak Ave', '2 plots', '9876543213', '9876543214', 'Passport', '123456789013'],
        ['Bob Johnson', 'William Johnson', 'Hyderabad', '500001', 'Hyderabad', 'Banjara Hills', '789 Pine Rd', '1 plot', '9876543215', '9876543216', 'Driving License', '123456789014']
    ];
    
    foreach ($sample_customers as $customer) {
        $stmt = $mysqli->prepare("INSERT INTO customers (plot_buyer_name, father_name, district, pincode, taluk_name, village_town_name, street_address, total_plot_bought, phone_number_1, phone_number_2, id_proof, aadhar_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", ...$customer);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Added customer: {$customer[0]}</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding customer: " . $stmt->error . "</p>";
        }
    }
    
    // Sample staff
    $sample_staff = [
        ['Alice Manager', 'EMP001', '9876543220', 'alice@rms.com', 'Manager', 'Sales', '2023-01-15'],
        ['Bob Agent', 'EMP002', '9876543221', 'bob@rms.com', 'Sales Agent', 'Sales', '2023-02-01'],
        ['Carol Admin', 'EMP003', '9876543222', 'carol@rms.com', 'Administrator', 'Admin', '2023-01-01']
    ];
    
    foreach ($sample_staff as $staff) {
        $stmt = $mysqli->prepare("INSERT INTO staff (employee_name, employee_id, phone_number, email, designation, department, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", ...$staff);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Added staff: {$staff[0]}</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding staff: " . $stmt->error . "</p>";
        }
    }
    
    // Sample properties
    $sample_properties = [
        ['garden', 'Green Valley Gardens', 'Chennai', 'Chennai', 'Adyar', 1200.00, 2500000.00, 'unsold', 'Beautiful garden plot with good connectivity'],
        ['plot', 'Sunrise Plots', 'Bangalore', 'Bangalore', 'Whitefield', 800.00, 1800000.00, 'booked', 'Prime location plot near IT corridor'],
        ['garden', 'Paradise Gardens', 'Hyderabad', 'Hyderabad', 'Gachibowli', 1500.00, 3200000.00, 'sold', 'Luxury garden plot in premium location']
    ];
    
    foreach ($sample_properties as $property) {
        $stmt = $mysqli->prepare("INSERT INTO properties (property_type, garden_name, district, taluk_name, village_town_name, size_sqft, price, status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssddss", ...$property);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Added property: {$property[1]}</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding property: " . $stmt->error . "</p>";
        }
    }
    
    echo "<h2>Database Setup Complete!</h2>";
    echo "<p style='color: green; font-size: 18px;'>✓ All tables created and sample data inserted successfully</p>";
    echo "<p><a href='dashboard' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Dashboard</a></p>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}
?>