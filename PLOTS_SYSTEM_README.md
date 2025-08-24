# Plots Management System

This document describes the comprehensive plots management system that has been implemented for the RMS (Real Estate Management System).

## Overview

The plots management system allows users to:
- Register new plots with comprehensive details
- Book plots with customer information
- Mark plots as sold
- View all plots in a unified overview
- Manage garden details and plot information
- Track plot statuses (unsold, booked, sold, registered)

## System Components

### 1. Database Tables

#### Gardens Table
- Stores garden/nagar information
- Includes location details, infrastructure information, and financial data
- Supports multiple gardens per system

#### Plots Table
- Stores individual plot information
- Links to gardens via foreign key
- Tracks plot status, customer details, and transaction history
- Supports comprehensive plot details including boundaries and documents

### 2. Models

#### Garden_model.php
- `create_garden_table()` - Creates gardens table
- `create_plots_table()` - Creates plots table with enhanced structure
- `submit_registered_plot()` - Submits new plot registration
- `submit_plot_booking()` - Books a plot with customer details
- `submit_plot_sale()` - Marks a plot as sold
- `get_plots_overview()` - Gets all plots with garden information
- `get_plot_statistics()` - Gets comprehensive plot statistics

### 3. Controllers

#### Welcome.php (Enhanced)
- `submit_registered_plot()` - Handles plot registration form submission
- `submit_plot_booking()` - Handles plot booking form submission
- `submit_plot_sale()` - Handles plot sale form submission
- `plots_overview()` - Displays unified plots overview
- `garden_details()` - Shows garden details and associated plots

### 4. Views

#### registered_plot.php
- Enhanced form with proper validation and submission
- Auto-calculates plot value based on extension and rate
- Includes customer details, payment information, and document uploads
- Submits data to database via AJAX

#### plots_overview.php
- Unified view of all plots (sold, unsold, booked, registered)
- Statistics dashboard with counts and values
- Advanced filtering by status, garden, and search terms
- Action buttons for plot management

#### garden_details.php
- Comprehensive garden information display
- Shows garden infrastructure and financial details
- Lists all plots within the garden
- Navigation to other system components

### 5. Routes

New routes have been added for the plots system:
```
plots/submit-registered - Submit plot registration
plots/submit-booking - Submit plot booking
plots/submit-sale - Submit plot sale
plots/overview - View all plots
garden/details - View garden details
garden/details/{id} - View specific garden details
```

## Features

### Plot Registration
- Complete plot details including boundaries
- Customer information capture
- Document upload support
- Payment method selection
- Auto-calculation of plot values

### Plot Status Management
- **Unsold**: Available for sale/booking
- **Booked**: Reserved by customer with partial payment
- **Sold**: Completed sale with full payment
- **Registered**: Newly registered plot

### Customer Management
- Customer name, phone, and address
- ID proof information
- Father's name and contact details
- Multiple phone number support

### Financial Tracking
- Plot values and rates per square foot
- Booking amounts and sale amounts
- Payment method tracking
- Total value calculations

### Reporting and Analytics
- Total plot counts by status
- Total plot values
- Sales value tracking
- Garden-wise plot distribution

## Usage

### 1. Register a New Plot
1. Navigate to `plots/registered`
2. Fill in plot details (number, extension, boundaries)
3. Add customer information
4. Select payment method
5. Upload documents (optional)
6. Submit the form

### 2. View All Plots
1. Navigate to `plots/overview`
2. Use filters to find specific plots
3. View plot details and take actions
4. Export data as needed

### 3. Manage Garden Details
1. Navigate to `garden/details`
2. View comprehensive garden information
3. See all plots within the garden
4. Add new plots or edit existing ones

### 4. Book or Sell Plots
1. From plots overview, use action buttons
2. Fill in customer and transaction details
3. Submit to update plot status

## Technical Implementation

### Database Schema
The plots table includes:
- Basic plot information (number, extension, boundaries)
- Customer details (name, phone, address, ID proof)
- Transaction details (booking, sale, payment)
- Document references
- Status tracking and timestamps

### Form Handling
- AJAX-based form submission
- Client-side validation
- Server-side data processing
- Error handling and user feedback

### Security Features
- Input sanitization
- SQL injection prevention
- File upload validation
- User authentication (framework-level)

## Testing

A test script (`test_plots_system.php`) is provided to verify:
- Database connectivity
- Table creation
- Data insertion
- Data retrieval
- System functionality

Run this script to ensure the system is working correctly.

## Future Enhancements

Potential improvements include:
- Advanced reporting and analytics
- Document management system
- Payment gateway integration
- Customer relationship management
- Mobile application support
- Multi-language support

## Support

For technical support or questions about the plots management system, please refer to the system documentation or contact the development team.

## Version History

- **v1.0** - Initial implementation with basic plot management
- **v1.1** - Enhanced database structure and form handling
- **v1.2** - Unified plots overview and garden details
- **v1.3** - Comprehensive customer and transaction management
