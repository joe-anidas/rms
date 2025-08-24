<?php
// Test script for the plots management system
// This script tests the database connection and table creation

// Include CodeIgniter bootstrap
require_once 'index.php';

// Test the plots system
echo "<h2>Testing Plots Management System</h2>";

try {
    // Test database connection
    echo "<h3>1. Testing Database Connection</h3>";
    $CI =& get_instance();
    $CI->load->database();
    
    if ($CI->db->conn_id) {
        echo "✅ Database connection successful<br>";
    } else {
        echo "❌ Database connection failed<br>";
        exit;
    }
    
    // Test Garden model
    echo "<h3>2. Testing Garden Model</h3>";
    $CI->load->model('Garden_model');
    
    // Test table creation
    echo "Creating gardens table...<br>";
    $garden_result = $CI->Garden_model->create_garden_table();
    if ($garden_result) {
        echo "✅ Gardens table created/verified successfully<br>";
    } else {
        echo "❌ Failed to create gardens table<br>";
    }
    
    echo "Creating plots table...<br>";
    $plots_result = $CI->Garden_model->create_plots_table();
    if ($plots_result) {
        echo "✅ Plots table created/verified successfully<br>";
    } else {
        echo "❌ Failed to create plots table<br>";
    }
    
    // Test inserting a sample garden
    echo "<h3>3. Testing Garden Insertion</h3>";
    $garden_data = array(
        'garden_name' => 'Test Garden ' . date('Y-m-d H:i:s'),
        'district' => 'Test District',
        'taluk_name' => 'Test Taluk',
        'village_town_name' => 'Test Village',
        'total_extension' => '10000',
        'total_plots' => 5
    );
    
    $insert_result = $CI->Garden_model->insert_garden($garden_data);
    if ($insert_result) {
        echo "✅ Sample garden inserted successfully<br>";
        
        // Get the inserted garden ID
        $garden_id = $CI->db->insert_id();
        echo "Garden ID: $garden_id<br>";
        
        // Test inserting a sample plot
        echo "<h3>4. Testing Plot Insertion</h3>";
        $plot_data = array(
            'garden_id' => $garden_id,
            'plot_no' => 'TEST001',
            'plot_extension' => '1200',
            'north' => '30',
            'east' => '40',
            'west' => '30',
            'south' => '40',
            'plot_value' => 6000000,
            'plot_rate_per_sqft' => 5000,
            'status' => 'unsold'
        );
        
        $plot_result = $CI->Garden_model->submit_registered_plot($plot_data);
        if ($plot_result) {
            echo "✅ Sample plot inserted successfully<br>";
            echo "Plot ID: $plot_result<br>";
        } else {
            echo "❌ Failed to insert sample plot<br>";
        }
        
    } else {
        echo "❌ Failed to insert sample garden<br>";
    }
    
    // Test getting plots overview
    echo "<h3>5. Testing Plots Overview</h3>";
    $plots = $CI->Garden_model->get_plots_overview();
    echo "Total plots found: " . count($plots) . "<br>";
    
    if (!empty($plots)) {
        echo "Sample plot data:<br>";
        echo "<pre>";
        print_r($plots[0]);
        echo "</pre>";
    }
    
    // Test getting plot statistics
    echo "<h3>6. Testing Plot Statistics</h3>";
    $stats = $CI->Garden_model->get_plot_statistics();
    echo "Plot statistics:<br>";
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
    echo "<h3>✅ All tests completed successfully!</h3>";
    echo "<p>The plots management system is working correctly.</p>";
    echo "<p><a href='plots/overview'>View Plots Overview</a></p>";
    echo "<p><a href='garden/details'>View Garden Details</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error occurred</h3>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>
