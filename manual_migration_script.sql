-- Manual Migration Script for RMS Enhancement
-- Run this script directly in MySQL if you prefer manual migration
-- This creates all the enhanced database schema

-- Enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Create staff table (if not exists)
CREATE TABLE IF NOT EXISTS staff (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(255) NOT NULL,
    father_name VARCHAR(255),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed'),
    blood_group VARCHAR(10),
    contact_number VARCHAR(20),
    alternate_contact VARCHAR(20),
    email_address VARCHAR(255),
    permanent_address TEXT,
    current_address TEXT,
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(20),
    emergency_contact_relation VARCHAR(100),
    id_proof_type VARCHAR(50),
    id_proof_number VARCHAR(100),
    designation VARCHAR(100),
    department VARCHAR(100),
    joining_date DATE,
    salary DECIMAL(10,2),
    bank_name VARCHAR(100),
    bank_account_number VARCHAR(50),
    ifsc_code VARCHAR(20),
    pan_number VARCHAR(20),
    aadhar_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_employee_name (employee_name),
    INDEX idx_designation (designation),
    INDEX idx_department (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create properties table
CREATE TABLE IF NOT EXISTS properties (
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
    INDEX idx_assigned_staff_id (assigned_staff_id),
    INDEX idx_status (status),
    INDEX idx_property_type (property_type),
    FOREIGN KEY (assigned_staff_id) REFERENCES staff(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enhance customers table (add new columns if they don't exist)
ALTER TABLE customers 
ADD COLUMN IF NOT EXISTS email_address VARCHAR(255) AFTER phone_number_2,
ADD COLUMN IF NOT EXISTS alternate_address TEXT AFTER street_address,
ADD COLUMN IF NOT EXISTS occupation VARCHAR(100) AFTER email_address,
ADD COLUMN IF NOT EXISTS annual_income DECIMAL(15,2) AFTER occupation,
ADD COLUMN IF NOT EXISTS reference_source VARCHAR(100) AFTER annual_income,
ADD COLUMN IF NOT EXISTS emergency_contact_name VARCHAR(255) AFTER reference_source,
ADD COLUMN IF NOT EXISTS emergency_contact_phone VARCHAR(20) AFTER emergency_contact_name,
ADD COLUMN IF NOT EXISTS emergency_contact_relation VARCHAR(100) AFTER emergency_contact_phone,
ADD COLUMN IF NOT EXISTS pan_number VARCHAR(20) AFTER aadhar_number,
ADD COLUMN IF NOT EXISTS bank_name VARCHAR(100) AFTER pan_number,
ADD COLUMN IF NOT EXISTS bank_account_number VARCHAR(50) AFTER bank_name,
ADD COLUMN IF NOT EXISTS ifsc_code VARCHAR(20) AFTER bank_account_number,
ADD COLUMN IF NOT EXISTS customer_status ENUM('active', 'inactive', 'blacklisted') DEFAULT 'active' AFTER ifsc_code,
ADD COLUMN IF NOT EXISTS notes TEXT AFTER customer_status;

-- Add indexes to customers table
ALTER TABLE customers 
ADD INDEX IF NOT EXISTS idx_email (email_address),
ADD INDEX IF NOT EXISTS idx_customer_status (customer_status),
ADD INDEX IF NOT EXISTS idx_pan_number (pan_number);

-- Create registrations table
CREATE TABLE IF NOT EXISTS registrations (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    property_id INT(11) NOT NULL,
    customer_id INT(11) NOT NULL,
    registration_date DATE NOT NULL,
    agreement_path VARCHAR(500),
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    total_amount DECIMAL(15,2),
    paid_amount DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_registration_number (registration_number),
    INDEX idx_property_id (property_id),
    INDEX idx_customer_id (customer_id),
    INDEX idx_status (status),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    registration_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_type ENUM('advance', 'installment', 'full_payment') NOT NULL,
    payment_method ENUM('cash', 'cheque', 'bank_transfer', 'online') NOT NULL,
    payment_date DATE NOT NULL,
    receipt_number VARCHAR(50) UNIQUE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_registration_id (registration_id),
    INDEX idx_payment_date (payment_date),
    INDEX idx_payment_type (payment_type),
    UNIQUE KEY uk_receipt_number (receipt_number),
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create property_assignments table
CREATE TABLE IF NOT EXISTS property_assignments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    property_id INT(11) NOT NULL,
    staff_id INT(11) NOT NULL,
    assignment_type ENUM('sales', 'maintenance', 'customer_service') NOT NULL,
    assigned_date DATE NOT NULL,
    end_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_property_id (property_id),
    INDEX idx_staff_id (staff_id),
    INDEX idx_assignment_type (assignment_type),
    INDEX idx_is_active (is_active),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create audit_logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(100) NOT NULL,
    record_id INT(11) NOT NULL,
    action ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    old_values TEXT,
    new_values TEXT,
    user_id INT(11),
    user_ip VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_table_name (table_name),
    INDEX idx_record_id (record_id),
    INDEX idx_action (action),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create migrations table for tracking
CREATE TABLE IF NOT EXISTS migrations (
    version BIGINT(20) NOT NULL,
    PRIMARY KEY (version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert migration version
INSERT IGNORE INTO migrations (version) VALUES (20250824000007);

-- Verify table creation
SELECT 'Database schema created successfully' as status;

-- Show table counts
SELECT 
    'customers' as table_name, COUNT(*) as record_count FROM customers
UNION ALL
SELECT 
    'staff' as table_name, COUNT(*) as record_count FROM staff
UNION ALL
SELECT 
    'properties' as table_name, COUNT(*) as record_count FROM properties
UNION ALL
SELECT 
    'registrations' as table_name, COUNT(*) as record_count FROM registrations
UNION ALL
SELECT 
    'transactions' as table_name, COUNT(*) as record_count FROM transactions
UNION ALL
SELECT 
    'property_assignments' as table_name, COUNT(*) as record_count FROM property_assignments
UNION ALL
SELECT 
    'audit_logs' as table_name, COUNT(*) as record_count FROM audit_logs;

-- Show foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN ('properties', 'registrations', 'transactions', 'property_assignments')
ORDER BY 
    TABLE_NAME, COLUMN_NAME;