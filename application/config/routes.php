<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Dashboard Routes
$route['dashboard'] = 'welcome/dashboard';

// Plots Routes
$route['plots/registered'] = 'welcome/registered_plot';
$route['plots/garden'] = 'welcome/garden_profile';
$route['plots/sold'] = 'welcome/sold_plots';
$route['plots/unsold'] = 'welcome/unsold_plots';
$route['plots/booked'] = 'welcome/booked_plots';
$route['plots/sold/(:num)'] = 'welcome/get_sold_plot/$1';
$route['plots/unsold/(:num)'] = 'welcome/get_unsold_plot/$1';
$route['plots/booked/(:num)'] = 'welcome/get_booked_plot/$1';
$route['plots/mark-sold'] = 'welcome/mark_plot_as_sold';
$route['plots/convert-booking'] = 'welcome/convert_booking_to_sale';
$route['plots/cancel-booking'] = 'welcome/cancel_booking';

// Staff Routes
$route['staff/add'] = 'welcome/add_staff';
$route['staff/list'] = 'welcome/staff_list';
$route['staff/details'] = 'welcome/staff_details';
$route['staff/(:num)'] = 'welcome/get_staff/$1';
$route['staff/submit'] = 'welcome/submit_staff';

// Customer Routes
$route['customer/add'] = 'welcome/add_customer';
$route['customer/list'] = 'welcome/customer_list';
$route['customer/details'] = 'welcome/customer_details';
$route['customer/(:num)'] = 'welcome/get_customer/$1';
$route['customer/submit'] = 'welcome/submit_customer';

// Billing Routes
$route['billing_recipt'] = 'welcome/billing_recipt';
$route['expense_details'] = 'welcome/expense_details';
$route['salary_advance'] = 'welcome/salary_advance';
$route['employee_salary'] = 'welcome/employee_salary';

// Legacy Routes (for backward compatibility)
$route['registered_plot'] = 'welcome/registered_plot';
$route['garden_profile'] = 'welcome/garden_profile';
$route['customer_details'] = 'welcome/customer_details';
$route['submit_customer'] = 'welcome/submit_customer';
$route['customer_list'] = 'welcome/customer_list';
$route['get_customer/(:num)'] = 'welcome/get_customer/$1';
$route['staff_details'] = 'welcome/staff_details';
$route['submit_staff'] = 'welcome/submit_staff';
$route['staff_list'] = 'welcome/staff_list';
$route['get_staff/(:num)'] = 'welcome/get_staff/$1';

// Utility/Test Routes
$route['debug_customer'] = 'welcome/debug_customer';
$route['simple_test'] = 'welcome/simple_test';
$route['db_test'] = 'welcome/db_test';
$route['check_table_exists'] = 'welcome/check_table_exists';
$route['test_db_connection'] = 'welcome/test_db_connection';
$route['create_customers_table'] = 'welcome/create_customers_table';
$route['get_all_customers'] = 'welcome/get_all_customers';
$route['test-customer'] = 'welcome/test_customer';