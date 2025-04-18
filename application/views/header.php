<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Dashtreme Admin - Free Dashboard for Bootstrap 4 by Codervent</title>
  <!-- loader-->
  <link href="<?php base_url(); ?>assets/css/pace.min.css" rel="stylesheet"/>
  <script src="<?php base_url(); ?>assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="<?php base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
  <!-- Vector CSS -->
  <!-- <link href="<?php base_url(); ?>assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/> -->
  <!-- simplebar CSS-->
  <link href="<?php base_url(); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
  <!-- Bootstrap core CSS-->
  <link href="<?php base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="<?php base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="<?php base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <!-- Sidebar CSS-->
  <link href="<?php base_url(); ?>assets/css/sidebar-menu.css" rel="stylesheet"/>
  <!-- Custom Style-->
  <link href="<?php base_url(); ?>assets/css/app-style.css" rel="stylesheet"/>
  
 


  
    
  <style>
    .dropdown-menu{
      background-color: #00192e;
    }
    .sidebar-menu> li:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.15);
      border-left-color: #ffffff;
      border-radius: 0px 50px 50px 0px;
    }
    .dropdown-item:hover {
      padding: .70rem 1.5rem;
      background-color: #012d50;
      color: #ffffff;
      border-radius: 50px 50px 50px 50px;
    }
    /* .dropdown-item:active {
      color: #fff;
      text-decoration: none;
      background-color: #012d50;
      border-radius: 0px 50px 50px 0px;
    } */
    .nav-item.dropdown.active {
    /* Remove styles applied by active */
    background-color: transparent !important;
    color: inherit !important;
    font-weight: normal !important;
   }
  </style>
</head>

<body class="<?php echo $theme; ?>">
 
<!-- Start wrapper-->
 <div id="wrapper">
 
  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
     <div class="brand-logo">
      <a href="index.html">
      <img src="<?php echo base_url(); ?>assets/images/logo.png" class="logo" alt="logo" width="68" height="46">
       <h5 class="logo-text">AGP <b>(RMS)</b></h5>
     </a>
   </div>
   <ul class="sidebar-menu do-nicescrol">
      
      <li class="sidebar-header">MAIN NAVIGATION</li>
       <li>
        <a href="<?php echo base_url(); ?>">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-home"></i> <span>Nagar/Garden</span></a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="<?php echo base_url('garden_profile'); ?>">Nagar/Garden Profile</a>
          <a class="dropdown-item" href="<?php echo base_url('registered_plot'); ?>">Sold/Registered plot</a>
          <a class="dropdown-item" href="#">Unsold/Unregistered Plot</a>
          <a class="dropdown-item" href="#">Booked Plots</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-home"></i> <span>Billing & Accounts</span></a>
        <div class="dropdown-menu" aria-labelledby="dropdown02">
          <a class="dropdown-item" href="#">Custumor Receipt</a>
          <a class="dropdown-item" href="#">Expense</a>
          <a class="dropdown-item" href="#">Salary Advance Info</a>
          <a class="dropdown-item" href="#">Staff Salary Info</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-home"></i> <span>Offer Desk</span></a>
        <div class="dropdown-menu" aria-labelledby="dropdown03">
          <a class="dropdown-item" href="#">Add Offer</a>
          <a class="dropdown-item" href="#">Offer Incentives</a>
        </div>
      </li>
      <li>
        <a href="<?php echo base_url('customer_details'); ?>">
          <i class="zmdi zmdi-format-list-bulleted"></i> <span>Customer/Buyer Info</span>
        </a>
      </li>

      <li>
        <a href="<?php echo base_url('staff_details'); ?>">
          <i class="zmdi zmdi-grid"></i> <span>Employees/Staff Info</span>
        </a>
      </li> 
    </ul>


      
   
   </div>
   <!--End sidebar-wrapper-->

<!--Start topbar header-->
<header class="topbar-nav">
 <nav class="navbar navbar-expand fixed-top">
  <ul class="navbar-nav mr-auto align-items-center">
    <li class="nav-item">
      <a class="nav-link toggle-menu" href="javascript:void();">
       <i class="icon-menu menu-icon"></i>
     </a>
    </li>
    <li class="nav-item">
      <form class="search-bar">
        <input type="text" class="form-control" placeholder="Enter keywords">
         <a href="javascript:void();"><i class="icon-magnifier"></i></a>
      </form>
    </li>
  </ul>
     
  <ul class="navbar-nav align-items-center right-nav-link">
    <!-- <li class="nav-item dropdown-lg">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
      <i class="fa fa-envelope-open-o"></i></a>
    </li> -->
    <li class="nav-item dropdown-lg">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
      <i class="fa fa-bell-o"></i></a>
    </li>
    <!-- <li class="nav-item language">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();"><i class="fa fa-flag"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
          <li class="dropdown-item"> <i class="flag-icon flag-icon-gb mr-2"></i> English</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-fr mr-2"></i> French</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-cn mr-2"></i> Chinese</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-de mr-2"></i> German</li>
        </ul>
    </li> -->
    <li class="nav-item">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
        <span class="user-profile"><img src="<?php base_url(); ?>assets/avatar/avatar.jpg" class="img-circle" alt="user avatar"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
       <li class="dropdown-item user-details">
        <a href="javaScript:void();">
           <div class="media">
             <div class="avatar"><img class="align-self-start mr-3" src="<?php base_url(); ?>assets/avatar/avatar.JPG" alt="user avatar"></div>
            <div class="media-body">
            <h6 class="mt-2 user-title">Sarajhon Mccoy</h6>
            <p class="user-subtitle">mccoy@example.com</p>
            </div>
           </div>
          </a>
        </li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-power mr-2"></i> Logout</li>
      </ul>
    </li>
  </ul>
</nav>
</header>
<!--End topbar header-->

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

