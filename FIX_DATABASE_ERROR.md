# Fix Database Error: Table 'rms.properties' doesn't exist

## Problem
Your RMS application is showing this error:
```
Type: mysqli_sql_exception
Message: Table 'rms.properties' doesn't exist
```

This happens because the database tables haven't been created yet, but your CodeIgniter application is trying to query them.

## Solution Options

### Option 1: Run Migrations (Recommended)
1. **Access the migration runner**: Navigate to `http://localhost:2211/rms/run_migrations.php`
2. **This will automatically create all necessary tables** using CodeIgniter's migration system
3. **Check the output** to ensure all migrations completed successfully

### Option 2: Manual SQL Execution
If migrations don't work, manually execute the SQL script:

1. **Open phpMyAdmin** or your MySQL client
2. **Connect to your `rms` database**
3. **Run the SQL script**: `create_all_tables_manual.sql`
4. **This creates all tables with sample data**

### Option 3: Individual Table Creation
If you prefer to create tables one by one:

1. **Start with**: `create_customers_table_manual.sql`
2. **Then**: `create_staff_table_manual.sql` 
3. **Finally**: `create_properties_table_manual.sql`

## Database Configuration
Your application is configured to connect to:
- **Host**: localhost
- **Database**: rms
- **Username**: root
- **Password**: (empty)

Make sure:
1. **XAMPP is running** (Apache + MySQL)
2. **Database `rms` exists** in phpMyAdmin
3. **MySQL service is active**

## Verification
After fixing, you should see:
- ✅ No more database errors
- ✅ Dashboard loads properly
- ✅ All tables exist in your database
- ✅ Sample data is available

## Files Created
- `run_migrations.php` - Automatic migration runner
- `create_all_tables_manual.sql` - Complete table creation script
- `create_properties_table_manual.sql` - Properties table only
- `FIX_DATABASE_ERROR.md` - This help file

## Next Steps
1. **Try Option 1 first** (migrations)
2. **If that fails, use Option 2** (manual SQL)
3. **Test your application** at `http://localhost:2211/rms`
4. **Delete the fix files** once everything works

## Need Help?
If you still have issues:
1. Check XAMPP control panel for MySQL status
2. Verify database connection in phpMyAdmin
3. Check MySQL error logs
4. Ensure all required tables exist
