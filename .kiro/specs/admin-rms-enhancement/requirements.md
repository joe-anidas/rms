# Requirements Document

## Introduction

This document outlines the requirements for enhancing the existing Real Estate Management System (RMS) with comprehensive admin-based functionality. The system will provide complete property management capabilities including property profiles, customer management, staff management, transaction tracking, and a modern dashboard with data visualization. The enhancement will modernize the existing CodeIgniter application with improved database connectivity, modern CSS styling, and comprehensive reporting features.

## Requirements

### Requirement 1: Property/Garden Profile Management

**User Story:** As an admin, I want to manage property profiles comprehensively, so that I can maintain accurate records of all available properties with their current status and details.

#### Acceptance Criteria

1. WHEN an admin accesses the property management section THEN the system SHALL display a list of all properties with their current status
2. WHEN an admin clicks "Add Property" THEN the system SHALL provide a form to enter property details including type (garden/plot/house/flat), size, location, price, and description
3. WHEN an admin saves a new property THEN the system SHALL store the property with default status "Unsold" and generate a unique property ID
4. WHEN an admin edits an existing property THEN the system SHALL allow modification of all property details except the property ID
5. WHEN an admin changes property status THEN the system SHALL update the status to Sold, Unsold, or Booked with timestamp
6. WHEN an admin deletes a property THEN the system SHALL soft delete the property and maintain audit trail
7. IF a property has associated transactions or customers THEN the system SHALL prevent deletion and show warning message

### Requirement 2: Registration Details Management

**User Story:** As an admin, I want to record property sales and bookings with customer associations, so that I can maintain complete registration records for legal and business purposes.

#### Acceptance Criteria

1. WHEN an admin registers a sale or booking THEN the system SHALL link the selected customer to the selected property
2. WHEN a property is registered THEN the system SHALL automatically update the property status to "Sold" or "Booked"
3. WHEN registration is created THEN the system SHALL generate a unique registration number with date prefix
4. WHEN admin enters booking details THEN the system SHALL store booking date, registration number, and optional agreement copy path
5. IF a property is already sold or booked THEN the system SHALL prevent new registration and show error message
6. WHEN registration is completed THEN the system SHALL send confirmation with registration details
7. WHEN admin views registration history THEN the system SHALL display all registrations with customer and property details

### Requirement 3: Transaction Management System

**User Story:** As an admin, I want to track all financial transactions related to properties, so that I can monitor payments, generate invoices, and maintain accurate financial records.

#### Acceptance Criteria

1. WHEN an admin records a payment THEN the system SHALL store transaction details including amount, payment type (advance/installment/full), and payment method
2. WHEN a transaction is created THEN the system SHALL link it to the specific property and customer registration
3. WHEN admin views transaction history THEN the system SHALL display all payments with running balance calculations
4. WHEN generating payment reports THEN the system SHALL calculate total sales, pending payments, and revenue by date range
5. IF payment exceeds remaining balance THEN the system SHALL show warning and require confirmation
6. WHEN installment is recorded THEN the system SHALL update payment schedule and calculate next due date
7. WHEN full payment is completed THEN the system SHALL automatically mark the property as "Fully Paid"

### Requirement 4: Customer Management System

**User Story:** As an admin, I want to maintain comprehensive customer profiles and their property associations, so that I can provide personalized service and track customer relationships.

#### Acceptance Criteria

1. WHEN an admin adds a new customer THEN the system SHALL store complete profile including name, phone, email, address, and contact preferences
2. WHEN customer profile is created THEN the system SHALL generate unique customer ID and creation timestamp
3. WHEN admin views customer details THEN the system SHALL display all associated properties and transaction history
4. WHEN customer information is updated THEN the system SHALL maintain audit log of changes with timestamps
5. IF customer has active bookings or transactions THEN the system SHALL prevent deletion and show associated records
6. WHEN searching customers THEN the system SHALL provide search by name, phone, email, or property association
7. WHEN customer makes multiple property purchases THEN the system SHALL maintain separate records for each property association

### Requirement 5: Staff Management System

**User Story:** As an admin, I want to manage staff profiles and assign them to properties and customers, so that I can ensure proper accountability and customer service delivery.

#### Acceptance Criteria

1. WHEN an admin adds staff member THEN the system SHALL store staff details including name, role, contact information, and employment date
2. WHEN staff is assigned to property THEN the system SHALL create assignment record with date and responsibility type
3. WHEN staff is assigned to customer THEN the system SHALL link staff member as primary contact for customer relations
4. WHEN admin views staff performance THEN the system SHALL display assigned properties, customers, and transaction involvement
5. IF staff member is assigned to active properties or customers THEN the system SHALL prevent deletion and require reassignment
6. WHEN staff assignment changes THEN the system SHALL maintain history of all assignments with date ranges
7. WHEN generating staff reports THEN the system SHALL show workload distribution and performance metrics

### Requirement 6: Dashboard and Reporting System

**User Story:** As an admin, I want a comprehensive dashboard with visual reports and charts, so that I can quickly understand business performance and make informed decisions.

#### Acceptance Criteria

1. WHEN admin accesses dashboard THEN the system SHALL display key metrics including total properties by status, revenue collected vs pending, and customer/staff counts
2. WHEN viewing property analytics THEN the system SHALL show charts for sold/unsold/booked properties with trend analysis
3. WHEN analyzing financial data THEN the system SHALL display revenue charts by month, payment type distribution, and pending payment alerts
4. WHEN reviewing customer analytics THEN the system SHALL show customer acquisition trends, top customers by value, and geographic distribution
5. WHEN accessing staff analytics THEN the system SHALL display staff performance metrics, workload distribution, and assignment history
6. IF data is updated THEN the dashboard SHALL refresh automatically or show real-time updates
7. WHEN exporting reports THEN the system SHALL provide PDF and Excel export options with customizable date ranges

### Requirement 7: Database Integration and Modern UI

**User Story:** As an admin, I want a modern, responsive interface with reliable database connectivity, so that I can efficiently manage the system from any device with confidence in data integrity.

#### Acceptance Criteria

1. WHEN system starts THEN the database connection SHALL be established and verified with proper error handling
2. WHEN admin accesses any module THEN the interface SHALL display with modern, responsive CSS design compatible with mobile and desktop
3. WHEN performing database operations THEN the system SHALL use prepared statements and proper transaction management
4. WHEN data is modified THEN the system SHALL maintain audit trails with user identification and timestamps
5. IF database connection fails THEN the system SHALL display appropriate error messages and retry mechanisms
6. WHEN using mobile devices THEN the interface SHALL adapt responsively with touch-friendly controls
7. WHEN loading data THEN the system SHALL implement pagination and lazy loading for optimal performance