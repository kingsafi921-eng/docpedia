<!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="keywords" content="directory, doctor, doctor directory, Health directory, listing, map, medical, medical directory, professional directory, reservation, reviews ,<?php echo $keyword; ?> ">
    <meta name="description" content="Health Care & Medical Services Directory">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800%7CPoppins:300i,300,400,700,400i,500%7CDancing+Script:700" rel="stylesheet">
    <!-- animate -->
    <link rel="stylesheet" href="assets/css/animate.css" />
    <!-- owl Carousel assets -->
    <link href="assets/css/owl.carousel.css" rel="stylesheet">
    <link href="assets/css/owl.theme.css" rel="stylesheet">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- hover animation -->
    <link rel="stylesheet" type="text/css" href="assets/tableCss/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/tableCss/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/tableCss/vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/tableCss/css/util.css">
    <link rel="stylesheet" type="text/css" href="assets/tableCss/css/main.css">
    <link rel="stylesheet" href="assets/css/hover-min.css">
    <!-- flag icon -->
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <!-- main style -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- my style -->
    <link rel="stylesheet" href="style.css">
    <!-- colors -->
    <link rel="stylesheet" href="assets/css/colors/main.css">
    <!-- elegant icon -->
    <link rel="stylesheet" href="assets/css/elegant_icon.css">

    <!-- jquery library  -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <!-- Fix for dropdown jumps -->
    <script>
    $(document).ready(function() {
        // Prevent default for dropdown links
        $('.has-dropdown > a').on('click', function(e) {
            e.preventDefault();
            $(this).parent().toggleClass('active');
        });
    });
    </script>
</head>

<body>

    <header class="background-white box-shadow">
        <div>
            <div class="container header-in">
                <div class="row">
                    <div class="col-lg-2 col-md-12">
                        <a id="logo" href="index.php" class="d-inline-block margin-tb-15px"><img src="assets/img/logo-5.png" alt=""></a>
                        <a class="mobile-toggle padding-13px background-main-color" href="#"><i class="fas fa-bars"></i></a>
                    </div>
                    <div class="col-lg-7 col-md-12 position-inherit">
                        <ul id="menu-main" class="nav-menu float-lg-right link-padding-tb-20px">
                            <li><a href="index.php">Home</a></li>
                            <li class="has-dropdown">
                                <a href="javascript:void(0)">Listings</a>
                                <ul class="sub-menu">
                                    <li><a href="laboratories.php"><i class="fas fa-flask"></i> Laboratories</a></li>
                                    <li><a href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                                    <li><a href="medicines.php"><i class="fas fa-pills"></i> Medicines</a></li>
                                    <li><a href="pharmacies.php"><i class="fas fa-hospital"></i> Pharmacies</a></li>
                                    <li><a href="single-listings.php"><i class="fas fa-list"></i> Single Listings</a></li>
                                </ul>
                            </li>
                            <li><a href="blogs.php">Health Blogs</a></li>
                            <!-- PAGES REMOVED -->
                            <li><a href="page-contact-us.php">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <hr class="margin-bottom-0px d-block d-sm-none">
                        <?php if(isset($_SESSION['admin_id']) && $_SESSION['role'] == 'admin'): ?>
                            <!-- DASHBOARD REMOVED -->
                            <a href="logout.php" class="margin-tb-20px d-inline-block text-up-small float-left float-lg-right" style="color: #EF4444; font-weight: 600;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="admin_login.php" class="margin-tb-20px d-inline-block text-up-small float-left float-lg-right" style="color: #667eea; font-weight: 600;">
                                <i class="fas fa-user-shield"></i> Admin Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>