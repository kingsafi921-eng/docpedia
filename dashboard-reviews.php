<?php 
session_start();
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
include('dashboard-header.php');
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px; background: #f4f6f9; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-star" style="color: #ffc107;"></i> Reviews</h2>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                    <div class="card-body" style="padding: 50px; text-align: center;">
                        <i class="fas fa-star" style="font-size: 64px; color: #ddd;"></i>
                        <h4 style="color: #666; margin-top: 20px;">No Reviews Yet</h4>
                        <p style="color: #999;">No reviews have been submitted yet.</p>
                        <a href="doctor.php" class="btn btn-primary" style="background: #667eea; border: none; border-radius: 10px; padding: 10px 30px;">
                            <i class="fas fa-search"></i> Browse Doctors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>