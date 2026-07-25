<?php
// dashboard-header.php - Session check with proper condition
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?php echo isset($title) ? $title : 'Dashboard'; ?></title>
    <meta name="author" content="Nile-Theme">
    <meta name="robots" content="index follow">
    <meta name="googlebot" content="index follow">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="keywords" content="directory, doctor, doctor directory, Health directory, listing, map, medical, medical directory, professional directory, reservation, reviews">
    <meta name="description" content="Health Care & Medical Services Directory">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800%7CPoppins:300i,300,400,700,400i,500%7CDancing+Script:700%7CDancing+Script:700" rel="stylesheet">
    <!-- animate -->
    <link rel="stylesheet" href="assets/css/animate.css" />
    <!-- owl Carousel assets -->
    <link href="assets/css/owl.carousel.css" rel="stylesheet">
    <link href="assets/css/owl.theme.css" rel="stylesheet">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- hover anmation -->
    <link rel="stylesheet" href="assets/css/hover-min.css">
    <!-- flag icon -->
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <!-- main style -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- colors -->
    <link rel="stylesheet" href="assets/css/colors/main.css">
    <!-- elegant icon -->
    <link rel="stylesheet" href="assets/css/elegant_icon.css">
    <link rel="stylesheet" href="style.css">
    <!-- admin style -->
    <link rel="stylesheet" href="assets/css/sb-admin.css">
    
    <!-- ============================================
         CUSTOM STYLES - FIX ALL CONFLICTS
         ============================================ -->
    <style>
        /* ===== HIDE SIDEBAR ===== */
        #mainNav {
            display: none !important;
        }
        
        /* ===== MAIN CONTENT FULL WIDTH ===== */
        .content-wrapper {
            margin-left: 0 !important;
            padding-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* ===== TOP BAR FULL WIDTH ===== */
        header.background-white {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 20px !important;
        }
        
        /* ===== FIX OVERLAPPING ISSUES ===== */
        .fixed-top {
            position: sticky !important;
        }
        
        /* ===== REMOVE SIDEBAR PADDING ===== */
        body {
            padding-left: 0 !important;
        }
        
        /* ============================================
           FORCE VISIBLE BUTTONS - OVERRIDE ALL CSS
           ============================================ */
        .action-btn-force {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 999 !important;
            position: relative !important;
        }
        
        /* View Button - Green */
        .btn-view-force {
            background: #2ecc71 !important;
            color: white !important;
            padding: 5px 14px !important;
            border-radius: 20px !important;
            text-decoration: none !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin: 2px !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(46,204,113,0.3) !important;
            display: inline-block !important;
        }
        .btn-view-force:hover {
            background: #27ae60 !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        
        /* Edit Button - Blue */
        .btn-edit-force {
            background: #3498db !important;
            color: white !important;
            padding: 5px 14px !important;
            border-radius: 20px !important;
            text-decoration: none !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin: 2px !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(52,152,219,0.3) !important;
            display: inline-block !important;
        }
        .btn-edit-force:hover {
            background: #2980b9 !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        
        /* Toggle Button - Yellow/Green */
        .btn-toggle-force {
            background: #f39c12 !important;
            color: white !important;
            padding: 5px 14px !important;
            border-radius: 20px !important;
            text-decoration: none !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin: 2px !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(243,156,18,0.3) !important;
            display: inline-block !important;
        }
        .btn-toggle-force:hover {
            background: #e67e22 !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        .btn-toggle-force.active-btn {
            background: #2ecc71 !important;
        }
        .btn-toggle-force.active-btn:hover {
            background: #27ae60 !important;
        }
        
        /* Delete Button - Red */
        .btn-delete-force {
            background: #e74c3c !important;
            color: white !important;
            padding: 5px 14px !important;
            border-radius: 20px !important;
            text-decoration: none !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin: 2px !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(231,76,60,0.3) !important;
            display: inline-block !important;
        }
        .btn-delete-force:hover {
            background: #c0392b !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        
        /* ============================================
           TABLE STYLING FIXES
           ============================================ */
        .table-admin-wrap {
            background: white !important;
            border-radius: 8px !important;
            overflow: auto !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
        }
        .table-admin-wrap table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 14px !important;
            min-width: 900px !important;
        }
        .table-admin-wrap table thead {
            background: #1a1a2e !important;
            color: white !important;
        }
        .table-admin-wrap table thead th {
            padding: 12px 15px !important;
            text-align: left !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            font-weight: 600 !important;
        }
        .table-admin-wrap table tbody tr {
            border-bottom: 1px solid #eee !important;
        }
        .table-admin-wrap table tbody tr:hover {
            background: #f8faff !important;
        }
        .table-admin-wrap table tbody td {
            padding: 12px 15px !important;
            vertical-align: middle !important;
        }
        
        /* Status Badge */
        .status-badge-admin {
            padding: 3px 14px !important;
            border-radius: 20px !important;
            font-size: 11px !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            display: inline-block !important;
        }
        .status-badge-admin.active {
            background: #d4edda !important;
            color: #155724 !important;
            border: 1px solid #c3e6cb !important;
        }
        .status-badge-admin.inactive {
            background: #f8d7da !important;
            color: #721c24 !important;
            border: 1px solid #f5c6cb !important;
        }
        
        /* Header Box */
        .header-box-admin {
            background: white !important;
            padding: 15px 20px !important;
            border-radius: 8px !important;
            margin-bottom: 20px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
        }
        .header-box-admin h3 {
            margin: 0 !important;
            font-size: 20px !important;
        }
        .header-box-admin .count-badge-admin {
            background: #667eea !important;
            color: white !important;
            padding: 2px 12px !important;
            border-radius: 30px !important;
            font-size: 14px !important;
            margin-left: 8px !important;
        }
        .btn-add-admin {
            background: #2ecc71 !important;
            color: white !important;
            padding: 8px 22px !important;
            border-radius: 30px !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        .btn-add-admin:hover {
            background: #27ae60 !important;
            color: white !important;
        }
        
        /* Alert */
        .alert-admin {
            padding: 10px 20px !important;
            border-radius: 8px !important;
            margin-bottom: 15px !important;
        }
        .alert-admin.success {
            background: #d4edda !important;
            color: #155724 !important;
            border: 1px solid #c3e6cb !important;
        }
        
        /* ===== RESPONSIVE FIXES ===== */
        @media (max-width: 768px) {
            header.background-white {
                padding: 0 10px !important;
            }
            .header-box-admin {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            .btn-add-admin {
                width: 100% !important;
                justify-content: center !important;
                margin-top: 10px !important;
            }
        }
    </style>
    
    <!-- jquery library  -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- fontawesome  -->
    <script defer src="../../../use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <!-- Fix for page jumps and dropdowns -->
    <script>
        $(document).ready(function() {
            // 1. Prevent page jump for all links with href="#"
            $('a[href="#"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
            
            // 2. Fix for dropdown links - prevent jump and toggle
            $('.has-dropdown > a').on('click', function(e) {
                var href = $(this).attr('href');
                if(href === '#' || href === '') {
                    e.preventDefault();
                    $(this).parent().toggleClass('active');
                }
            });
            
            // 3. Fix for sidebar links - prevent jump for # links
            $('.navbar-sidenav .nav-link').on('click', function(e) {
                var href = $(this).attr('href');
                if(href === '#' || href === '' || href === undefined) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
            
            // 4. Fix for mobile toggle
            $('.mobile-toggle').on('click', function(e) {
                e.preventDefault();
                $('.nav-menu').toggleClass('show');
            });
        });
    </script>

</head>

<body>

    <!-- ======================================================
        HEADER - TOP BAR (PAGES & DASHBOARD REMOVED)
        ====================================================== -->
    <header class="background-white box-shadow fixed-top z-index-99">
        <nav class="container-fluid header-in">
            <div class="row">
                <!-- Logo -->
                <div class="col-xl-2 col-lg-2">
                    <a id="logo" href="index.php" class="d-inline-block margin-tb-15px">
                        <img src="assets/img/logo-5.png" alt="Doctorpedia">
                    </a>
                    <a class="mobile-toggle padding-13px background-main-color" href="#">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                
                <!-- ============================================
                    NAVIGATION - PAGES & DASHBOARD REMOVED
                    ============================================ -->
                <div class="col-xl-6 col-lg-8 position-inherit">
                    <ul id="menu-main" class="nav-menu float-lg-right link-padding-tb-20px">
                        <!-- Home -->
                        <li><a href="index.php">Home</a></li>
                        
                        <!-- Listings -->
                        <li class="has-dropdown">
                            <a href="#">Listings</a>
                            <ul class="sub-menu">
                                <li><a href="laboratories.php"><i class="fas fa-flask"></i> Laboratories</a></li>
                                <li><a href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                                <li><a href="pharmacies.php"><i class="fas fa-hospital"></i> Pharmacies</a></li>
                                <li><a href="medicines.php"><i class="fas fa-pills"></i> Medicines</a></li>
                                <li><a href="single-listings.php"><i class="fas fa-list"></i> Single Listings</a></li>
                            </ul>
                        </li>
                        
                        <!-- ========================================
                            DASHBOARD MENU - COMPLETELY REMOVED
                            ======================================== -->
                        <!-- Dashboard menu item removed -->
                        
                        <!-- Health Blogs -->
                        <li><a href="blogs.php">Health Blogs</a></li>
                        
                        <!-- ========================================
                            PAGES MENU - COMPLETELY REMOVED
                            ======================================== -->
                        <!-- Pages menu item removed -->
                        
                        <!-- Contact Us -->
                        <li><a href="page-contact-us.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <!-- ============================================
                    RIGHT SIDE - DASHBOARD BUTTON REMOVED
                    ============================================ -->
                <div class="col-xl-4 d-none d-xl-block">
                    <hr class="margin-bottom-0px d-block d-sm-none">
                    
                    <!-- Add List Button -->
                    <a href="add-listing.php" class="btn btn-sm border-radius-30 margin-tb-15px text-white background-second-color box-shadow float-right padding-lr-25px margin-left-30px">
                        <i class="fas fa-plus-circle margin-right-7px"></i> Add list
                    </a>
                    
                    <!-- User Avatar -->
                    <div class="nav-item dropdown float-left">
                        <a href="#" class="margin-top-15px d-inline-block text-grey-3 margin-right-15px">
                            <img src="images/uploads/no.png" class="height-30px border-radius-30" alt="">
                        </a>
                    </div>
                    
                    <!-- ========================================
                        DASHBOARD LINK - COMPLETELY REMOVED
                        ======================================== -->
                    <!-- Dashboard link removed -->
                    
                    <!-- Logout -->
                    <div class="nav-item float-left">
                        <a href="logout.php" class="nav-link margin-top-10px">
                            <div class="text-grey-3"><i class="fa fa-fw fa-sign-out-alt"></i>Logout</div>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- // Header  -->

    <!-- ======================================================
        SIDEBAR - UPDATED WITH MEDICINES
        ====================================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark z-index-9 fixed-top" id="mainNav">
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav background-main-color admin-nav" id="admin-nav">
                <li class="nav-item">
                    <span class="nav-title-text">MAIN</span>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="fas fa-fw fa-home"></i><span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Listings">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-add-listings.php') ? 'active' : ''; ?>" href="dashboard-add-listings.php">
                        <i class="fa fa-fw fa-table"></i>
                        <span class="nav-link-text">My Listings</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Favorites">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-my-favorites.php') ? 'active' : ''; ?>" href="dashboard-my-favorites.php">
                        <i class="fa fa-fw fa-heart"></i>
                        <span class="nav-link-text">My Favorites</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reviews">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-reviews.php') ? 'active' : ''; ?>" href="dashboard-reviews.php">
                        <i class="fa fa-fw fa-star"></i>
                        <span class="nav-link-text">Reviews</span>
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-title-text">EXAMPLE PAGES</span>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Appointments">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-appointments.php') ? 'active' : ''; ?>" href="dashboard-appointments.php">
                        <i class="far fa-fw fa-bookmark"></i>
                        <span class="nav-link-text">Appointments</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lab Bookings">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-lab-bookings.php') ? 'active' : ''; ?>" href="dashboard-lab-bookings.php">
                        <i class="fas fa-flask"></i>
                        <span class="nav-link-text">Lab Bookings</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Pharmacies">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-pharmacies.php') ? 'active' : ''; ?>" href="dashboard-pharmacies.php">
                        <i class="fas fa-hospital"></i>
                        <span class="nav-link-text">Pharmacies</span>
                    </a>
                </li>
                <!-- ===== MEDICINES LINK ADDED ===== -->
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Medicines">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-medicines.php') ? 'active' : ''; ?>" href="dashboard-medicines.php">
                        <i class="fas fa-pills"></i>
                        <span class="nav-link-text">Medicines</span>
                    </a>
                </li>
                <!-- ===== END MEDICINES LINK ===== -->
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add Listing">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'add-listing.php') ? 'active' : ''; ?>" href="add-listing.php">
                        <i class="fa fa-fw fa-plus-circle"></i>
                        <span class="nav-link-text">Add Listing</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Packages">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard-packages.php') ? 'active' : ''; ?>" href="dashboard-packages.php">
                        <i class="far fa-fw fa-list-alt"></i>
                        <span class="nav-link-text">Packages</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Manage Timing">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage-timing.php') ? 'active' : ''; ?>" href="manage-timing.php">
                        <i class="fas fa-clock"></i>
                        <span class="nav-link-text">Manage Timing</span>
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-title-text">USER AREA</span>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Profile">
                    <a class="nav-link" href="#">
                        <i class="fa fa-fw fa-user-circle"></i>
                        <span class="nav-link-text">My Profile</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sign Out">
                    <a class="nav-link" href="logout.php">
                        <i class="fa fa-fw fa-sign-out-alt"></i>
                        <span class="nav-link-text">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navigation Sidebar -->