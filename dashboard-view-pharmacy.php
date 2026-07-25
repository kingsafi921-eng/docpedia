<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

$id = isset($_GET['id']) ? mysqli_real_escape_string($connect, $_GET['id']) : 0;
$query = "SELECT * FROM pharmacies WHERE pharmacy_id = '$id'";
$result = mysqli_query($connect, $query);
$pharmacy = mysqli_fetch_assoc($result);

if(!$pharmacy) {
    $_SESSION['error'] = "Pharmacy not found!";
    header("Location: dashboard-pharmacies.php");
    exit();
}
?>

<style>
    .view-pharmacy-page {
        margin-top: 100px;
        padding: 30px;
        background: #f4f6f9;
        min-height: 100vh;
    }
    .view-pharmacy-page .detail-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        max-width: 700px;
        margin: 0 auto;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .view-pharmacy-page .detail-card h4 {
        margin-top: 0;
        color: #1a1a2e;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 15px;
    }
    .view-pharmacy-page .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }
    .view-pharmacy-page .detail-row .label {
        font-weight: 600;
        color: #1a1a2e;
        width: 120px;
        flex-shrink: 0;
    }
    .view-pharmacy-page .detail-row .value {
        color: #4a5568;
        flex: 1;
    }
    .view-pharmacy-page .detail-row .value .status-badge {
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .view-pharmacy-page .detail-row .value .status-badge.active {
        background: #d4edda;
        color: #155724;
    }
    .view-pharmacy-page .detail-row .value .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .view-pharmacy-page .detail-img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 10px;
        border: 2px solid #e8ecf1;
    }
    .view-pharmacy-page .btn-back {
        background: #667eea;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: 0.3s;
        margin-top: 15px;
    }
    .view-pharmacy-page .btn-back:hover {
        background: #5a67d8;
        transform: translateY(-2px);
        color: white;
    }
    @media (max-width: 768px) {
        .view-pharmacy-page .detail-row {
            flex-direction: column;
        }
        .view-pharmacy-page .detail-row .label {
            width: 100%;
            margin-bottom: 3px;
        }
    }
</style>

<div class="view-pharmacy-page">
    <div class="container-fluid">
        <div class="detail-card">
            <h4><i class="fas fa-eye" style="color:#2ecc71;"></i> Pharmacy Details</h4>
            
            <?php if(!empty($pharmacy['img']) && file_exists("images/uploads/".$pharmacy['img'])): ?>
                <div style="text-align:center;margin-bottom:20px;">
                    <img src="images/uploads/<?php echo $pharmacy['img']; ?>" class="detail-img">
                </div>
            <?php endif; ?>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-store"></i> Name</div>
                <div class="value"><strong><?php echo htmlspecialchars($pharmacy['pharmacy_name']); ?></strong></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-envelope"></i> Email</div>
                <div class="value"><?php echo htmlspecialchars($pharmacy['email'] ?? '-'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-phone"></i> Phone</div>
                <div class="value"><?php echo htmlspecialchars($pharmacy['phone'] ?? '-'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-map-marker-alt"></i> Address</div>
                <div class="value"><?php echo htmlspecialchars($pharmacy['address'] ?? '-'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-info-circle"></i> About</div>
                <div class="value"><?php echo nl2br(htmlspecialchars($pharmacy['about'] ?? '-')); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-globe"></i> Website</div>
                <div class="value"><?php echo htmlspecialchars($pharmacy['website'] ?? '-'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-circle"></i> Status</div>
                <div class="value">
                    <span class="status-badge <?php echo $pharmacy['status'] ?? 'active'; ?>">
                        <?php echo ucfirst($pharmacy['status'] ?? 'Active'); ?>
                    </span>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="label"><i class="fas fa-calendar"></i> Created</div>
                <div class="value"><?php echo date('d M Y, h:i A', strtotime($pharmacy['created_at'] ?? 'now')); ?></div>
            </div>
            
            <div style="margin-top:20px;display:flex;gap:10px;flex-wrap:wrap;">
                <a href="dashboard-edit-pharmacy.php?id=<?php echo $pharmacy['pharmacy_id']; ?>" class="btn-back" style="background:#3498db;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="dashboard-pharmacies.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>