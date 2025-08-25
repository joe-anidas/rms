-- Manual SQL script to create the properties table
-- Run this in your MySQL database if migrations don't work

USE rms;

-- Create properties table
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
  KEY `property_type` (`property_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some sample data
INSERT INTO `properties` (`property_type`, `garden_name`, `district`, `taluk_name`, `village_town_name`, `size_sqft`, `price`, `status`, `description`) VALUES
('garden', 'Sample Garden 1', 'Sample District', 'Sample Taluk', 'Sample Village', 1000.00, 50000.00, 'unsold', 'A beautiful garden property'),
('plot', 'Sample Plot 1', 'Sample District', 'Sample Taluk', 'Sample Village', 500.00, 25000.00, 'unsold', 'A well-located plot'),
('house', 'Sample House 1', 'Sample District', 'Sample Taluk', 'Sample Village', 1500.00, 150000.00, 'unsold', 'A comfortable family house');

-- Show the created table
DESCRIBE properties;

-- Show sample data
SELECT * FROM properties;
