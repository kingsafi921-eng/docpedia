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
                    <h2><i class="fas fa-box" style="color: #17a2b8;"></i> Packages</h2>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                            <div class="card-body text-center" style="padding: 30px;">
                                <h3 style="color: #667eea;">Basic</h3>
                                <h2 style="font-weight: bold;">$0</h2>
                                <p style="color: #666;">Free Plan</p>
                                <ul style="list-style: none; padding: 0; text-align: left;">
                                    <li><i class="fas fa-check text-success"></i> 5 Listings</li>
                                    <li><i class="fas fa-check text-success"></i> Basic Support</li>
                                    <li><i class="fas fa-times text-danger"></i> Featured Listing</li>
                                </ul>
                                <button class="btn btn-primary" style="background: #667eea; border: none; border-radius: 10px; width: 100%;">Current Plan</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); border-top: 4px solid #667eea;">
                            <div class="card-body text-center" style="padding: 30px;">
                                <span class="badge bg-primary" style="position: relative; top: -10px;">Popular</span>
                                <h3 style="color: #667eea;">Pro</h3>
                                <h2 style="font-weight: bold;">$29</h2>
                                <p style="color: #666;">Monthly</p>
                                <ul style="list-style: none; padding: 0; text-align: left;">
                                    <li><i class="fas fa-check text-success"></i> 50 Listings</li>
                                    <li><i class="fas fa-check text-success"></i> Priority Support</li>
                                    <li><i class="fas fa-check text-success"></i> Featured Listing</li>
                                    <li><i class="fas fa-check text-success"></i> Analytics</li>
                                </ul>
                                <button class="btn btn-primary" style="background: #667eea; border: none; border-radius: 10px; width: 100%;">Upgrade Now</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                            <div class="card-body text-center" style="padding: 30px;">
                                <h3 style="color: #667eea;">Business</h3>
                                <h2 style="font-weight: bold;">$49</h2>
                                <p style="color: #666;">Monthly</p>
                                <ul style="list-style: none; padding: 0; text-align: left;">
                                    <li><i class="fas fa-check text-success"></i> Unlimited Listings</li>
                                    <li><i class="fas fa-check text-success"></i> 24/7 Support</li>
                                    <li><i class="fas fa-check text-success"></i> Featured Listing</li>
                                    <li><i class="fas fa-check text-success"></i> Advanced Analytics</li>
                                    <li><i class="fas fa-check text-success"></i> API Access</li>
                                </ul>
                                <button class="btn btn-outline-primary" style="border-color: #667eea; color: #667eea; border-radius: 10px; width: 100%;">Contact Sales</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>