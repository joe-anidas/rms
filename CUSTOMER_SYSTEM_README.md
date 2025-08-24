# Customer Management System

This system allows you to store and manage customer details for plot buyers. It includes a form to collect customer information and stores it in a MySQL database.

## Features

- **Customer Details Form**: Collect comprehensive customer information including:
  - Plot buyer name (required)
  - Father's name
  - District selection
  - Pincode
  - Taluk name
  - Village/Town name
  - Street address
  - Total plot bought
  - Phone numbers (primary and secondary)
  - ID proof type
  - Aadhar number

- **Sample Data**: Pre-filled form fields with sample values for development
- **Database Storage**: Automatically creates and manages the customers table
- **Form Validation**: Client-side and server-side validation
- **AJAX Submission**: Smooth form submission without page reload
- **Success/Error Messages**: User-friendly feedback messages
- **Customer List View**: Display all customers in a table format
- **Customer Details Modal**: View detailed customer information
- **Data Export**: Export customer data to CSV format
- **Navigation Integration**: Easy access through sidebar navigation

## Database Structure

The system creates a `customers` table with the following structure:

```sql
CREATE TABLE customers (
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
);
```

## Installation & Setup

### 1. Database Setup

You can create the table manually using the SQL script:

```bash
# Option 1: Run the SQL script directly in your database
mysql -u your_username -p your_database < tasks/table/create_customers_table.sql

# Option 2: The table will be created automatically when you first submit a customer
```

### 2. File Structure

The system consists of these key files:

- `application/models/Customer_model.php` - Database operations
- `application/controllers/Welcome.php` - Controller with customer methods
- `application/views/customer_details.php` - Main customer form
- `application/views/test_customer.php` - Test page for development
- `application/config/routes.php` - URL routing configuration

### 3. Routes

The following routes are available:

- `/customer_details` - Main customer details form
- `/submit-customer` - API endpoint for form submission
- `/customer-list` - View all customers
- `/get-customer/{id}` - Get specific customer details (API)
- `/test-customer` - Test page for development

## Usage

### Adding a New Customer

1. Navigate to `/customer_details` in your browser
2. The form comes pre-filled with sample data for development
3. Modify the values as needed or keep the sample data
4. Click "Submit Customer Details"
5. The system will validate and store the data
6. You'll see a success message and be redirected to the customer list
7. Use the "View All Customers" button to see all entries

### Form Fields

- **Plot Buyer Name**: Required field for the customer's name
- **Father Name**: Optional field for father's name
- **District**: Dropdown with common Karnataka districts
- **Pincode**: 6-digit postal code
- **Taluk Name**: Administrative division
- **Village/Town Name**: Local area name
- **Street Address**: Detailed address
- **Total Plot Bought**: Plot size (e.g., "2 acres")
- **Phone Numbers**: Contact information
- **ID Proof**: Type of identification document
- **Aadhar Number**: 12-digit Aadhar ID

## Testing

### Customer List View

Access the customer list at `/customer-list` to:

- View all customers in a table format
- See customer details in a modal popup
- Export customer data to CSV
- Navigate between add and view functions

### Test Page

Use the test page at `/test-customer` to verify functionality:

- Simplified form for quick testing
- Same submission logic as main form
- Useful for development and debugging

### Sample Data

The SQL script includes sample customer data for testing:

```sql
INSERT INTO customers (plot_buyer_name, father_name, district, pincode, taluk_name, village_town_name, street_address, total_plot_bought, phone_number_1, phone_number_2, id_proof, aadhar_number) VALUES
('John Doe', 'Robert Doe', 'Bangalore Urban', '560001', 'Bangalore South', 'Bangalore', '123 Main Street, Indiranagar', '2 acres', '9876543210', '9876543211', 'Aadhar', '123456789012'),
('Jane Smith', 'Michael Smith', 'Mysore', '570001', 'Mysore', 'Mysore', '456 Park Road, Vijayanagar', '1.5 acres', '9876543212', '9876543213', 'PAN', '987654321098');
```

## Technical Details

### AJAX Submission

The form uses modern JavaScript (Fetch API) for smooth submission:

- Prevents page reload
- Shows loading states
- Handles success/error responses
- Provides user feedback

### Form Validation

- **Client-side**: HTML5 validation attributes
- **Server-side**: PHP validation in the controller
- **Database**: MySQL constraints and data types

### Security Features

- SQL injection prevention through CodeIgniter's query builder
- Input sanitization
- CSRF protection (if enabled in CodeIgniter)

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `application/config/database.php`
   - Ensure MySQL service is running

2. **Table Creation Failed**
   - Verify database permissions
   - Check MySQL version compatibility

3. **Form Submission Error**
   - Check browser console for JavaScript errors
   - Verify server logs for PHP errors
   - Ensure all required fields are filled

### Debug Mode

Enable CodeIgniter debug mode in `application/config/config.php`:

```php
$config['log_threshold'] = 4;
```

## Future Enhancements

Potential improvements for the system:

- Customer search and filtering
- Customer data export (CSV/PDF)
- Bulk customer import
- Customer data editing
- Customer data deletion
- Advanced reporting
- User authentication and authorization
- Audit logging

## Support

For technical support or questions:

1. Check the CodeIgniter documentation
2. Review the error logs
3. Verify database connectivity
4. Test with the simplified test page

---

**Note**: This system is built on CodeIgniter 3 framework and requires PHP 7.0+ and MySQL 5.6+.
