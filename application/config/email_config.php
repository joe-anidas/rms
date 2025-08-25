<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Configuration
|--------------------------------------------------------------------------
|
| Configuration settings for the email functionality in the RMS system.
| Update these settings according to your email server configuration.
|
*/

$config['email_settings'] = array(
    // SMTP Configuration
    'protocol' => 'smtp',
    'smtp_host' => 'localhost', // Change to your SMTP server (e.g., smtp.gmail.com)
    'smtp_port' => 587,
    'smtp_user' => '', // Your SMTP username/email
    'smtp_pass' => '', // Your SMTP password
    'smtp_crypto' => 'tls', // tls or ssl
    
    // Email Settings
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => TRUE,
    'newline' => "\r\n",
    
    // Sender Information
    'from_email' => 'noreply@yourdomain.com', // Change to your email
    'from_name' => 'Real Estate Management System',
    
    // Company Information for receipts and reports
    'company_info' => array(
        'name' => 'Real Estate Management System',
        'address' => 'Your Company Address Line 1, Address Line 2, City, State, PIN',
        'phone' => '+91 XXXXXXXXXX',
        'email' => 'info@yourdomain.com',
        'website' => 'www.yourdomain.com'
    )
);

/*
|--------------------------------------------------------------------------
| Gmail SMTP Example Configuration
|--------------------------------------------------------------------------
|
| If you're using Gmail SMTP, use these settings:
|
| 'smtp_host' => 'smtp.gmail.com',
| 'smtp_port' => 587,
| 'smtp_user' => 'your-email@gmail.com',
| 'smtp_pass' => 'your-app-password', // Use App Password, not regular password
| 'smtp_crypto' => 'tls',
|
| Note: Enable 2-factor authentication and generate an App Password for Gmail
|
*/

/*
|--------------------------------------------------------------------------
| Other Email Provider Examples
|--------------------------------------------------------------------------
|
| Outlook/Hotmail:
| 'smtp_host' => 'smtp-mail.outlook.com',
| 'smtp_port' => 587,
| 'smtp_crypto' => 'tls',
|
| Yahoo Mail:
| 'smtp_host' => 'smtp.mail.yahoo.com',
| 'smtp_port' => 587,
| 'smtp_crypto' => 'tls',
|
| Custom SMTP Server:
| 'smtp_host' => 'mail.yourdomain.com',
| 'smtp_port' => 587, // or 465 for SSL
| 'smtp_crypto' => 'tls', // or 'ssl'
|
*/