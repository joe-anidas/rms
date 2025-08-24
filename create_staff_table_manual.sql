-- Manual SQL script to create staff table
-- Run this directly in your MySQL database

-- Drop table if it exists (optional - remove this line if you want to keep existing data)
-- DROP TABLE IF EXISTS staff;

-- Create staff table
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO staff (employee_name, father_name, date_of_birth, gender, marital_status, blood_group, contact_number, alternate_contact, email_address, permanent_address, current_address, emergency_contact_name, emergency_contact_phone, emergency_contact_relation, id_proof_type, id_proof_number, designation, department, joining_date, salary, bank_name, bank_account_number, ifsc_code, pan_number, aadhar_number) VALUES
('John Doe', 'Robert Doe', '1990-05-15', 'Male', 'Single', 'A+', '9876543210', '9876543211', 'john.doe@example.com', '123 Main Street, City, State - 123456', '456 Work Street, Work City, State - 654321', 'Robert Doe', '9876543212', 'Father', 'Aadhar', '123456789012', 'Software Developer', 'IT Department', '2023-01-15', 50000.00, 'Sample Bank', '1234567890', 'SMPL0001234', 'ABCDE1234F', '123456789012'),
('Jane Smith', 'Michael Smith', '1988-12-20', 'Female', 'Married', 'B+', '9876543213', '9876543214', 'jane.smith@example.com', '789 Home Street, Home City, State - 789012', '321 Office Street, Office City, State - 321098', 'Michael Smith', '9876543215', 'Father', 'PAN', 'FGHIJ5678K', 'Project Manager', 'Management', '2022-08-10', 75000.00, 'Business Bank', '0987654321', 'BUSN0005678', 'FGHIJ5678K', '987654321098');

-- Verify table creation
SELECT 'Staff table created successfully' as status;
SELECT COUNT(*) as total_staff FROM staff;
