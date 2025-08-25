-- Comprehensive SQL script to create all necessary tables for RMS
-- Run this in your MySQL database if migrations don't work
-- Make sure to run these in order due to foreign key dependencies

USE rms;

-- 1. Create customers table first (no dependencies)
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plot_buyer_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `taluk_name` varchar(100) DEFAULT NULL,
  `village_town_name` varchar(100) DEFAULT NULL,
  `street_address` text,
  `total_plot_bought` varchar(50) DEFAULT NULL,
  `phone_number_1` varchar(20) DEFAULT NULL,
  `phone_number_2` varchar(20) DEFAULT NULL,
  `id_proof` varchar(50) DEFAULT NULL,
  `aadhar_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create staff table (no dependencies)
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `alternate_contact` varchar(20) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `permanent_address` text,
  `current_address` text,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relation` varchar(100) DEFAULT NULL,
  `id_proof_type` varchar(50) DEFAULT NULL,
  `id_proof_number` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('Active','Inactive','Terminated') DEFAULT 'Active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create properties table (depends on staff table)
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_type` enum('garden','plot','house','flat') NOT NULL,
  `garden_name` varchar(255) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `taluk_name` varchar(100) DEFAULT NULL,
  `village_town_name` varchar(100) DEFAULT NULL,
  `size_sqft` decimal(10,2) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `status` enum('unsold','booked','sold') DEFAULT 'unsold',
  `description` text,
  `assigned_staff_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assigned_staff_id` (`assigned_staff_id`),
  KEY `status` (`status`),
  KEY `property_type` (`property_type`),
  CONSTRAINT `fk_properties_staff` FOREIGN KEY (`assigned_staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create registrations table (depends on customers and properties)
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `registration_date` date NOT NULL,
  `status` enum('pending','active','completed','cancelled') DEFAULT 'pending',
  `amount_paid` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `payment_terms` text,
  `notes` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `property_id` (`property_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_registrations_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_registrations_properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create transactions table (depends on customers and properties)
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `transaction_type` enum('payment','refund','adjustment') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `description` text,
  `status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `property_id` (`property_id`),
  KEY `transaction_type` (`transaction_type`),
  KEY `status` (`status`),
  CONSTRAINT `fk_transactions_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Create migrations table to track migration status
CREATE TABLE IF NOT EXISTS `migrations` (
  `version` bigint(20) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert migration version to indicate tables are created
INSERT INTO `migrations` (`version`) VALUES (20250824000007) ON DUPLICATE KEY UPDATE `version` = VALUES(`version`);

-- Insert sample data for testing
INSERT INTO `staff` (`employee_name`, `father_name`, `designation`, `department`, `status`) VALUES
('John Doe', 'Father Doe', 'Manager', 'Sales', 'Active'),
('Jane Smith', 'Father Smith', 'Agent', 'Sales', 'Active');

INSERT INTO `properties` (`property_type`, `garden_name`, `district`, `taluk_name`, `village_town_name`, `size_sqft`, `price`, `status`, `description`) VALUES
('garden', 'Sample Garden 1', 'Sample District', 'Sample Taluk', 'Sample Village', 1000.00, 50000.00, 'unsold', 'A beautiful garden property'),
('plot', 'Sample Plot 1', 'Sample District', 'Sample Taluk', 'Sample Village', 500.00, 25000.00, 'unsold', 'A well-located plot'),
('house', 'Sample House 1', 'Sample District', 'Sample Taluk', 'Sample Village', 1500.00, 150000.00, 'unsold', 'A comfortable family house');

INSERT INTO `customers` (`plot_buyer_name`, `father_name`, `district`, `phone_number_1`) VALUES
('Sample Customer 1', 'Sample Father 1', 'Sample District', '9876543210'),
('Sample Customer 2', 'Sample Father 2', 'Sample District', '9876543211');

-- Show all created tables
SHOW TABLES;

-- Show sample data
SELECT 'Staff' as table_name, COUNT(*) as count FROM staff
UNION ALL
SELECT 'Properties' as table_name, COUNT(*) as count FROM properties
UNION ALL
SELECT 'Customers' as table_name, COUNT(*) as count FROM customers;
