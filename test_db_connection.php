<?php
// Standalone database connection test
// Place this file in your root directory and access it directly

echo "<h1>Database Connection Test</h1>";

// Database configuration
$hostname = 'sql12.freesqldatabase.com';
$username = 'sql12795673';
$password = 'SwIlRfzVuU';
$database = 'sql12795673';

echo "<h2>Configuration:</h2>";
echo "<p><strong>Host:</strong> $hostname</p>";
echo "<p><strong>Username:</strong> $username</p>";
echo "<p><strong>Database:</strong> $database</p>";

// Test connection
echo "<h2>Connection Test:</h2>";
try {
    $mysqli = new mysqli($hostname, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>✗ Connection failed: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✓ Connection successful!</p>";
        echo "<p><strong>Server info:</strong> " . $mysqli->server_info . "</p>";
        echo "<p><strong>Host info:</strong> " . $mysqli->host_info . "</p>";
        
        // Test if we can query the database
        $result = $mysqli->query("SELECT 1 as test");
        if ($result) {
            echo "<p style='color: green;'>✓ Query test successful</p>";
            $row = $result->fetch_assoc();
            echo "<p><strong>Test query result:</strong> " . $row['test'] . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Query test failed: " . $mysqli->error . "</p>";
        }
        
        // Check if customers table exists
        $result = $mysqli->query("SHOW TABLES LIKE 'customers'");
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✓ Table 'customers' exists</p>";
            
            // Show table structure
            $result = $mysqli->query("DESCRIBE customers");
            if ($result) {
                echo "<h3>Table Structure:</h3>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Field'] . "</td>";
                    echo "<td>" . $row['Type'] . "</td>";
                    echo "<td>" . $row['Null'] . "</td>";
                    echo "<td>" . $row['Key'] . "</td>";
                    echo "<td>" . $row['Default'] . "</td>";
                    echo "<td>" . $row['Extra'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Count records
            $result = $mysqli->query("SELECT COUNT(*) as count FROM customers");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p><strong>Total customers:</strong> " . $row['count'] . "</p>";
            }
            
        } else {
            echo "<p style='color: orange;'>⚠ Table 'customers' does not exist</p>";
            
            // Try to create the table
            echo "<h3>Creating customers table...</h3>";
            $sql = "CREATE TABLE customers (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if ($mysqli->query($sql)) {
                echo "<p style='color: green;'>✓ Table created successfully</p>";
            } else {
                echo "<p style='color: red;'>✗ Table creation failed: " . $mysqli->error . "</p>";
            }
        }
        
        // Test insert
        echo "<h3>Testing Insert:</h3>";
        $sql = "INSERT INTO customers (plot_buyer_name, father_name, district, phone_number_1) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        
        if ($stmt) {
            $name = "Test Customer " . date('Y-m-d H:i:s');
            $father = "Test Father";
            $district = "Test District";
            $phone = "9876543210";
            
            $stmt->bind_param("ssss", $name, $father, $district, $phone);
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✓ Insert test successful (ID: " . $stmt->insert_id . ")</p>";
            } else {
                echo "<p style='color: red;'>✗ Insert test failed: " . $stmt->error . "</p>";
            }
            
            $stmt->close();
        } else {
            echo "<p style='color: red;'>✗ Prepare statement failed: " . $mysqli->error . "</p>";
        }
        
        $mysqli->close();
        
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Test completed at:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><a href='application/views/db_test.php'>Go to Database Test Page</a></p>";
?>
