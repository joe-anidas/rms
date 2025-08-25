# Database Migration System

This directory contains the database migration files for the RMS (Real Estate Management System) enhancement project.

## Migration Files

The migration files are organized chronologically and include:

1. **20250824000001_create_properties_table.php** - Creates the properties table for managing garden/plot/house/flat listings
2. **20250824000002_create_staff_table.php** - Creates the comprehensive staff table with employment details
3. **20250824000003_create_registrations_table.php** - Creates the registrations table linking customers to properties
4. **20250824000004_create_transactions_table.php** - Creates the transactions table for payment tracking
5. **20250824000005_create_property_assignments_table.php** - Creates the property assignments table for staff-property relationships
6. **20250824000006_create_audit_logs_table.php** - Creates the audit logs table for tracking data changes
7. **20250824000007_enhance_customers_table.php** - Enhances the existing customers table with additional fields

## Database Schema Overview

### Core Tables

#### Properties Table
- Manages all property types (garden, plot, house, flat)
- Tracks property status (unsold, booked, sold)
- Links to assigned staff members
- Stores location and pricing information

#### Staff Table (Enhanced)
- Comprehensive employee information
- Personal details, employment information
- Banking and identification details
- Emergency contact information

#### Customers Table (Enhanced)
- Extended customer profiles
- Additional contact and financial information
- Emergency contacts and banking details
- Customer status tracking

#### Registrations Table
- Links customers to properties
- Tracks registration status and dates
- Stores agreement document paths
- Manages payment amounts

#### Transactions Table
- Records all payment transactions
- Supports multiple payment types and methods
- Generates receipt numbers
- Links to registrations

#### Property Assignments Table
- Manages staff assignments to properties
- Tracks assignment types (sales, maintenance, customer_service)
- Maintains assignment history

#### Audit Logs Table
- Tracks all data modifications
- Stores old and new values
- Records user information and timestamps

## Usage Instructions

### Running Migrations

1. **Run all pending migrations:**
   ```
   http://yoursite.com/migration_controller/migrate
   ```

2. **Check migration status:**
   ```
   http://yoursite.com/migration_controller/status
   ```

3. **List all available migrations:**
   ```
   http://yoursite.com/migration_controller/list_migrations
   ```

### Rollback Operations

1. **Rollback to specific version:**
   ```
   http://yoursite.com/migration_controller/rollback/20250824000003
   ```

2. **Reset all migrations:**
   ```
   http://yoursite.com/migration_controller/reset
   ```

### Database Backup

1. **Create backup before migration:**
   ```
   http://yoursite.com/migration_controller/backup
   ```

### Schema Validation

1. **Validate database schema:**
   ```
   http://yoursite.com/migration_controller/validate_schema
   ```

## Seeding Test Data

### Seed All Tables
```
http://yoursite.com/seeder_controller/seed_all
```

### Seed Individual Tables
```
http://yoursite.com/seeder_controller/seed_staff
http://yoursite.com/seeder_controller/seed_customers
http://yoursite.com/seeder_controller/seed_properties
http://yoursite.com/seeder_controller/seed_registrations
http://yoursite.com/seeder_controller/seed_transactions
http://yoursite.com/seeder_controller/seed_property_assignments
```

### Clear All Data
```
http://yoursite.com/seeder_controller/clear_all
```

### Check Seeding Status
```
http://yoursite.com/seeder_controller/status
```

## Foreign Key Relationships

The database maintains referential integrity through foreign key constraints:

- `properties.assigned_staff_id` → `staff.id`
- `registrations.property_id` → `properties.id`
- `registrations.customer_id` → `customers.id`
- `transactions.registration_id` → `registrations.id`
- `property_assignments.property_id` → `properties.id`
- `property_assignments.staff_id` → `staff.id`

## Migration Best Practices

1. **Always create a backup** before running migrations in production
2. **Test migrations** in development environment first
3. **Run migrations in order** - don't skip versions
4. **Verify schema** after migration completion
5. **Check foreign key constraints** are properly created

## Troubleshooting

### Common Issues

1. **Foreign Key Constraint Errors:**
   - Ensure parent tables exist before creating child tables
   - Check that referenced IDs exist in parent tables

2. **Migration Version Conflicts:**
   - Use `list_migrations` to see available versions
   - Use `status` to check current migration state

3. **Permission Issues:**
   - Ensure database user has CREATE, ALTER, DROP permissions
   - Check file permissions for backup directory

### Error Recovery

If a migration fails:

1. Check the error message in the migration output
2. Fix the issue in the migration file
3. Rollback to previous version if needed
4. Re-run the corrected migration

## Configuration

Migration settings are configured in `application/config/migration.php`:

- `migration_enabled` = TRUE (enabled for this project)
- `migration_type` = 'timestamp' (using timestamp-based naming)
- `migration_version` = 20250824000007 (latest version)

## Security Considerations

1. **Disable migrations in production** after deployment
2. **Restrict access** to migration controllers
3. **Backup regularly** before any schema changes
4. **Validate input** in migration scripts
5. **Use prepared statements** to prevent SQL injection

## Support

For issues with migrations:
1. Check the migration logs
2. Verify database connectivity
3. Ensure proper permissions
4. Contact the development team if needed