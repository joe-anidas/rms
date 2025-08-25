<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Enhanced RMS URI ROUTING
| -------------------------------------------------------------------------
| Modern, user-friendly routing configuration for the Real Estate Management System
| Organized by modules with clean URLs and proper HTTP status codes
|
| Route Structure:
| - Dashboard & Analytics
| - Property Management
| - Customer Management  
| - Staff Management
| - Registration Management
| - Transaction Management
| - Reports & Analytics
| - Legacy Routes (for backward compatibility)
|
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'dashboard';
$route['404_override'] = 'error_handler/page_not_found';
$route['translate_uri_dashes'] = TRUE;

/*
| -------------------------------------------------------------------------
| DASHBOARD & ANALYTICS ROUTES
| -------------------------------------------------------------------------
*/
$route['dashboard'] = 'dashboard/index';
$route['dashboard/overview'] = 'dashboard/index';
$route['analytics'] = 'dashboard/analytics';
$route['analytics/properties'] = 'analytics_simple/properties';
$route['analytics/financial'] = 'analytics_simple/financial';
$route['analytics/customers'] = 'analytics_simple/customers';
$route['analytics/staff'] = 'dashboard/staff_analytics';

// AJAX Routes for Dashboard
$route['api/dashboard/data'] = 'dashboard/get_dashboard_data';
$route['api/analytics/properties'] = 'dashboard/ajax_property_analytics';
$route['api/analytics/financial'] = 'dashboard/ajax_financial_analytics';
$route['api/analytics/customers'] = 'dashboard/ajax_customer_analytics';
$route['api/analytics/staff'] = 'dashboard/ajax_staff_analytics';
$route['api/dashboard/export'] = 'dashboard/export_dashboard_data';

/*
| -------------------------------------------------------------------------
| PROPERTY MANAGEMENT ROUTES
| -------------------------------------------------------------------------
*/
$route['properties'] = 'properties_simple/index';
$route['properties/create'] = 'properties_simple/create';
$route['properties/store'] = 'properties/store';
$route['properties/(:num)'] = 'properties/view/$1';
$route['properties/(:num)/edit'] = 'properties/edit/$1';
$route['properties/(:num)/update'] = 'properties/update/$1';
$route['properties/(:num)/delete'] = 'properties/delete/$1';
$route['properties/search'] = 'properties/search';
$route['properties/statistics'] = 'properties/statistics';
$route['properties/export'] = 'properties/export';

// Property Status Management
$route['properties/status/change'] = 'properties/change_status';
$route['properties/staff/assign'] = 'properties/assign_staff';
$route['properties/staff/unassign'] = 'properties/unassign_staff';
$route['properties/bulk-actions'] = 'properties/bulk_action';

// Property API Routes
$route['api/properties/(:num)'] = 'properties/get_property/$1';
$route['api/properties/by-staff/(:num)'] = 'properties/get_by_staff/$1';
$route['api/properties/filter-options'] = 'properties/get_filter_options';

/*
| -------------------------------------------------------------------------
| CUSTOMER MANAGEMENT ROUTES
| -------------------------------------------------------------------------
*/
$route['customers'] = 'customers_simple/index';
$route['customers/create'] = 'customers_simple/create';
$route['customers/store'] = 'customers/store';
$route['customers/(:num)'] = 'customers/view/$1';
$route['customers/(:num)/edit'] = 'customers/edit/$1';
$route['customers/(:num)/update'] = 'customers/update/$1';
$route['customers/(:num)/delete'] = 'customers/delete/$1';
$route['customers/(:num)/profile'] = 'customers/profile/$1';
$route['customers/(:num)/properties'] = 'customers/properties/$1';
$route['customers/(:num)/transactions'] = 'customers/transactions/$1';
$route['customers/search'] = 'customers/search';
$route['customers/analytics'] = 'customers/analytics';
$route['customers/export'] = 'customers/export';

// Customer API Routes
$route['api/customers/(:num)'] = 'customers/get_customer/$1';
$route['api/customers/search'] = 'customers/ajax_search';

/*
| -------------------------------------------------------------------------
| STAFF MANAGEMENT ROUTES
| -------------------------------------------------------------------------
*/
$route['staff'] = 'staff_simple/index';
$route['staff/create'] = 'staff_simple/create';
$route['staff/store'] = 'staff/store';
$route['staff/(:num)'] = 'staff/view/$1';
$route['staff/(:num)/edit'] = 'staff/edit/$1';
$route['staff/(:num)/update'] = 'staff/update/$1';
$route['staff/(:num)/delete'] = 'staff/delete/$1';
$route['staff/(:num)/profile'] = 'staff/profile/$1';
$route['staff/assignments'] = 'staff/assignments';
$route['staff/(:num)/assignments'] = 'staff/assignments/$1';
$route['staff/workload'] = 'staff/workload';
$route['staff/performance'] = 'staff/performance';
$route['staff/export'] = 'staff/export';

// Staff Assignment Routes
$route['staff/assign/property'] = 'staff/assign_property';
$route['staff/assign/customer'] = 'staff/assign_customer';
$route['staff/assignments/end'] = 'staff/end_assignment';

// Staff API Routes
$route['api/staff/(:num)'] = 'staff/get_staff/$1';

/*
| -------------------------------------------------------------------------
| REGISTRATION MANAGEMENT ROUTES
| -------------------------------------------------------------------------
*/
$route['registrations'] = 'registrations_simple/index';
$route['registrations/create'] = 'registrations_simple/create';
$route['registrations/store'] = 'registrations/store';
$route['registrations/(:num)'] = 'registrations/view/$1';
$route['registrations/(:num)/edit'] = 'registrations/edit/$1';
$route['registrations/(:num)/update'] = 'registrations/update/$1';
$route['registrations/(:num)/status'] = 'registrations/update_status/$1';
$route['registrations/customer/(:num)/history'] = 'registrations/customer_history/$1';
$route['registrations/(:num)/agreement/download'] = 'registrations/download_agreement/$1';
$route['registrations/statistics'] = 'registrations/statistics';
$route['registrations/export'] = 'registrations/export';

/*
| -------------------------------------------------------------------------
| TRANSACTION MANAGEMENT ROUTES
| -------------------------------------------------------------------------
*/
$route['transactions'] = 'transactions_simple/index';
$route['transactions/record-payment'] = 'transactions_simple/record_payment';
$route['transactions/(:num)'] = 'transactions/view/$1';
$route['transactions/(:num)/edit'] = 'transactions/edit/$1';
$route['transactions/(:num)/receipt'] = 'transactions/receipt/$1';
$route['transactions/schedules'] = 'transactions/payment_schedules';
$route['transactions/schedules/create'] = 'transactions/create_schedule';
$route['transactions/pending'] = 'transactions/pending_payments';
$route['transactions/reports'] = 'transactions/financial_reports';
$route['transactions/export'] = 'transactions/export';

// Transaction API Routes
$route['api/transactions/balance/(:num)'] = 'transactions/get_balance/$1';
$route['api/transactions/schedule/(:num)'] = 'transactions/get_schedule/$1';

/*
| -------------------------------------------------------------------------
| REPORTS & ANALYTICS ROUTES
| -------------------------------------------------------------------------
*/
$route['reports'] = 'reports_simple/index';
$route['reports/sales'] = 'reports/sales_report';
$route['reports/bookings'] = 'reports/booking_report';
$route['reports/financial'] = 'reports/financial_summary';
$route['reports/customer-analytics'] = 'reports/customer_analytics';
$route['reports/staff-performance'] = 'reports/staff_performance';
$route['reports/property-analytics'] = 'reports/property_analytics';
$route['reports/export/(:any)'] = 'reports/export/$1';

/*
| -------------------------------------------------------------------------
| LEGACY ROUTES (Backward Compatibility)
| -------------------------------------------------------------------------
*/
// Legacy Plot Routes
$route['plots/registered'] = 'welcome/registered_plot';
$route['plots/garden'] = 'welcome/garden_profile';
$route['plots/sold'] = 'welcome/sold_plots';
$route['plots/unsold'] = 'welcome/unsold_plots';
$route['plots/booked'] = 'welcome/booked_plots';
$route['plots/unregistered'] = 'welcome/unregistered_plots';
$route['plots/overview'] = 'welcome/plots_overview';

// Legacy Short Routes
$route['registered'] = 'welcome/registered_plot';
$route['unregistered'] = 'welcome/unregistered_plots';
$route['booked'] = 'welcome/booked_plots';
$route['sold'] = 'welcome/sold_plots';
$route['unsold'] = 'welcome/unsold_plots';

// Legacy Customer & Staff Routes
$route['customer/list'] = 'welcome/customer_list';
$route['customer/details'] = 'welcome/customer_details';
$route['customer/(:num)'] = 'welcome/get_customer/$1';
$route['customer/submit'] = 'welcome/submit_customer';
$route['staff/list'] = 'welcome/staff_list';
$route['staff/details'] = 'welcome/staff_details';
$route['staff/submit'] = 'welcome/submit_staff';

// Legacy API Routes
$route['get_sold_plot/(:num)'] = 'welcome/get_sold_plot/$1';
$route['get_unsold_plot/(:num)'] = 'welcome/get_unsold_plot/$1';
$route['get_booked_plot/(:num)'] = 'welcome/get_booked_plot/$1';
$route['get_unregistered_plot/(:num)'] = 'welcome/get_unregistered_plot/$1';
$route['mark_plot_as_sold'] = 'welcome/mark_plot_as_sold';
$route['convert_booking_to_sale'] = 'welcome/convert_booking_to_sale';
$route['cancel_booking'] = 'welcome/cancel_booking';

/*
| -------------------------------------------------------------------------
| UTILITY & SYSTEM ROUTES
| -------------------------------------------------------------------------
*/
// System Routes
$route['success'] = 'welcome/success';
$route['home'] = 'dashboard/index';

// Development & Testing Routes (Remove in production)
$route['debug/customer'] = 'welcome/debug_customer';
$route['test/simple'] = 'welcome/simple_test';
$route['test/database'] = 'welcome/db_test';
$route['test/connection'] = 'welcome/test_db_connection';
$route['migrate/customers'] = 'welcome/create_customers_table';

/*
| -------------------------------------------------------------------------
| API ROUTES
| -------------------------------------------------------------------------
*/
$route['api/search/global'] = 'api/search_global';
$route['api/search/suggestions'] = 'api/search_suggestions';
$route['api/notifications'] = 'api/notifications';
$route['api/notifications/mark-read'] = 'api/notifications_mark_read';
$route['api/navigation/menu'] = 'api/navigation_menu';

/*
| -------------------------------------------------------------------------
| PERFORMANCE MONITORING ROUTES
| -------------------------------------------------------------------------
*/
$route['performance'] = 'performance/index';
$route['performance/clear-cache'] = 'performance/clear_cache';
$route['performance/warm-cache'] = 'performance/warm_cache';
$route['performance/clean-assets'] = 'performance/clean_assets';
$route['performance/clean-images'] = 'performance/clean_images';
$route['performance/optimize-database'] = 'performance/optimize_database';
$route['performance/test-speed'] = 'performance/test_speed';
$route['performance/batch-optimize-images'] = 'performance/batch_optimize_images';
$route['performance/generate-report'] = 'performance/generate_report';
$route['api/performance/metrics'] = 'performance/get_metrics';

/*
| -------------------------------------------------------------------------
| ERROR HANDLING ROUTES
| -------------------------------------------------------------------------
*/
$route['error/404'] = 'error_handler/page_not_found';
$route['error/500'] = 'error_handler/server_error';
$route['error/403'] = 'error_handler/access_denied';