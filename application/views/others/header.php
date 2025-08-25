<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content="Real Estate Management System - Comprehensive property, customer, and staff management"/>
  <meta name="author" content="RMS Team"/>
  
  <!-- Security Headers -->
  <meta http-equiv="X-Content-Type-Options" content="nosniff">
  <meta http-equiv="X-Frame-Options" content="DENY">
  <meta http-equiv="X-XSS-Protection" content="1; mode=block">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  
  <!-- CSRF Token for AJAX requests -->
  <?php 
  // Safe CSRF token generation
  $csrf_token = 'rms_token_' . uniqid();
  ?>
  <meta name="csrf-token" content="<?php echo $csrf_token; ?>">
  
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Real Estate Management System</title>
  
  <?php
  // Load performance helper and initialize asset optimization
  $this->load->helper('performance');
  $this->load->library('asset_optimizer');
  
  // Define critical CSS files for above-the-fold content
  $critical_css = array(
    'assets/css/bootstrap5.min.css',
    'assets/css/modern-theme.css',
    'assets/css/pace.min.css'
  );
  
  // Define all CSS files
  $css_files = array(
    'assets/plugins/simplebar/css/simplebar.css',
    'assets/css/bootstrap.min.css',
    'assets/css/animate.css',
    'assets/css/icons.css',
    'assets/css/sidebar-menu.css',
    'assets/css/app-style.css',
    'assets/css/dashboard.css',
    'assets/css/modern-components.css'
  );
  
  // Preload critical resources
  $preload_resources = array(
    array('src' => 'assets/css/bootstrap5.min.css', 'as' => 'style'),
    array('src' => 'assets/css/modern-theme.css', 'as' => 'style'),
    array('src' => 'assets/js/jquery.min.js', 'as' => 'script'),
    array('src' => 'assets/js/bootstrap.min.js', 'as' => 'script')
  );
  
  echo preload_critical_resources($preload_resources);
  ?>
  
  <!-- Critical CSS (inline for performance) -->
  <?php echo $this->asset_optimizer->generate_critical_css($critical_css); ?>
  
  <!--favicon-->
  <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
  
  <!-- Core CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/modern-rms.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/icons.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/sidebar-menu.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/app-style.css'); ?>">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
 


  
    
  <!-- Enhanced Navigation Styles -->
  <style>
    /* Modern Navigation Styles */
    .modern-sidebar {
      background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-900) 100%);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .modern-nav-item {
      transition: all 0.3s ease;
      border-radius: 8px;
      margin: 2px 8px;
    }
    
    .modern-nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(4px);
    }
    
    .modern-nav-item.active {
      background: rgba(255, 255, 255, 0.15);
      border-left: 4px solid var(--warning-400);
    }
    
    .modern-dropdown-menu {
      background: var(--primary-700);
      border: none;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      margin-top: 8px;
    }
    
    .modern-dropdown-item {
      color: rgba(255, 255, 255, 0.9);
      padding: 12px 20px;
      border-radius: 8px;
      margin: 4px 8px;
      transition: all 0.2s ease;
    }
    
    .modern-dropdown-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(4px);
    }
    
    .breadcrumb-modern {
      background: transparent;
      padding: 0;
      margin: 0;
    }
    
    .breadcrumb-modern .breadcrumb-item {
      color: var(--text-secondary);
    }
    
    .breadcrumb-modern .breadcrumb-item.active {
      color: var(--text-primary);
      font-weight: 500;
    }
    
    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
      content: "›";
      color: var(--text-tertiary);
    }
    
    /* Mobile Navigation */
    @media (max-width: 768px) {
      .sidebar-wrapper {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .sidebar-wrapper.show {
        transform: translateX(0);
      }
      
      .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
      }
      
      .mobile-overlay.show {
        display: block;
      }
    }
    
    /* Legacy compatibility */
    .dropdown-menu {
      background-color: var(--primary-700);
    }
    
    .sidebar-menu > li:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.1);
      border-left-color: var(--warning-400);
      border-radius: 8px;
    }
    
    .dropdown-item:hover {
      padding: .70rem 1.5rem;
      background-color: rgba(255, 255, 255, 0.1);
      color: #ffffff;
      border-radius: 8px;
    }
  </style>
</head>

<body class="<?php echo $theme; ?>">
 
<!-- Start wrapper-->
 <div id="wrapper">
 
  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" class="sidebar-wrapper modern-sidebar" data-simplebar="" data-simplebar-auto-hide="true">
     <div class="brand-logo">
      <a href="<?php echo base_url('dashboard'); ?>" class="d-flex align-items-center text-decoration-none">
        <img src="<?php echo base_url(); ?>assets/images/logo.png" class="logo" alt="RMS Logo" width="68" height="46">
        <div class="ms-3">
          <h5 class="logo-text mb-0 text-white">AGP <span class="fw-bold">(RMS)</span></h5>
          <small class="text-white-50">Real Estate Management</small>
        </div>
      </a>
   </div>
   
   <!-- Mobile Navigation Toggle -->
   <div class="mobile-nav-toggle d-md-none">
     <button class="btn btn-link text-white" id="mobile-menu-close">
       <i class="zmdi zmdi-close"></i>
     </button>
   </div>
   
   <ul class="sidebar-menu do-nicescrol modern-nav-menu">
      
      <li class="sidebar-header">MAIN NAVIGATION</li>
      
      <!-- Dashboard -->
      <li class="modern-nav-item <?php echo (current_url() == base_url('dashboard')) ? 'active' : ''; ?>">
        <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">
          <i class="zmdi zmdi-view-dashboard"></i> 
          <span>Dashboard</span>
        </a>
      </li>
      
      <!-- Property Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="propertiesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-home"></i> 
          <span>Property Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="propertiesDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties'); ?>">
            <i class="zmdi zmdi-view-list me-2"></i>All Properties
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/create'); ?>">
            <i class="zmdi zmdi-plus me-2"></i>Add New Property
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/search'); ?>">
            <i class="zmdi zmdi-search me-2"></i>Search Properties
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/statistics'); ?>">
            <i class="zmdi zmdi-chart me-2"></i>Property Statistics
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Legacy Views</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('plots/overview'); ?>">Plots Overview</a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('garden_profile'); ?>">Garden Profile</a>
        </div>
      </li>
      <!-- Customer Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="customersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-account-box"></i> 
          <span>Customer Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="customersDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers'); ?>">
            <i class="zmdi zmdi-accounts-list me-2"></i>All Customers
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/create'); ?>">
            <i class="zmdi zmdi-account-add me-2"></i>Add New Customer
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/search'); ?>">
            <i class="zmdi zmdi-search me-2"></i>Search Customers
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/analytics'); ?>">
            <i class="zmdi zmdi-chart me-2"></i>Customer Analytics
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Legacy Views</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customer_details'); ?>">Add Customer (Legacy)</a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customer_list'); ?>">View Customers (Legacy)</a>
        </div>
      </li>
      
      <!-- Registration Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="registrationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-assignment"></i> 
          <span>Registrations</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="registrationsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations'); ?>">
            <i class="zmdi zmdi-view-list me-2"></i>All Registrations
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/create'); ?>">
            <i class="zmdi zmdi-plus me-2"></i>New Registration
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/statistics'); ?>">
            <i class="zmdi zmdi-chart me-2"></i>Registration Statistics
          </a>
        </div>
      </li>

      <!-- Staff Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="staffDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-accounts"></i> 
          <span>Staff Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="staffDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff'); ?>">
            <i class="zmdi zmdi-view-list me-2"></i>Staff Overview
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/create'); ?>">
            <i class="zmdi zmdi-account-add me-2"></i>Add New Staff
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/assignments'); ?>">
            <i class="zmdi zmdi-assignment-account me-2"></i>Manage Assignments
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/workload'); ?>">
            <i class="zmdi zmdi-chart me-2"></i>Workload Dashboard
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/performance'); ?>">
            <i class="zmdi zmdi-trending-up me-2"></i>Performance Metrics
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Legacy Views</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff_details'); ?>">Add Staff (Legacy)</a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff_list'); ?>">View Staff (Legacy)</a>
        </div>
      </li>

      <!-- Transactions & Payments -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="transactionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-money"></i> 
          <span>Transactions & Payments</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="transactionsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions'); ?>">
            <i class="zmdi zmdi-view-list me-2"></i>All Transactions
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/record-payment'); ?>">
            <i class="zmdi zmdi-plus me-2"></i>Record Payment
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/schedules'); ?>">
            <i class="zmdi zmdi-calendar me-2"></i>Payment Schedules
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/pending'); ?>">
            <i class="zmdi zmdi-time me-2"></i>Pending Payments
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/reports'); ?>">
            <i class="zmdi zmdi-chart me-2"></i>Financial Reports
          </a>
        </div>
      </li>

      <!-- Reports & Analytics -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="zmdi zmdi-chart"></i> 
          <span>Reports & Analytics</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="reportsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('dashboard'); ?>">
            <i class="zmdi zmdi-view-dashboard me-2"></i>Main Dashboard
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Analytics</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/properties'); ?>">
            <i class="zmdi zmdi-home me-2"></i>Property Analytics
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/financial'); ?>">
            <i class="zmdi zmdi-money me-2"></i>Financial Analytics
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/customers'); ?>">
            <i class="zmdi zmdi-account-box me-2"></i>Customer Analytics
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/staff'); ?>">
            <i class="zmdi zmdi-accounts me-2"></i>Staff Analytics
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Reports</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('reports/sales'); ?>">
            <i class="zmdi zmdi-trending-up me-2"></i>Sales Report
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('reports/bookings'); ?>">
            <i class="zmdi zmdi-calendar-check me-2"></i>Booking Report
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('reports/financial'); ?>">
            <i class="zmdi zmdi-balance me-2"></i>Financial Summary
          </a>
        </div>
      </li>
    </ul>


      
   
   </div>
   <!--End sidebar-wrapper-->

<!--Start topbar header-->
<header class="topbar-nav">
 <nav class="navbar navbar-expand fixed-top">
  <ul class="navbar-nav me-auto align-items-center">
    <li class="nav-item">
      <a class="nav-link toggle-menu" href="javascript:void();" id="sidebar-toggle">
       <i class="icon-menu menu-icon"></i>
     </a>
    </li>
    
    <!-- Breadcrumb Navigation -->
    <li class="nav-item d-none d-md-block">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-modern">
          <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
              <?php if ($index === count($breadcrumbs) - 1): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($crumb['title']); ?></li>
              <?php else: ?>
                <li class="breadcrumb-item">
                  <a href="<?php echo $crumb['url']; ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($crumb['title']); ?>
                  </a>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="breadcrumb-item">
              <a href="<?php echo base_url('dashboard'); ?>" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Page'; ?>
            </li>
          <?php endif; ?>
        </ol>
      </nav>
    </li>
    
    <!-- Global Search -->
    <li class="nav-item d-none d-lg-block">
      <form class="search-bar" id="global-search-form">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search properties, customers, staff..." id="global-search-input">
          <button class="btn btn-outline-secondary" type="submit">
            <i class="icon-magnifier"></i>
          </button>
        </div>
      </form>
    </li>
  </ul>
     
  <ul class="navbar-nav align-items-center right-nav-link">
    <!-- Mobile Search Toggle -->
    <li class="nav-item d-lg-none">
      <a class="nav-link" href="#" id="mobile-search-toggle">
        <i class="icon-magnifier"></i>
      </a>
    </li>
    
    <!-- Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="#" id="notificationsDropdown">
        <i class="fa fa-bell-o"></i>
        <span class="badge bg-danger badge-pill notification-count">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="notificationsDropdown">
        <h6 class="dropdown-header text-white">Notifications</h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <div class="d-flex align-items-center">
            <i class="zmdi zmdi-money text-success me-3"></i>
            <div>
              <div class="fw-bold">New Payment Received</div>
              <small class="text-muted">₹50,000 from John Doe</small>
            </div>
          </div>
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <div class="d-flex align-items-center">
            <i class="zmdi zmdi-home text-info me-3"></i>
            <div>
              <div class="fw-bold">Property Status Updated</div>
              <small class="text-muted">Plot #123 marked as sold</small>
            </div>
          </div>
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <div class="d-flex align-items-center">
            <i class="zmdi zmdi-account text-warning me-3"></i>
            <div>
              <div class="fw-bold">New Customer Registration</div>
              <small class="text-muted">Jane Smith registered</small>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center modern-dropdown-item" href="#">
          <small>View All Notifications</small>
        </a>
      </div>
    </li>
    
    <!-- Quick Actions -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="#" id="quickActionsDropdown">
        <i class="zmdi zmdi-plus-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="quickActionsDropdown">
        <h6 class="dropdown-header text-white">Quick Actions</h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/create'); ?>">
          <i class="zmdi zmdi-home me-2"></i>Add Property
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/create'); ?>">
          <i class="zmdi zmdi-account-add me-2"></i>Add Customer
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/create'); ?>">
          <i class="zmdi zmdi-assignment me-2"></i>New Registration
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/record-payment'); ?>">
          <i class="zmdi zmdi-money me-2"></i>Record Payment
        </a>
      </div>
    </li>
    
    <!-- User Profile -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="#" id="userProfileDropdown">
        <span class="user-profile">
          <img src="<?php echo base_url(); ?>assets/avatar/avatar.jpg" class="img-circle" alt="user avatar">
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="userProfileDropdown">
        <div class="dropdown-item user-details">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <img class="rounded-circle" src="<?php echo base_url(); ?>assets/avatar/avatar.jpg" alt="user avatar" width="40" height="40">
            </div>
            <div>
              <h6 class="mb-0 text-white">Admin User</h6>
              <small class="text-white-50">admin@rms.com</small>
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="icon-user me-2"></i>Profile
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="icon-settings me-2"></i>Settings
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="icon-question me-2"></i>Help & Support
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="icon-power me-2"></i>Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
</header>
<!--End topbar header-->

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobile-overlay"></div>

<!-- Mobile Search Modal -->
<div class="modal fade" id="mobileSearchModal" tabindex="-1" aria-labelledby="mobileSearchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mobileSearchModalLabel">Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="mobile-search-form">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search properties, customers, staff..." id="mobile-search-input">
            <button class="btn btn-primary" type="submit">
              <i class="icon-magnifier"></i>
            </button>
          </div>
        </form>
        <div id="mobile-search-results" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

<!-- Security and CSRF JavaScript -->
<script>
// Global security configuration
window.RMS_Security = {
    csrfToken: '<?php echo $csrf_token; ?>',
    csrfTokenName: 'csrf_token',
    baseUrl: '<?php echo base_url(); ?>',
    
    // Update CSRF token in all forms
    updateCSRFToken: function(newToken) {
        this.csrfToken = newToken;
        document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
        
        // Update all forms
        document.querySelectorAll('input[name="' + this.csrfTokenName + '"]').forEach(function(input) {
            input.value = newToken;
        });
    },
    
    // Add CSRF token to AJAX requests
    setupAjaxCSRF: function() {
        // jQuery AJAX setup
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                        xhr.setRequestHeader("X-CSRF-Token", RMS_Security.csrfToken);
                    }
                }
            });
        }
        
        // Fetch API setup
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            if (options.method && !/^(GET|HEAD|OPTIONS|TRACE)$/i.test(options.method)) {
                options.headers = options.headers || {};
                options.headers['X-CSRF-Token'] = RMS_Security.csrfToken;
            }
            return originalFetch(url, options);
        };
    },
    
    // Sanitize HTML output
    escapeHtml: function(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
    
    // Validate form inputs
    validateInput: function(input, type = 'string') {
        let value = input.value.trim();
        
        switch (type) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(value);
            
            case 'phone':
                const phoneRegex = /^[0-9+\-\s()]{10,15}$/;
                return phoneRegex.test(value);
            
            case 'numeric':
                return !isNaN(value) && value !== '';
            
            case 'required':
                return value !== '';
            
            default:
                // Check for dangerous patterns
                const dangerousPatterns = [
                    /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                    /javascript:/i,
                    /vbscript:/i,
                    /on\w+\s*=/i
                ];
                
                return !dangerousPatterns.some(pattern => pattern.test(value));
        }
    },
    
    // Show security alert
    showSecurityAlert: function(message, type = 'warning') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <strong>Security Notice:</strong> ${this.escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
        }
    }
};

// Initialize security features when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    RMS_Security.setupAjaxCSRF();
    
    // Add CSRF token to all forms
    document.querySelectorAll('form').forEach(function(form) {
        if (!form.querySelector('input[name="' + RMS_Security.csrfTokenName + '"]')) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = RMS_Security.csrfTokenName;
            csrfInput.value = RMS_Security.csrfToken;
            form.appendChild(csrfInput);
        }
    });
    
    // Validate forms on submit
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            form.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(input) {
                if (!RMS_Security.validateInput(input, 'required')) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Validate email fields
            form.querySelectorAll('input[type="email"]').forEach(function(input) {
                if (input.value && !RMS_Security.validateInput(input, 'email')) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Validate phone fields
            form.querySelectorAll('input[data-type="phone"]').forEach(function(input) {
                if (input.value && !RMS_Security.validateInput(input, 'phone')) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                RMS_Security.showSecurityAlert('Please correct the highlighted fields before submitting.');
            }
        });
    });
    
    // Handle CSRF token refresh on AJAX errors
    document.addEventListener('ajaxError', function(event) {
        if (event.detail && event.detail.status === 403) {
            // CSRF token might be expired, try to refresh
            fetch(RMS_Security.baseUrl + 'api/csrf-token')
                .then(response => response.json())
                .then(data => {
                    if (data.csrf_token) {
                        RMS_Security.updateCSRFToken(data.csrf_token);
                        RMS_Security.showSecurityAlert('Security token refreshed. Please try again.', 'info');
                    }
                })
                .catch(() => {
                    RMS_Security.showSecurityAlert('Security validation failed. Please refresh the page.', 'danger');
                });
        }
    });
});

// Rate limiting for form submissions
const formSubmissionTracker = new Map();
function checkFormSubmissionRate(formId) {
    const now = Date.now();
    const submissions = formSubmissionTracker.get(formId) || [];
    
    // Remove submissions older than 1 minute
    const recentSubmissions = submissions.filter(time => now - time < 60000);
    
    if (recentSubmissions.length >= 5) {
        RMS_Security.showSecurityAlert('Too many form submissions. Please wait before trying again.', 'warning');
        return false;
    }
    
    recentSubmissions.push(now);
    formSubmissionTracker.set(formId, recentSubmissions);
    return true;
}
</script>

