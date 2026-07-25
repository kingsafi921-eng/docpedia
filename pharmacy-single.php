<?php
$title = 'Pharmacy Details - Doctorpedia';
include('header.php');
include('connect.php');

// Get pharmacy ID from URL
$pharmacy_id = isset($_GET['hid']) ? intval($_GET['hid']) : 0;

if($pharmacy_id > 0) {
    $query = "SELECT * FROM pharmacies WHERE pharmacy_id = $pharmacy_id AND status = 'active'";
    $result = mysqli_query($connect, $query);
    $pharmacy = mysqli_fetch_assoc($result);
    
    if(!$pharmacy) {
        header('Location: pharmacies.php');
        exit;
    }
} else {
    header('Location: pharmacies.php');
    exit;
}
?>

<style>
    /* ========================================
       PHARMACY DETAIL PAGE
    ======================================== */
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #2ecc71;
        --dark: #1a1a2e;
        --gray: #7f8c8d;
        --gray-light: #e8ecf1;
        --bg: #f0f2f5;
    }

    .pharmacy-detail-hero {
        background: linear-gradient(135deg, rgba(15, 15, 35, 0.88) 0%, rgba(30, 30, 80, 0.82) 50%, rgba(60, 40, 120, 0.78) 100%),
                    url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=1920&q=80');
        background-size: cover;
        background-position: center;
        padding: 130px 0 50px;
        color: white;
        position: relative;
    }

    .pharmacy-detail-hero .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 20px;
    }

    .pharmacy-detail-hero .breadcrumb a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        transition: color 0.3s;
    }

    .pharmacy-detail-hero .breadcrumb a:hover {
        color: white;
    }

    .pharmacy-detail-hero .breadcrumb .active {
        color: white;
    }

    .pharmacy-detail-hero .pharmacy-name {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .pharmacy-detail-hero .pharmacy-address {
        font-size: 16px;
        opacity: 0.85;
        margin-bottom: 15px;
    }

    .pharmacy-detail-hero .pharmacy-address i {
        color: #a78bfa;
        margin-right: 8px;
    }

    .pharmacy-detail-hero .status-badge {
        display: inline-block;
        padding: 6px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pharmacy-detail-hero .status-badge.open {
        background: rgba(46, 204, 113, 0.25);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.3);
    }

    .pharmacy-detail-hero .status-badge.closed {
        background: rgba(231, 76, 60, 0.25);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .pharmacy-detail-section {
        padding: 50px 0 80px;
        background: var(--bg);
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.06);
        margin-bottom: 30px;
    }

    .detail-card .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f2f5;
    }

    .detail-card .card-title i {
        color: var(--primary);
        margin-right: 10px;
    }

    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        font-weight: 600;
        color: var(--dark);
        min-width: 140px;
        font-size: 14px;
    }

    .info-row .value {
        color: #555;
        font-size: 14px;
        flex: 1;
    }

    .info-row .value i {
        color: var(--primary);
        margin-right: 8px;
        width: 18px;
    }

    .info-row .value a {
        color: var(--primary);
        text-decoration: none;
    }

    .info-row .value a:hover {
        text-decoration: underline;
    }

    .pharmacy-image-large {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        color: white;
    }

    .pharmacy-image-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102,126,234,0.25);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.35);
        color: white;
        text-decoration: none;
    }

    .btn-success-custom {
        background: var(--success);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(46,204,113,0.25);
    }

    .btn-success-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(46,204,113,0.35);
        color: white;
        text-decoration: none;
    }

    .btn-outline-custom {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
        padding: 10px 28px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-outline-custom:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
        text-decoration: none;
    }

    .services-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .services-list .service-tag {
        background: linear-gradient(135deg, #f0f2f5 0%, #e8ecf1 100%);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        color: #555;
        font-weight: 500;
    }

    .services-list .service-tag i {
        color: var(--primary);
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .pharmacy-detail-hero {
            padding: 100px 0 30px;
        }
        .pharmacy-detail-hero .pharmacy-name {
            font-size: 24px;
        }
        .detail-card {
            padding: 20px;
        }
        .info-row {
            flex-direction: column;
            padding: 10px 0;
        }
        .info-row .label {
            min-width: auto;
            margin-bottom: 4px;
            font-size: 12px;
            color: var(--gray);
        }
        .pharmacy-image-large {
            height: 200px;
        }
        .action-buttons {
            flex-direction: column;
        }
        .action-buttons .btn-primary-custom,
        .action-buttons .btn-success-custom,
        .action-buttons .btn-outline-custom {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .pharmacy-detail-hero .pharmacy-name {
            font-size: 20px;
        }
        .pharmacy-detail-hero .pharmacy-address {
            font-size: 13px;
        }
        .pharmacy-image-large {
            height: 150px;
            font-size: 50px;
        }
    }
</style>

<!-- ===== HERO / HEADER SECTION ===== -->
<section class="pharmacy-detail-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="pharmacies.php">Pharmacies</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($pharmacy['pharmacy_name']); ?></li>
                    </ol>
                </nav>

                <h1 class="pharmacy-name">
                    <i class="fas fa-store" style="color: #a78bfa;"></i>
                    <?php echo htmlspecialchars($pharmacy['pharmacy_name']); ?>
                </h1>
                
                <p class="pharmacy-address">
                    <i class="fas fa-location-dot"></i>
                    <?php echo htmlspecialchars($pharmacy['address'] ?? 'Address not available'); ?>
                </p>

                <span class="status-badge <?php echo ($pharmacy['status'] == 'active') ? 'open' : 'closed'; ?>">
                    <i class="fas fa-<?php echo ($pharmacy['status'] == 'active') ? 'check-circle' : 'times-circle'; ?>"></i>
                    <?php echo ($pharmacy['status'] == 'active') ? 'Open Now' : 'Closed'; ?>
                </span>
                
                <?php if(!empty($pharmacy['timing'])): ?>
                    <span class="status-badge" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.1); margin-left: 10px;">
                        <i class="far fa-clock"></i> <?php echo htmlspecialchars($pharmacy['timing']); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===== DETAIL SECTION ===== -->
<section class="pharmacy-detail-section">
    <div class="container">
        <div class="row">
            <!-- Left Column: Main Info -->
            <div class="col-lg-8">
                <div class="detail-card">
                    <h4 class="card-title"><i class="fas fa-info-circle"></i> About Pharmacy</h4>
                    
                    <?php if(!empty($pharmacy['about'])): ?>
                        <p style="line-height: 1.8; color: #555; font-size: 15px;">
                            <?php echo nl2br(htmlspecialchars($pharmacy['about'])); ?>
                        </p>
                    <?php else: ?>
                        <p style="color: #999; font-style: italic;">No description available.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h4 class="card-title"><i class="fas fa-list-ul"></i> Contact & Details</h4>
                    
                    <div class="info-row">
                        <span class="label"><i class="fas fa-phone" style="color: var(--primary); margin-right: 8px;"></i> Phone</span>
                        <span class="value">
                            <?php if(!empty($pharmacy['phone'])): ?>
                                <a href="tel:<?php echo $pharmacy['phone']; ?>">
                                    <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($pharmacy['phone']); ?>
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">Not available</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label"><i class="fas fa-envelope" style="color: var(--primary); margin-right: 8px;"></i> Email</span>
                        <span class="value">
                            <?php if(!empty($pharmacy['email'])): ?>
                                <a href="mailto:<?php echo $pharmacy['email']; ?>">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($pharmacy['email']); ?>
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">Not available</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label"><i class="fas fa-location-dot" style="color: var(--primary); margin-right: 8px;"></i> Address</span>
                        <span class="value">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($pharmacy['address'] ?? 'Address not available'); ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label"><i class="far fa-clock" style="color: var(--primary); margin-right: 8px;"></i> Timing</span>
                        <span class="value">
                            <i class="far fa-clock"></i>
                            <?php echo htmlspecialchars($pharmacy['timing'] ?? '9:00 AM - 11:00 PM'); ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label"><i class="fas fa-store" style="color: var(--primary); margin-right: 8px;"></i> Status</span>
                        <span class="value">
                            <?php if($pharmacy['status'] == 'active'): ?>
                                <span style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Active / Open</span>
                            <?php else: ?>
                                <span style="color: #e74c3c;"><i class="fas fa-times-circle"></i> Inactive / Closed</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <?php if(!empty($pharmacy['services'])): ?>
                <div class="detail-card">
                    <h4 class="card-title"><i class="fas fa-hand-holding-heart"></i> Services Offered</h4>
                    <div class="services-list">
                        <?php 
                        $services = explode(',', $pharmacy['services']);
                        foreach($services as $service): 
                            $service = trim($service);
                            if(!empty($service)):
                        ?>
                            <span class="service-tag">
                                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($service); ?>
                            </span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Image & Actions -->
            <div class="col-lg-4">
                <div class="detail-card">
                    <h4 class="card-title"><i class="fas fa-image"></i> Pharmacy Image</h4>
                    
                    <div class="pharmacy-image-large">
                        <?php if(!empty($pharmacy['img']) && file_exists("images/uploads/".$pharmacy['img'])): ?>
                            <img src="images/uploads/<?php echo $pharmacy['img']; ?>" alt="<?php echo $pharmacy['pharmacy_name']; ?>">
                        <?php else: ?>
                            <i class="fas fa-store"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-card">
                    <h4 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h4>
                    
                    <div class="action-buttons">
                        <?php if(!empty($pharmacy['phone'])): ?>
                            <a href="tel:<?php echo $pharmacy['phone']; ?>" class="btn-success-custom">
                                <i class="fas fa-phone-alt"></i> Call Now
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($pharmacy['email'])): ?>
                            <a href="mailto:<?php echo $pharmacy['email']; ?>" class="btn-primary-custom">
                                <i class="fas fa-envelope"></i> Email Pharmacy
                            </a>
                        <?php endif; ?>
                        
                        <a href="pharmacies.php" class="btn-outline-custom">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <?php if(!empty($pharmacy['timing'])): ?>
                <div class="detail-card" style="background: linear-gradient(135deg, #f8faff 0%, #f0f2f5 100%);">
                    <h4 class="card-title"><i class="far fa-clock"></i> Business Hours</h4>
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="font-size: 20px; font-weight: 700; color: var(--primary);">
                            <?php echo htmlspecialchars($pharmacy['timing']); ?>
                        </div>
                        <div style="font-size: 12px; color: #999; margin-top: 5px;">
                            <i class="fas fa-<?php echo ($pharmacy['status'] == 'active') ? 'check-circle' : 'times-circle'; ?>" style="color: <?php echo ($pharmacy['status'] == 'active') ? '#2ecc71' : '#e74c3c'; ?>;"></i>
                            <?php echo ($pharmacy['status'] == 'active') ? 'We are open now' : 'Currently closed'; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>