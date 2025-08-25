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
    :root {
      --primary-600: #0284c7;
      --primary-700: #0369a1;
      --primary-800: #075985;
      --primary-900: #0c4a6e;
      --warning-400: #fbbf24;
      --text-secondary: #64748b;
      --text-primary: #1e293b;
      --text-tertiary: #94a3b8;
    }
    
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

<body class="<?php echo isset($theme) ? $theme : 'bg-theme bg-theme1'; ?>">
 
<!-- Start wrapper-->
 <div id="wrapper">
 
  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" class="sidebar-wrapper modern-sidebar" data-simplebar="" data-simplebar-auto-hide="true">
     <div class="brand-logo">
      <a href="<?php echo base_url('dashboard'); ?>" class="d-flex align-items-center text-decoration-none">
        <img src="<?php echo base_url(); ?>assets/images/logo.png" class="logo" alt="RMS Logo" width="68" height="46" onerror="this.style.display='none'">
        <div class="ms-3">
          <h5 class="logo-text mb-0 text-white">AGP <span class="fw-bold">(RMS)</span></h5>
          <small class="text-white-50">Real Estate Management</small>
        </div>
      </a>
   </div>
   
   <!-- Mobile Navigation Toggle -->
   <div class="mobile-nav-toggle d-md-none">
     <button class="btn btn-link text-white" id="mobile-menu-close">
       <i class="fa fa-times"></i>
     </button>
   </div>
   
   <ul class="sidebar-menu do-nicescrol modern-nav-menu">
      
      <li class="sidebar-header">MAIN NAVIGATION</li>
      
      <!-- Dashboard -->
      <li class="modern-nav-item <?php echo (uri_string() == 'dashboard' || uri_string() == '') ? 'active' : ''; ?>">
        <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">
          <i class="fa fa-dashboard"></i> 
          <span>Dashboard</span>
        </a>
      </li>
      
      <!-- Property Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="propertiesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-home"></i> 
          <span>Property Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="propertiesDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties'); ?>">
            <i class="fa fa-list me-2"></i>All Properties
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/create'); ?>">
            <i class="fa fa-plus me-2"></i>Add New Property
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/search'); ?>">
            <i class="fa fa-search me-2"></i>Search Properties
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/statistics'); ?>">
            <i class="fa fa-chart-bar me-2"></i>Property Statistics
          </a>
        </div>
      </li>
      
      <!-- Customer Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="customersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-users"></i> 
          <span>Customer Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="customersDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers'); ?>">
            <i class="fa fa-list me-2"></i>All Customers
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/create'); ?>">
            <i class="fa fa-user-plus me-2"></i>Add New Customer
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/search'); ?>">
            <i class="fa fa-search me-2"></i>Search Customers
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/analytics'); ?>">
            <i class="fa fa-chart-line me-2"></i>Customer Analytics
          </a>
        </div>
      </li>
      
      <!-- Registration Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="registrationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-file-text"></i> 
          <span>Registrations</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="registrationsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations'); ?>">
            <i class="fa fa-list me-2"></i>All Registrations
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/create'); ?>">
            <i class="fa fa-plus me-2"></i>New Registration
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/statistics'); ?>">
            <i class="fa fa-chart-bar me-2"></i>Registration Statistics
          </a>
        </div>
      </li>

      <!-- Staff Management -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="staffDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-user-tie"></i> 
          <span>Staff Management</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="staffDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff'); ?>">
            <i class="fa fa-list me-2"></i>Staff Overview
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/create'); ?>">
            <i class="fa fa-user-plus me-2"></i>Add New Staff
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/assignments'); ?>">
            <i class="fa fa-tasks me-2"></i>Manage Assignments
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('staff/performance'); ?>">
            <i class="fa fa-chart-line me-2"></i>Performance Metrics
          </a>
        </div>
      </li>

      <!-- Transactions & Payments -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="transactionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-money-bill"></i> 
          <span>Transactions & Payments</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="transactionsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions'); ?>">
            <i class="fa fa-list me-2"></i>All Transactions
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/record-payment'); ?>">
            <i class="fa fa-plus me-2"></i>Record Payment
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/reports'); ?>">
            <i class="fa fa-chart-bar me-2"></i>Financial Reports
          </a>
        </div>
      </li>

      <!-- Reports & Analytics -->
      <li class="nav-item dropdown modern-nav-item">
        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-chart-bar"></i> 
          <span>Reports & Analytics</span>
        </a>
        <div class="dropdown-menu modern-dropdown-menu" aria-labelledby="reportsDropdown">
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('dashboard'); ?>">
            <i class="fa fa-dashboard me-2"></i>Main Dashboard
          </a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-white-50">Analytics</h6>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/properties'); ?>">
            <i class="fa fa-home me-2"></i>Property Analytics
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/financial'); ?>">
            <i class="fa fa-money-bill me-2"></i>Financial Analytics
          </a>
          <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('analytics/customers'); ?>">
            <i class="fa fa-users me-2"></i>Customer Analytics
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
       <i class="fa fa-bars menu-icon"></i>
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
  </ul>
     
  <ul class="navbar-nav align-items-center right-nav-link">
    <!-- Quick Actions -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="#" id="quickActionsDropdown">
        <i class="fa fa-plus-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="quickActionsDropdown">
        <h6 class="dropdown-header text-white">Quick Actions</h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('properties/create'); ?>">
          <i class="fa fa-home me-2"></i>Add Property
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('customers/create'); ?>">
          <i class="fa fa-user-plus me-2"></i>Add Customer
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('registrations/create'); ?>">
          <i class="fa fa-file-text me-2"></i>New Registration
        </a>
        <a class="dropdown-item modern-dropdown-item" href="<?php echo base_url('transactions/record-payment'); ?>">
          <i class="fa fa-money-bill me-2"></i>Record Payment
        </a>
      </div>
    </li>
    
    <!-- User Profile -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="#" id="userProfileDropdown">
        <span class="user-profile">
          <i class="fa fa-user-circle fa-2x"></i>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="userProfileDropdown">
        <div class="dropdown-item user-details">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <i class="fa fa-user-circle fa-2x text-white"></i>
            </div>
            <div>
              <h6 class="mb-0 text-white">Admin User</h6>
              <small class="text-white-50">admin@rms.com</small>
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="fa fa-user me-2"></i>Profile
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="fa fa-cog me-2"></i>Settings
        </a>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="fa fa-question-circle me-2"></i>Help & Support
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item modern-dropdown-item" href="#">
          <i class="fa fa-sign-out-alt me-2"></i>Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
</header>
<!--End topbar header-->

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
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
        }
        
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
});
</script>