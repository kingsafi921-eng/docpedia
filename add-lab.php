<?php 
$title = 'Add Lab';
// session_start() hata diya - already started in dashboard-header.php
include("connect.php");
include("function.php");

if(!isset($_SESSION['admin_id'])) {
    header('location:page-login.php');
    exit();
}

if(!(isset($_SESSION['admin_id']) && $_SESSION['role'] == 1)) {
    header("location:index.php");
    exit();
}

$user2 = fetch($connect, $_SESSION['admin_id']);
$user = fetch($connect, $_SESSION['admin_id']);

include('dashboard-header.php');
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px;">
    <div class="container-fluid overflow-hidden">
        <div class="row margin-tb-90px margin-lr-10px sm-mrl-0px">
            <!-- Page Title -->
            <div id="page-title" class="padding-30px background-white full-width">
                <div class="container">
                    <ol class="breadcrumb opacity-5">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li class="active">Add Lab</li>
                    </ol>
                    <h1 class="font-weight-300">Add Lab</h1>
                </div>
            </div>
            <!-- // Page Title -->

            <div class="margin-tb-45px full-width">
                <div class="padding-30px background-white border-radius-20 box-shadow">
                    <h3><i class="far fa-list-alt margin-right-10px text-main-color"></i> Basic Informations </h3>
                    
                    <?php if(isset($_GET['success']) && $_GET['success'] == 0) { ?>
                        <div class="alert alert-danger" role="alert">Data Insert Failed!</div>
                    <?php } else if(isset($_GET['success']) && $_GET['success'] == 1) { ?>
                        <div class="alert alert-success" role="alert">Lab Added Successfully!</div>
                    <?php } ?>
                    
                    <hr>
                    <form action="add-lab-action.php" method="post" enctype="multipart/form-data">
                        <div class="form-group margin-bottom-20px">
                            <label><i class="far fa-list-alt margin-right-10px"></i> Lab Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Lab Name" required>
                        </div>
                        
                        <div class="form-group margin-bottom-20px">
                            <div class="row">
                                <div class="col-md-6 margin-bottom-20px">
                                    <label><i class="far fa-envelope-open margin-right-10px"></i> Email</label>
                                    <input type="email" class="form-control form-control-sm" placeholder="info@yourname.com" name="email">
                                </div>
                                <div class="col-md-6 margin-bottom-20px">
                                    <label><i class="far fa-list-alt margin-right-10px"></i> Address</label>
                                    <input type="text" class="form-control form-control-sm" name="address" placeholder="Address">
                                </div>
                            </div>
                        </div>

                        <div class="form-group margin-bottom-20px">
                            <div class="row">
                                <div class="col-md-6 margin-bottom-20px">
                                    <label><i class="fas fa-mobile-alt margin-right-10px"></i> Phone</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Phone Number" name="number">
                                </div>
                                <div class="col-md-6 margin-bottom-20px">
                                    <label><i class="far fa-envelope-open margin-right-10px"></i> About</label>
                                    <textarea class="form-control" placeholder="About Lab" name="about" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 margin-bottom-20px">
                                <label><i class="far fa-envelope-open margin-right-10px"></i> Website</label>
                                <input type="text" class="form-control form-control-sm" placeholder="www.example.com" name="website">
                            </div>
                        </div>
                        <br>

                        <button type="submit" name="add" class="btn btn-lg border-2 btn-primary btn-block border-radius-15 padding-15px box-shadow">
                            <i class="fas fa-plus-circle"></i> Add Lab
                        </button>
                        <a href="dashboard.php?page=labs" class="btn btn-lg btn-secondary border-radius-15 padding-15px">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("dashboard-footer.php"); ?>