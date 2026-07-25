<?php
$title = 'Medicine Details - Doctorpedia';
include('header.php');
include('connect.php');

$medicine_id = isset($_GET['hid']) ? intval($_GET['hid']) : 0;

if($medicine_id > 0) {
    $query = "SELECT * FROM medicines WHERE medicine_id = '$medicine_id' AND status = 'active'";
    $result = mysqli_query($connect, $query);
    $medicine = mysqli_fetch_assoc($result);
}

if(!$medicine) {
    header('Location: medicines.php');
    exit();
}

// Get related medicines (same category)
$related_query = "SELECT * FROM medicines WHERE category = '{$medicine['category']}' AND medicine_id != '$medicine_id' AND status = 'active' LIMIT 4";
$related_result = mysqli_query($connect, $related_query);
?>

<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --dark: #1a1a2e;
        --gray: #7f8c8d;
    }

    /* ===== PREMIUM DETAIL SECTION ===== */
    .medicine-detail-section {
        padding: 30px 0 50px 0;
        background: #f0f2f5;
        min-height: 100vh;
    }

    /* ===== HERO BANNER ===== */
    .detail-hero-premium {
        background: linear-gradient(135deg, rgba(15, 15, 35, 0.88) 0%, rgba(30, 30, 80, 0.82) 50%, rgba(60, 40, 120, 0.75) 100%),
                    url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=1920&q=80');
        background-size: cover;
        background-position: center;
        padding: 110px 0 40px 0;
        color: white;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid rgba(102,126,234,0.3);
    }
    .detail-hero-premium .floating-pills {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 1;
    }
    .detail-hero-premium .floating-pills .pill {
        position: absolute;
        font-size: 30px;
        opacity: 0.06;
        color: white;
        animation: floatPill 20s infinite ease-in-out;
    }
    .detail-hero-premium .floating-pills .pill:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; font-size: 40px; }
    .detail-hero-premium .floating-pills .pill:nth-child(2) { top: 20%; right: 8%; animation-delay: 3s; font-size: 35px; }
    .detail-hero-premium .floating-pills .pill:nth-child(3) { bottom: 30%; left: 12%; animation-delay: 6s; font-size: 40px; }
    .detail-hero-premium .floating-pills .pill:nth-child(4) { bottom: 20%; right: 5%; animation-delay: 9s; font-size: 35px; }
    .detail-hero-premium .floating-pills .pill:nth-child(5) { top: 50%; left: 50%; animation-delay: 12s; font-size: 25px; }

    @keyframes floatPill {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(25px, -30px) rotate(10deg); }
        50% { transform: translate(-15px, -60px) rotate(-5deg); }
        75% { transform: translate(35px, -20px) rotate(15deg); }
    }

    .detail-hero-premium .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .detail-hero-premium .hero-content .hero-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 45px;
        border: 2px solid rgba(255,255,255,0.15);
        flex-shrink: 0;
    }
    .detail-hero-premium .hero-content .hero-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .detail-hero-premium .hero-content .hero-text h1 {
        font-size: 32px;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 20px rgba(0,0,0,0.3);
    }
    .detail-hero-premium .hero-content .hero-text h1 .highlight {
        background: linear-gradient(135deg, #667eea 0%, #a78bfa 50%, #f093fb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .detail-hero-premium .hero-content .hero-text .subtitle {
        font-size: 16px;
        opacity: 0.85;
        margin: 5px 0 0;
    }
    .detail-hero-premium .hero-content .hero-text .subtitle i {
        color: #a78bfa;
    }
    .detail-hero-premium .hero-content .hero-stats {
        display: flex;
        gap: 20px;
        margin-left: auto;
        flex-wrap: wrap;
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(12px);
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        text-align: center;
        min-width: 80px;
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item .number {
        font-size: 20px;
        font-weight: 700;
        display: block;
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item .label {
        font-size: 10px;
        opacity: 0.7;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        color: rgba(255,255,255,0.7);
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item .stock-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-top: 4px;
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item .stock-badge.in-stock {
        background: rgba(46,204,113,0.3);
        color: #2ecc71;
    }
    .detail-hero-premium .hero-content .hero-stats .stat-item .stock-badge.out-of-stock {
        background: rgba(231,76,60,0.3);
        color: #e74c3c;
    }

    /* ===== BACK BUTTON ===== */
    .btn-back-premium {
        background: white;
        color: var(--dark);
        border: none;
        border-radius: 12px;
        padding: 10px 25px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    .btn-back-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.2);
        color: var(--primary);
    }

    /* ===== MAIN DETAIL CARD ===== */
    .medicine-detail-card-premium {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 35px rgba(0,0,0,0.06);
        padding: 35px;
        margin-bottom: 25px;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .medicine-detail-card-premium .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .medicine-detail-card-premium .section-title i {
        color: var(--primary);
    }
    .detail-row-premium {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
        align-items: center;
        transition: background 0.3s;
    }
    .detail-row-premium:hover {
        background: #f8f9fc;
        padding-left: 10px;
        border-radius: 8px;
    }
    .detail-row-premium:last-child {
        border-bottom: none;
    }
    .detail-row-premium .label {
        font-weight: 600;
        width: 140px;
        color: #555;
        font-size: 14px;
    }
    .detail-row-premium .label i {
        color: var(--primary);
        width: 22px;
    }
    .detail-row-premium .value {
        flex: 1;
        color: var(--dark);
        font-size: 14px;
    }
    .detail-row-premium .value .price-tag {
        font-size: 26px;
        font-weight: 700;
        color: var(--success);
    }
    .detail-row-premium .value .stock-badge {
        padding: 4px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 13px;
    }
    .stock-badge.in-stock { background: #d4edda; color: #155724; }
    .stock-badge.out-of-stock { background: #f8d7da; color: #721c24; }

    .about-box-premium {
        background: #f8f9fc;
        padding: 18px 22px;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
        line-height: 1.8;
        color: #555;
        font-size: 14px;
        margin-top: 5px;
    }
    .about-box-premium.side-effects { border-left-color: var(--warning); }
    .about-box-premium.precautions { border-left-color: var(--danger); }
    .about-box-premium.how-to-use { border-left-color: var(--success); }

    /* ===== ORDER BUTTON ===== */
    .btn-order-premium {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 50px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 6px 25px rgba(40,167,69,0.3);
    }
    .btn-order-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 35px rgba(40,167,69,0.4);
        color: white;
    }
    .btn-order-premium:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ===== SIDEBAR CARDS ===== */
    .sidebar-card-premium {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 35px rgba(0,0,0,0.06);
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .sidebar-card-premium .sidebar-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-card-premium .sidebar-title i {
        color: var(--primary);
    }
    .sidebar-card-premium .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 13px;
    }
    .sidebar-card-premium .info-item:last-child {
        border-bottom: none;
    }
    .sidebar-card-premium .info-item .info-label {
        color: #888;
    }
    .sidebar-card-premium .info-item .info-value {
        font-weight: 600;
        color: var(--dark);
    }

    /* ===== RELATED CARD ===== */
    .related-card-premium {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s;
        text-align: center;
        height: 100%;
        border: 1px solid transparent;
    }
    .related-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        background: white;
        border-color: var(--primary);
    }
    .related-card-premium .related-icon {
        font-size: 28px;
        color: var(--primary);
        margin-bottom: 8px;
    }
    .related-card-premium .related-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 13px;
        margin: 0;
    }
    .related-card-premium .related-price {
        color: var(--success);
        font-weight: 700;
        font-size: 14px;
        margin: 5px 0 8px;
    }
    .related-card-premium .btn-sm-view {
        background: var(--primary);
        color: white;
        border: none;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }
    .related-card-premium .btn-sm-view:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        color: white;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .detail-hero-premium .hero-content { flex-direction: column; text-align: center; }
        .detail-hero-premium .hero-content .hero-stats { margin-left: 0; justify-content: center; }
        .detail-hero-premium .hero-content .hero-text h1 { font-size: 26px; }
    }

    @media (max-width: 768px) {
        .detail-hero-premium { padding: 90px 0 25px; }
        .detail-hero-premium .hero-content .hero-icon { width: 70px; height: 70px; font-size: 30px; }
        .detail-hero-premium .hero-content .hero-text h1 { font-size: 22px; }
        .detail-hero-premium .hero-content .hero-text .subtitle { font-size: 13px; }
        .detail-hero-premium .hero-content .hero-stats .stat-item { padding: 6px 14px; min-width: 60px; }
        .detail-hero-premium .hero-content .hero-stats .stat-item .number { font-size: 16px; }
        .detail-row-premium { flex-direction: column; align-items: flex-start; }
        .detail-row-premium .label { width: 100%; margin-bottom: 4px; }
        .medicine-detail-card-premium { padding: 20px; }
        .medicine-detail-card-premium .section-title { font-size: 16px; }
        .detail-row-premium .value .price-tag { font-size: 20px; }
        .btn-order-premium { width: 100%; justify-content: center; padding: 12px 30px; }
        .sidebar-card-premium { padding: 18px; }
    }

    @media (max-width: 480px) {
        .detail-hero-premium .hero-content .hero-text h1 { font-size: 18px; }
        .detail-hero-premium .hero-content .hero-text .subtitle { font-size: 12px; }
        .detail-hero-premium .hero-content .hero-stats .stat-item .number { font-size: 14px; }
        .detail-hero-premium .hero-content .hero-stats .stat-item { padding: 5px 10px; min-width: 50px; }
        .detail-hero-premium .hero-content .hero-stats .stat-item .label { font-size: 8px; }
        .medicine-detail-card-premium { padding: 15px; }
        .detail-row-premium .value .price-tag { font-size: 18px; }
        .about-box-premium { padding: 12px 15px; font-size: 12px; }
    }
</style>

<!-- ===== PREMIUM HERO BANNER ===== -->
<section class="detail-hero-premium">
    <div class="floating-pills">
        <span class="pill"><i class="fas fa-pills"></i></span>
        <span class="pill"><i class="fas fa-capsules"></i></span>
        <span class="pill"><i class="fas fa-tablets"></i></span>
        <span class="pill"><i class="fas fa-syringe"></i></span>
        <span class="pill"><i class="fas fa-prescription-bottle-alt"></i></span>
    </div>

    <div class="container">
        <div class="hero-content">
            <div class="hero-icon">
                <?php if(!empty($medicine['img']) && file_exists("images/uploads/".$medicine['img'])): ?>
                    <img src="images/uploads/<?php echo $medicine['img']; ?>" alt="<?php echo $medicine['medicine_name']; ?>">
                <?php else: ?>
                    <i class="fas fa-pills"></i>
                <?php endif; ?>
            </div>
            <div class="hero-text">
                <h1><span class="highlight"><?php echo htmlspecialchars($medicine['medicine_name']); ?></span></h1>
                <p class="subtitle">
                    <i class="fas fa-flask"></i> 
                    <?php echo htmlspecialchars($medicine['generic_name'] ?? 'Generic name not available'); ?>
                </p>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="number">Rs. <?php echo number_format($medicine['price'], 2); ?></span>
                    <span class="label">Price</span>
                </div>
                <div class="stat-item">
                    <span class="number <?php echo ($medicine['stock'] > 0) ? 'in-stock' : 'out-of-stock'; ?>" style="background: none; -webkit-text-fill-color: <?php echo ($medicine['stock'] > 0) ? '#2ecc71' : '#e74c3c'; ?>;">
                        <i class="fas fa-<?php echo ($medicine['stock'] > 0) ? 'check-circle' : 'times-circle'; ?>"></i>
                        <?php echo ($medicine['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                    <span class="label">Availability</span>
                </div>
                <div class="stat-item">
                    <span class="number"><?php echo $medicine['stock']; ?></span>
                    <span class="label">Units</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== DETAIL SECTION ===== -->
<section class="medicine-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a href="medicines.php" class="btn-back-premium">
                    <i class="fas fa-arrow-left"></i> Back to Medicines
                </a>
            </div>
        </div>

        <div class="row">
            <!-- ===== MAIN CONTENT ===== -->
            <div class="col-lg-8">
                <div class="medicine-detail-card-premium">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> Complete Information
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-tag"></i> Category</span>
                        <span class="value">
                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">
                                <?php echo htmlspecialchars($medicine['category'] ?? 'N/A'); ?>
                            </span>
                        </span>
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-industry"></i> Manufacturer</span>
                        <span class="value"><?php echo htmlspecialchars($medicine['manufacturer'] ?? 'N/A'); ?></span>
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-weight-scale"></i> Strength</span>
                        <span class="value"><?php echo htmlspecialchars($medicine['strength'] ?? 'N/A'); ?></span>
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-capsules"></i> Dosage Form</span>
                        <span class="value"><?php echo htmlspecialchars($medicine['dosage_form'] ?? 'N/A'); ?></span>
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-money-bill"></i> Price</span>
                        <span class="value">
                            <span class="price-tag">Rs. <?php echo number_format($medicine['price'], 2); ?></span>
                        </span>
                    </div>

                    <div class="detail-row-premium">
                        <span class="label"><i class="fas fa-boxes"></i> Availability</span>
                        <span class="value">
                            <span class="stock-badge <?php echo ($medicine['stock'] > 0) ? 'in-stock' : 'out-of-stock'; ?>">
                                <i class="fas fa-<?php echo ($medicine['stock'] > 0) ? 'check-circle' : 'times-circle'; ?>"></i>
                                <?php echo ($medicine['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                            <?php if($medicine['stock'] > 0): ?>
                                <span style="margin-left:10px;color:#7f8c8d;font-size:13px;">
                                    <?php echo $medicine['stock']; ?> units available
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <?php if(!empty($medicine['description'])): ?>
                        <div style="margin-top: 15px;">
                            <div class="detail-row-premium" style="border-bottom: none; display: block;">
                                <span class="label" style="width:100%;margin-bottom:8px;">
                                    <i class="fas fa-info-circle"></i> Description
                                </span>
                                <div class="about-box-premium">
                                    <?php echo nl2br(htmlspecialchars($medicine['description'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($medicine['side_effects'])): ?>
                        <div style="margin-top: 15px;">
                            <div class="detail-row-premium" style="border-bottom: none; display: block;">
                                <span class="label" style="width:100%;margin-bottom:8px;">
                                    <i class="fas fa-exclamation-triangle" style="color:var(--warning);"></i> Side Effects
                                </span>
                                <div class="about-box-premium side-effects">
                                    <?php echo nl2br(htmlspecialchars($medicine['side_effects'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($medicine['precautions'])): ?>
                        <div style="margin-top: 15px;">
                            <div class="detail-row-premium" style="border-bottom: none; display: block;">
                                <span class="label" style="width:100%;margin-bottom:8px;">
                                    <i class="fas fa-shield-alt" style="color:var(--danger);"></i> Precautions
                                </span>
                                <div class="about-box-premium precautions">
                                    <?php echo nl2br(htmlspecialchars($medicine['precautions'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($medicine['how_to_use'])): ?>
                        <div style="margin-top: 15px;">
                            <div class="detail-row-premium" style="border-bottom: none; display: block;">
                                <span class="label" style="width:100%;margin-bottom:8px;">
                                    <i class="fas fa-hand-holding" style="color:var(--success);"></i> How to Use
                                </span>
                                <div class="about-box-premium how-to-use">
                                    <?php echo nl2br(htmlspecialchars($medicine['how_to_use'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($medicine['ingredients'])): ?>
                        <div style="margin-top: 15px;">
                            <div class="detail-row-premium" style="border-bottom: none; display: block;">
                                <span class="label" style="width:100%;margin-bottom:8px;">
                                    <i class="fas fa-list-ul"></i> Ingredients
                                </span>
                                <div class="about-box-premium">
                                    <?php echo nl2br(htmlspecialchars($medicine['ingredients'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ===== ORDER BUTTON ===== -->
                <div class="text-center">
                    <?php if($medicine['stock'] > 0): ?>
                        <a href="#" class="btn-order-premium" onclick="alert('Order feature coming soon!')">
                            <i class="fas fa-shopping-cart"></i> Order Now
                        </a>
                    <?php else: ?>
                        <button class="btn-order-premium" disabled style="opacity:0.5;cursor:not-allowed;">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="sidebar-card-premium">
                    <div class="sidebar-title">
                        <i class="fas fa-info-circle"></i> Quick Info
                    </div>
                    <div class="info-item">
                        <span class="info-label">Category</span>
                        <span class="info-value"><?php echo htmlspecialchars($medicine['category'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Manufacturer</span>
                        <span class="info-value"><?php echo htmlspecialchars($medicine['manufacturer'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dosage Form</span>
                        <span class="info-value"><?php echo htmlspecialchars($medicine['dosage_form'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Strength</span>
                        <span class="info-value"><?php echo htmlspecialchars($medicine['strength'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Price</span>
                        <span class="info-value" style="color:var(--success);font-size:18px;">
                            Rs. <?php echo number_format($medicine['price'], 2); ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Stock</span>
                        <span class="info-value">
                            <span class="badge bg-<?php echo ($medicine['stock'] > 0) ? 'success' : 'danger'; ?>">
                                <?php echo ($medicine['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Related Medicines -->
                <?php if(mysqli_num_rows($related_result) > 0): ?>
                    <div class="sidebar-card-premium">
                        <div class="sidebar-title">
                            <i class="fas fa-link"></i> Related Medicines
                        </div>
                        <div class="row g-2">
                            <?php while($related = mysqli_fetch_assoc($related_result)): ?>
                                <div class="col-6">
                                    <div class="related-card-premium">
                                        <div class="related-icon">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                        <p class="related-name"><?php echo htmlspecialchars(substr($related['medicine_name'], 0, 15)); ?></p>
                                        <div class="related-price">Rs. <?php echo number_format($related['price'], 2); ?></div>
                                        <a href="medicine-single.php?hid=<?php echo $related['medicine_id']; ?>" class="btn-sm-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>