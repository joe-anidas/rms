-- Manual SQL script to create customers table
-- Run this directly in your MySQL database

-- Drop table if it exists (optional - remove this line if you want to keep existing data)
-- DROP TABLE IF EXISTS customers;

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO customers (plot_buyer_name, father_name, district, pincode, taluk_name, village_town_name, street_address, total_plot_bought, phone_number_1, phone_number_2, id_proof, aadhar_number) VALUES
('Test Customer 1', 'Test Father 1', 'Bangalore Urban', '560001', 'Bangalore South', 'Indiranagar', '123 Test Street', '2 acres', '9876543210', '9876543211', 'Aadhar', '123456789012'),
('Test Customer 2', 'Test Father 2', 'Mysore', '570001', 'Mysore', 'Mysore City', '456 Test Road', '1.5 acres', '9876543212', '9876543213', 'PAN', '987654321098');

-- Verify table creation
SELECT 'Table created successfully' as status;
SELECT COUNT(*) as total_customers FROM customers;
