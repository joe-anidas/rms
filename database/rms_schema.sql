-- RMS Database Schema
-- Real Estate Management System Database Structure

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `rms`
CREATE DATABASE IF NOT EXISTS `rms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rms`;

-- --------------------------------------------------------

-- Table structure for table `properties`
CREATE TABLE `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_type` enum('garden','plot','house','flat') NOT NULL DEFAULT 'plot',
  `garden_name` varchar(255) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `taluk_name` varchar(100) DEFAULT NULL,
  `village_town_name` varchar(100) DEFAULT NULL,
  `size_sqft` decimal(10,2) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `status` enum('unsold','booked','sold','deleted') NOT NULL DEFAULT 'unsold',
  `description` text DEFAULT NULL,
  `assigned_staff_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_property_type` (`property_type`),
  KEY `idx_assigned_staff` (`assigned_staff_id`),
  KEY `idx_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `customers`
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plot_buyer_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `taluk_name` varchar(100) DEFAULT NULL,
  `village_town_name` varchar(100) DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `total_plot_bought` varchar(50) DEFAULT NULL,
  `phone_number_1` varchar(20) DEFAULT NULL,
  `phone_number_2` varchar(20) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `id_proof` varchar(50) DEFAULT NULL,
  `aadhar_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `annual_income` decimal(15,2) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `customer_status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_customer_name` (`plot_buyer_name`),
  KEY `idx_phone` (`phone_number_1`),
  KEY `idx_status` (`customer_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `staff`
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `alternate_contact` varchar(20) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relation` varchar(100) DEFAULT NULL,
  `id_proof_type` varchar(50) DEFAULT NULL,
  `id_proof_number` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `aadhar_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_employee_name` (`employee_name`),
  KEY `idx_designation` (`designation`),
  KEY `idx_department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `registrations`
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(50) NOT NULL UNIQUE,
  `property_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `registration_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `agreement_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration_number` (`registration_number`),
  KEY `idx_property_id` (`property_id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `transactions`
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_type` enum('advance','installment','full_payment','refund') NOT NULL,
  `payment_method` enum('cash','cheque','bank_transfer','online','other') NOT NULL,
  `payment_date` date NOT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `cheque_number` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_registration_id` (`registration_id`),
  KEY `idx_payment_date` (`payment_date`),
  KEY `idx_payment_type` (`payment_type`),
  FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `property_assignments`
CREATE TABLE `property_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `assignment_type` enum('sales','maintenance','customer_service') NOT NULL DEFAULT 'sales',
  `assigned_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_property_id` (`property_id`),
  KEY `idx_staff_id` (`staff_id`),
  KEY `idx_is_active` (`is_active`),
  FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `customer_assignments`
CREATE TABLE `customer_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `assignment_type` enum('sales','customer_service','support') NOT NULL DEFAULT 'customer_service',
  `assigned_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_staff_id` (`staff_id`),
  KEY `idx_is_active` (`is_active`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `payment_schedules`
CREATE TABLE `payment_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `installment_number` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `paid_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_registration_id` (`registration_id`),
  KEY `idx_due_date` (`due_date`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `audit_logs`
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE','STATUS_UPDATE','DOCUMENT_UPLOAD') NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_table_record` (`table_name`, `record_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Insert sample data for testing

-- Sample Properties
INSERT INTO `properties` (`property_type`, `garden_name`, `district`, `taluk_name`, `village_town_name`, `size_sqft`, `price`, `status`, `description`) VALUES
('garden', 'Green Valley Garden', 'Mumbai', 'Andheri', 'Lokhandwala', 1200.00, 850000.00, 'unsold', 'Beautiful garden property with modern amenities'),
('plot', 'Sunrise Plot', 'Pune', 'Baner', 'Baner Village', 800.00, 650000.00, 'unsold', 'Prime location plot ready for construction'),
('house', 'Dream Villa', 'Mumbai', 'Bandra', 'Bandra West', 2500.00, 2500000.00, 'unsold', 'Luxury villa with sea view'),
('flat', 'Sky Heights Apartment', 'Pune', 'Koregaon Park', 'Koregaon Park', 1100.00, 1200000.00, 'booked', '2BHK apartment in premium location'),
('garden', 'Rose Garden Estate', 'Nashik', 'Nashik Road', 'Pathardi', 1500.00, 750000.00, 'sold', 'Spacious garden with fruit trees');

-- Sample Customers
INSERT INTO `customers` (`plot_buyer_name`, `father_name`, `district`, `pincode`, `taluk_name`, `village_town_name`, `street_address`, `phone_number_1`, `phone_number_2`, `email_address`, `aadhar_number`, `pan_number`, `annual_income`, `occupation`, `customer_status`) VALUES
('Rajesh Kumar', 'Suresh Kumar', 'Mumbai', '400001', 'Andheri', 'Lokhandwala', '123 Main Street, Andheri West', '9876543210', '9876543211', 'rajesh.kumar@email.com', '123456789012', 'ABCDE1234F', 800000.00, 'Business', 'active'),
('Priya Sharma', 'Mohan Sharma', 'Pune', '411001', 'Baner', 'Baner Village', '456 Park Avenue, Baner', '9876543220', '9876543221', 'priya.sharma@email.com', '123456789013', 'ABCDE1235F', 1200000.00, 'Software Engineer', 'active'),
('Amit Patel', 'Kishore Patel', 'Mumbai', '400050', 'Bandra', 'Bandra West', '789 Sea View Road, Bandra', '9876543230', '9876543231', 'amit.patel@email.com', '123456789014', 'ABCDE1236F', 1500000.00, 'Doctor', 'active'),
('Sunita Joshi', 'Ramesh Joshi', 'Pune', '411036', 'Koregaon Park', 'Koregaon Park', '321 Garden Street, Koregaon Park', '9876543240', '9876543241', 'sunita.joshi@email.com', '123456789015', 'ABCDE1237F', 900000.00, 'Teacher', 'active'),
('Vikram Singh', 'Harpal Singh', 'Nashik', '422001', 'Nashik Road', 'Pathardi', '654 Hill View, Nashik Road', '9876543250', '9876543251', 'vikram.singh@email.com', '123456789016', 'ABCDE1238F', 700000.00, 'Farmer', 'active');

-- Sample Staff
INSERT INTO `staff` (`employee_name`, `designation`, `department`, `contact_number`, `email_address`, `joining_date`, `salary`) VALUES
('Arjun Mehta', 'Sales Manager', 'Sales', '9876543260', 'arjun.mehta@rms.com', '2023-01-15', 50000.00),
('Kavya Reddy', 'Sales Executive', 'Sales', '9876543270', 'kavya.reddy@rms.com', '2023-02-01', 35000.00),
('Rohit Gupta', 'Customer Service Executive', 'Customer Service', '9876543280', 'rohit.gupta@rms.com', '2023-03-01', 30000.00),
('Neha Agarwal', 'Property Manager', 'Operations', '9876543290', 'neha.agarwal@rms.com', '2023-01-20', 45000.00),
('Sanjay Yadav', 'Sales Executive', 'Sales', '9876543300', 'sanjay.yadav@rms.com', '2023-04-01', 35000.00);

-- Sample Registrations
INSERT INTO `registrations` (`registration_number`, `property_id`, `customer_id`, `registration_date`, `total_amount`, `paid_amount`, `status`) VALUES
('REG-202401-0001', 4, 4, '2024-01-15', 1200000.00, 240000.00, 'active'),
('REG-202401-0002', 5, 5, '2024-01-20', 750000.00, 750000.00, 'completed');

-- Sample Transactions
INSERT INTO `transactions` (`registration_id`, `amount`, `payment_type`, `payment_method`, `payment_date`, `receipt_number`, `notes`) VALUES
(1, 240000.00, 'advance', 'bank_transfer', '2024-01-15', 'RCP20240115001', 'Initial advance payment for Sky Heights Apartment'),
(2, 750000.00, 'full_payment', 'bank_transfer', '2024-01-20', 'RCP20240120001', 'Full payment for Rose Garden Estate');

-- Sample Property Assignments
INSERT INTO `property_assignments` (`property_id`, `staff_id`, `assignment_type`, `assigned_date`, `is_active`) VALUES
(1, 2, 'sales', '2024-01-10', 1),
(2, 5, 'sales', '2024-01-12', 1),
(3, 1, 'sales', '2024-01-14', 1),
(4, 2, 'sales', '2024-01-15', 1),
(5, 5, 'sales', '2024-01-18', 0);

-- Update foreign key references
ALTER TABLE `properties` ADD FOREIGN KEY (`assigned_staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL;

-- Create indexes for better performance
CREATE INDEX `idx_properties_search` ON `properties` (`garden_name`, `district`, `status`);
CREATE INDEX `idx_customers_search` ON `customers` (`plot_buyer_name`, `phone_number_1`, `email_address`);
CREATE INDEX `idx_staff_search` ON `staff` (`employee_name`, `designation`, `department`);
CREATE INDEX `idx_registrations_search` ON `registrations` (`registration_number`, `registration_date`, `status`);
CREATE INDEX `idx_transactions_search` ON `transactions` (`payment_date`, `payment_type`, `amount`);

COMMIT;