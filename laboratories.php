<?php
$title = 'Laboratories - Doctorpedia';
include('header.php');
include('connect.php');

// Check if status column exists
$check_status = mysqli_query($connect, "SHOW COLUMNS FROM labs LIKE 'status'");
$status_exists = mysqli_num_rows($check_status) > 0;

// Fetch all labs
$labs_query = "SELECT * FROM labs ORDER BY lab_id DESC";
$labs_result = mysqli_query($connect, $labs_query);

// Get total labs count
$total_labs = 0;
if($labs_result) {
    $total_labs = mysqli_num_rows($labs_result);
}

// Check if city column exists
$check_city = mysqli_query($connect, "SHOW COLUMNS FROM labs LIKE 'city'");
$city_exists = mysqli_num_rows($check_city) > 0;

// Get cities for filter
if($city_exists) {
    $cities_query = "SELECT DISTINCT city FROM labs WHERE city IS NOT NULL AND city != '' ORDER BY city";
    $cities_result = mysqli_query($connect, $cities_query);
} else {
    $cities_result = false;
    // Try to extract cities from address
    $cities_query = "SELECT DISTINCT SUBSTRING_INDEX(address, ',', -1) as city FROM labs WHERE address IS NOT NULL AND address != '' ORDER BY city";
    $cities_result = mysqli_query($connect, $cities_query);
    if(!$cities_result) {
        $cities_result = false;
    }
}

// Get search parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$city_filter = isset($_GET['city']) ? mysqli_real_escape_string($connect, $_GET['city']) : '';

// Build query with filters
$query = "SELECT * FROM labs WHERE 1=1";
if(!empty($search)) {
    $query .= " AND (lab_name LIKE '%$search%' OR address LIKE '%$search%' OR phone LIKE '%$search%')";
}
if(!empty($city_filter)) {
    if($city_exists) {
        $query .= " AND city = '$city_filter'";
    } else {
        $query .= " AND address LIKE '%$city_filter%'";
    }
}
$query .= " ORDER BY lab_id DESC";

$labs_result = mysqli_query($connect, $query);
$total_labs_filtered = mysqli_num_rows($labs_result);

// Reset for total count
$total_query = "SELECT COUNT(*) as total FROM labs";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_labs_all = $total_row['total'];

// Get cities list
$cities_list = array();
if($cities_result && mysqli_num_rows($cities_result) > 0) {
    while($row = mysqli_fetch_assoc($cities_result)) {
        if(!empty($row['city'])) {
            $cities_list[] = $row['city'];
        }
    }
}
if(empty($cities_list)) {
    $cities_list = array('Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Peshawar', 'Quetta', 'Faisalabad', 'Multan');
}
?>

<style>
    /* ========================================
       PREMIUM LABORATORIES DASHBOARD
       Same design as Medicines page
    ======================================== */

    /* ----- ROOT VARIABLES ----- */
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --primary-light: #a78bfa;
        --secondary: #764ba2;
        --accent: #f093fb;
        --success: #2ecc71;
        --success-dark: #27ae60;
        --warning: #f39c12;
        --danger: #e74c3c;
        --dark: #1a1a2e;
        --dark-light: #2d2d44;
        --gray: #7f8c8d;
        --gray-light: #e8ecf1;
        --white: #ffffff;
        --bg: #f0f2f5;
        --shadow: 0 8px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 20px 60px rgba(102,126,234,0.15);
        --radius: 16px;
        --radius-sm: 10px;
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ----- FIXED CARD SPACING ----- */
    .labs-section-premium .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .labs-section-premium .row > [class*="col-"] {
        padding-left: 12px;
        padding-right: 12px;
        padding-bottom: 28px;
    }

    /* ----- HERO SECTION ----- */
    .labs-hero-premium {
        background: linear-gradient(135deg, rgba(15, 15, 35, 0.92) 0%, rgba(30, 30, 80, 0.85) 50%, rgba(60, 40, 120, 0.80) 100%),
                    url('https://images.unsplash.com/photo-1576086213369-97a306d36557?w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 35px 0 30px 0;
        color: white;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid rgba(102,126,234,0.3);
    }

    .labs-hero-premium .hero-bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }

    /* Floating Lab Icons */
    .labs-hero-premium .floating-icons {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 1;
    }
    .labs-hero-premium .floating-icons .icon {
        position: absolute;
        font-size: 35px;
        opacity: 0.05;
        color: white;
        animation: floatIcon 25s infinite ease-in-out;
    }
    .labs-hero-premium .floating-icons .icon:nth-child(1) { top: 5%; left: 5%; animation-delay: 0s; font-size: 50px; }
    .labs-hero-premium .floating-icons .icon:nth-child(2) { top: 10%; right: 10%; animation-delay: 4s; font-size: 40px; }
    .labs-hero-premium .floating-icons .icon:nth-child(3) { bottom: 15%; left: 8%; animation-delay: 8s; font-size: 45px; }
    .labs-hero-premium .floating-icons .icon:nth-child(4) { bottom: 10%; right: 5%; animation-delay: 12s; font-size: 35px; }
    .labs-hero-premium .floating-icons .icon:nth-child(5) { top: 45%; left: 50%; animation-delay: 16s; font-size: 30px; }
    .labs-hero-premium .floating-icons .icon:nth-child(6) { top: 0%; left: 45%; animation-delay: 20s; font-size: 28px; }

    @keyframes floatIcon {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(30px, -35px) rotate(12deg); }
        50% { transform: translate(-20px, -70px) rotate(-8deg); }
        75% { transform: translate(40px, -25px) rotate(18deg); }
    }

    .labs-hero-premium .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Hero Logo */
    .labs-hero-premium .hero-logo {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 8px;
    }
    .labs-hero-premium .hero-logo .logo-icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: white;
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .labs-hero-premium .hero-logo .logo-text {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .labs-hero-premium .hero-logo .logo-text .highlight {
        background: linear-gradient(135deg, #667eea 0%, #a78bfa 50%, #f093fb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .labs-hero-premium .hero-logo .logo-text .sub {
        font-weight: 300;
        font-size: 14px;
        opacity: 0.6;
        display: block;
        -webkit-text-fill-color: rgba(255,255,255,0.6);
    }

    .labs-hero-premium .hero-subtitle {
        font-size: 15px;
        opacity: 0.85;
        max-width: 520px;
        line-height: 1.6;
        margin-bottom: 15px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.2);
    }

    /* Hero Stats */
    .hero-stats-premium {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
    }
    .hero-stats-premium .stat-item {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(14px);
        padding: 12px 24px;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.08);
        text-align: center;
        min-width: 100px;
        transition: var(--transition);
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        cursor: default;
    }
    .hero-stats-premium .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(102,126,234,0.2) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .hero-stats-premium .stat-item:hover::before {
        opacity: 1;
    }
    .hero-stats-premium .stat-item:hover {
        transform: translateY(-5px) scale(1.03);
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.2);
        box-shadow: 0 12px 40px rgba(102,126,234,0.2);
    }
    .hero-stats-premium .stat-item .number {
        font-size: 28px;
        font-weight: 700;
        display: block;
        background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }
    .hero-stats-premium .stat-item .label {
        font-size: 10px;
        opacity: 0.7;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        display: block;
        margin-top: 3px;
        color: rgba(255,255,255,0.8);
    }
    .hero-stats-premium .stat-item .stat-icon {
        font-size: 18px;
        margin-bottom: 3px;
        display: block;
        opacity: 0.5;
    }

    /* ----- SEARCH SECTION ----- */
    .search-section-premium {
        padding: 20px 0 25px;
        background: var(--bg);
        position: sticky;
        top: 80px;
        z-index: 50;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .search-box-premium {
        background: white;
        border-radius: var(--radius);
        padding: 14px 20px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .search-box-premium .search-input {
        flex: 2;
        min-width: 180px;
        padding: 10px 20px;
        border: 2px solid var(--gray-light);
        border-radius: 30px;
        font-size: 14px;
        transition: var(--transition);
        outline: none;
        background: #f8f9fc;
        color: var(--dark);
    }
    .search-box-premium .search-input:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    }
    .search-box-premium .search-input::placeholder {
        color: #b0b8c4;
    }
    .search-box-premium .category-select {
        flex: 1;
        min-width: 140px;
        padding: 10px 20px;
        border: 2px solid var(--gray-light);
        border-radius: 30px;
        font-size: 14px;
        background: #f8f9fc;
        outline: none;
        cursor: pointer;
        transition: var(--transition);
        color: var(--dark);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
    }
    .search-box-premium .category-select:focus {
        border-color: var(--primary);
        background: white;
    }
    .search-box-premium .search-btn {
        padding: 10px 30px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(102,126,234,0.25);
    }
    .search-box-premium .search-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.35);
    }
    .search-box-premium .reset-btn {
        padding: 10px 22px;
        background: var(--gray-light);
        color: #4a5568;
        border: none;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .search-box-premium .reset-btn:hover {
        background: #d2d8e0;
        transform: translateY(-3px);
    }

    /* ----- LABS SECTION ----- */
    .labs-section-premium {
        padding: 25px 0 60px;
        background: var(--bg);
        min-height: 100vh;
    }

    /* ----- NEXT LEVEL GLASS CARD ----- */
    .lab-card-next {
        background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06), 0 0 0 1px rgba(102,126,234,0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        backdrop-filter: blur(10px);
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .lab-card-next .card-glow {
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(102,126,234,0.06) 0%, transparent 70%);
        pointer-events: none;
        transition: all 0.6s;
        opacity: 0;
    }

    .lab-card-next:hover .card-glow {
        opacity: 1;
        transform: scale(1.5);
    }

    .lab-card-next:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 20px 60px rgba(102,126,234,0.12), 0 0 0 1px rgba(102,126,234,0.08);
    }

    .lab-card-next:hover .card-image-next .overlay-gradient {
        opacity: 0.7;
    }

    .lab-card-next:hover .card-image-next .icon-pulse {
        transform: scale(1.1);
    }

    .lab-card-next:hover .lab-name-next a {
        color: #667eea;
    }

    .lab-card-next .card-image-next {
        height: 150px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        min-height: 150px;
    }

    .lab-card-next .card-image-next .shimmer-bg {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: shimmer 8s infinite linear;
    }

    @keyframes shimmer {
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.2); }
        100% { transform: rotate(360deg) scale(1); }
    }

    .lab-card-next .card-image-next .overlay-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,0.3) 100%);
        transition: opacity 0.4s;
        opacity: 0.3;
    }

    .lab-card-next .card-image-next .icon-pulse {
        position: relative;
        z-index: 2;
        transition: all 0.4s;
    }

    .lab-card-next .card-image-next .icon-pulse .img-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .lab-card-next .card-image-next .icon-pulse .icon-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        border: 3px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .lab-card-next .card-image-next .city-tag {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 3;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        color: #667eea;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.5);
    }

    .lab-card-next .card-image-next .status-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        z-index: 3;
        backdrop-filter: blur(10px);
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .lab-card-next .card-image-next .status-badge.active {
        background: rgba(46,204,113,0.92);
    }

    .lab-card-next .card-image-next .status-badge.inactive {
        background: rgba(231,76,60,0.92);
    }

    .lab-card-next .card-image-next .lab-id {
        position: absolute;
        bottom: 12px;
        left: 12px;
        z-index: 3;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        color: white;
        padding: 3px 12px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 600;
    }

    .lab-card-next .card-body-next {
        padding: 18px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .lab-card-next .card-body-next .lab-name-next {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lab-card-next .card-body-next .lab-name-next a {
        color: #1a1a2e;
        text-decoration: none;
        transition: color 0.3s;
    }

    .lab-card-next .card-body-next .lab-address-next {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lab-card-next .card-body-next .lab-address-next i {
        color: #667eea;
        font-size: 12px;
    }

    .lab-card-next .card-body-next .lab-meta-next {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }

    .lab-card-next .card-body-next .lab-meta-next span {
        font-size: 9px;
        color: #7f8c8d;
        background: linear-gradient(135deg, #f0f2f5 0%, #e8ecf1 100%);
        padding: 3px 10px;
        border-radius: 30px;
        font-weight: 600;
        border: 1px solid rgba(0,0,0,0.02);
        white-space: nowrap;
    }

    .lab-card-next .card-body-next .lab-meta-next span i {
        color: #667eea;
        margin-right: 3px;
        font-size: 9px;
    }

    .lab-card-next .card-body-next .lab-description-next {
        font-size: 12px;
        color: #666;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 12px;
        flex: 1;
        min-height: 36px;
    }

    .lab-card-next .card-body-next .lab-footer-next {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 2px solid #f0f2f5;
        margin-top: auto;
    }

    .lab-card-next .card-body-next .lab-footer-next .contact-info {
        font-size: 11px;
        color: #7f8c8d;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .lab-card-next .card-body-next .lab-footer-next .contact-info .phone {
        font-weight: 600;
        color: #2d2d44;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
    }

    .lab-card-next .card-body-next .lab-footer-next .contact-info .phone i {
        color: #667eea;
    }

    .lab-card-next .card-body-next .lab-footer-next .btn-detail-next {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 7px 18px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.4s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 15px rgba(102,126,234,0.2);
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }

    .lab-card-next .card-body-next .lab-footer-next .btn-detail-next .btn-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transition: left 0.6s;
    }

    .lab-card-next:hover .btn-shine {
        left: 100%;
    }

    .lab-card-next .card-body-next .lab-footer-next .btn-detail-next .btn-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* ----- EMPTY STATE ----- */
    .empty-state-premium {
        text-align: center;
        padding: 70px 20px;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    .empty-state-premium i {
        font-size: 72px;
        color: #ddd;
        margin-bottom: 18px;
    }
    .empty-state-premium h3 {
        color: var(--dark);
        font-weight: 700;
        font-size: 24px;
    }
    .empty-state-premium p {
        color: var(--gray);
        font-size: 16px;
        max-width: 400px;
        margin: 0 auto 20px;
    }
    .empty-state-premium .btn-empty {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        border-radius: 30px;
        padding: 12px 35px;
        color: white;
        font-weight: 600;
        display: inline-block;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(102,126,234,0.25);
    }
    .empty-state-premium .btn-empty:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.35);
        color: white;
    }

    /* ----- RESPONSIVE ----- */
    @media (max-width: 1200px) {
        .lab-card-next .card-body-next .lab-name-next { font-size: 15px; }
    }

    @media (max-width: 992px) {
        .labs-hero-premium .hero-logo .logo-text { font-size: 22px; }
        .hero-stats-premium .stat-item .number { font-size: 22px; }
        .hero-stats-premium .stat-item { padding: 10px 18px; min-width: 80px; }
        .lab-card-next .card-image-next { height: 120px; min-height: 120px; }
        .lab-card-next .card-image-next .icon-pulse .img-circle,
        .lab-card-next .card-image-next .icon-pulse .icon-placeholder { width: 60px; height: 60px; font-size: 26px; }
    }

    @media (max-width: 768px) {
        .labs-section-premium .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        .labs-section-premium .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
            padding-bottom: 20px;
        }

        .labs-hero-premium { padding: 25px 0 20px; background-attachment: scroll; }
        .labs-hero-premium .hero-logo .logo-icon { width: 44px; height: 44px; font-size: 22px; }
        .labs-hero-premium .hero-logo .logo-text { font-size: 18px; }
        .labs-hero-premium .hero-logo .logo-text .sub { font-size: 11px; }
        .labs-hero-premium .hero-subtitle { font-size: 13px; }
        .hero-stats-premium { gap: 10px; justify-content: flex-start; }
        .hero-stats-premium .stat-item { padding: 8px 14px; min-width: 60px; flex: 1; }
        .hero-stats-premium .stat-item .number { font-size: 18px; }
        .hero-stats-premium .stat-item .label { font-size: 8px; letter-spacing: 0.4px; }
        .hero-stats-premium .stat-item .stat-icon { font-size: 14px; }
        .search-section-premium { padding: 15px 0 18px; top: 65px; }
        .search-box-premium { padding: 12px 15px; flex-direction: column; }
        .search-box-premium .search-input,
        .search-box-premium .category-select,
        .search-box-premium .search-btn,
        .search-box-premium .reset-btn { width: 100%; }
        .search-box-premium .search-btn { justify-content: center; }
        .lab-card-next .card-image-next { height: 100px; min-height: 100px; font-size: 30px; }
        .lab-card-next .card-image-next .city-tag { font-size: 8px; padding: 3px 10px; top: 10px; right: 10px; }
        .lab-card-next .card-image-next .status-badge { font-size: 8px; padding: 3px 10px; bottom: 10px; right: 10px; }
        .lab-card-next .card-image-next .lab-id { font-size: 8px; padding: 2px 10px; bottom: 10px; left: 10px; }
        .lab-card-next .card-image-next .icon-pulse .img-circle,
        .lab-card-next .card-image-next .icon-pulse .icon-placeholder { width: 50px; height: 50px; font-size: 22px; }
        .lab-card-next .card-body-next { padding: 14px 16px 16px; }
        .lab-card-next .card-body-next .lab-name-next { font-size: 14px; }
        .lab-card-next .card-body-next .lab-footer-next .contact-info { font-size: 10px; }
        .lab-card-next .card-body-next .lab-footer-next .contact-info .phone { font-size: 11px; }
        .lab-card-next .card-body-next .lab-footer-next .btn-detail-next { font-size: 10px; padding: 5px 14px; }
        .lab-card-next .card-body-next .lab-description-next { font-size: 11px; min-height: 30px; }
        .lab-card-next .card-body-next .lab-meta-next span { font-size: 8px; padding: 2px 8px; }
    }

    @media (max-width: 480px) {
        .labs-section-premium .row {
            margin-left: -5px;
            margin-right: -5px;
        }
        .labs-section-premium .row > [class*="col-"] {
            padding-left: 5px;
            padding-right: 5px;
            padding-bottom: 16px;
        }

        .labs-hero-premium .hero-logo .logo-text { font-size: 15px; }
        .labs-hero-premium .hero-logo .logo-icon { width: 36px; height: 36px; font-size: 16px; }
        .labs-hero-premium .hero-subtitle { font-size: 12px; }
        .hero-stats-premium .stat-item { padding: 6px 10px; }
        .hero-stats-premium .stat-item .number { font-size: 15px; }
        .hero-stats-premium .stat-item .label { font-size: 7px; }
        .lab-card-next .card-body-next .lab-name-next { font-size: 13px; }
        .lab-card-next .card-body-next .lab-footer-next .btn-detail-next { font-size: 9px; padding: 4px 12px; }
        .lab-card-next .card-body-next .lab-description-next { font-size: 10px; min-height: 26px; }
        .lab-card-next .card-body-next .lab-meta-next span { font-size: 8px; padding: 2px 6px; }
        .lab-card-next .card-body-next .lab-footer-next { padding-top: 10px; }
        .search-section-premium { padding: 10px 0 14px; }
        .search-box-premium .search-input { font-size: 12px; padding: 8px 14px; }
        .empty-state-premium i { font-size: 48px; }
        .empty-state-premium h3 { font-size: 18px; }
    }
</style>

<!-- ===== HERO SECTION ===== -->
<section class="labs-hero-premium">
    <div class="hero-bg-pattern"></div>
    <div class="floating-icons">
        <span class="icon"><i class="fas fa-flask"></i></span>
        <span class="icon"><i class="fas fa-microscope"></i></span>
        <span class="icon"><i class="fas fa-vial"></i></span>
        <span class="icon"><i class="fas fa-dna"></i></span>
        <span class="icon"><i class="fas fa-syringe"></i></span>
        <span class="icon"><i class="fas fa-capsules"></i></span>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="hero-logo">
                        <div class="logo-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="logo-text">
                            <span class="highlight">Laboratories</span> Directory
                            <span class="sub">Premium Diagnostic Solutions</span>
                        </div>
                    </div>

                    <p class="hero-subtitle">
                        Discover Pakistan's most trusted diagnostic laboratories with advanced technology and expert pathologists.
                        Search by name, address, or phone with ease.
                    </p>

                    <div class="hero-stats-premium">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-flask"></i></span>
                            <span class="number"><?php echo $total_labs_all; ?></span>
                            <span class="label">Total Labs</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-city"></i></span>
                            <span class="number"><?php echo count($cities_list); ?>+</span>
                            <span class="label">Cities Covered</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-shield-alt"></i></span>
                            <span class="number">100%</span>
                            <span class="label">Accredited</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-clock"></i></span>
                            <span class="number">24/7</span>
                            <span class="label">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SEARCH SECTION ===== -->
<section class="search-section-premium">
    <div class="container">
        <div class="search-box-premium">
            <form method="GET" action="laboratories.php" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%; align-items: center;">
                <input type="text" name="search" class="search-input" placeholder="Search by lab name, address, or phone..." value="<?php echo htmlspecialchars($search); ?>">
                
                <select name="city" class="category-select">
                    <option value="">All Cities</option>
                    <?php foreach($cities_list as $city): ?>
                        <option value="<?php echo $city; ?>" <?php echo ($city_filter == $city) ? 'selected' : ''; ?>>
                            <?php echo $city; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Find
                </button>
                <a href="laboratories.php" class="reset-btn">
                    <i class="fas fa-times"></i> Reset
                </a>
            </form>
        </div>
    </div>
</section>

<!-- ===== LABS SECTION - NEXT LEVEL GLASS CARDS ===== -->
<section class="labs-section-premium">
    <div class="container">
        <?php if($total_labs_filtered > 0): ?>
            <div class="row">
                <?php 
                // Reset and fetch labs with filters
                $labs_query_final = "SELECT * FROM labs WHERE 1=1";
                if(!empty($search)) {
                    $labs_query_final .= " AND (lab_name LIKE '%$search%' OR address LIKE '%$search%' OR phone LIKE '%$search%')";
                }
                if(!empty($city_filter)) {
                    if($city_exists) {
                        $labs_query_final .= " AND city = '$city_filter'";
                    } else {
                        $labs_query_final .= " AND address LIKE '%$city_filter%'";
                    }
                }
                $labs_query_final .= " ORDER BY lab_id DESC";
                $labs_result_final = mysqli_query($connect, $labs_query_final);
                
                while($lab = mysqli_fetch_assoc($labs_result_final)): 
                    // Get status with fallback
                    $lab_status = isset($lab['status']) ? $lab['status'] : 'active';
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="lab-card-next">
                            <div class="card-glow"></div>
                            
                            <div class="card-image-next">
                                <div class="shimmer-bg"></div>
                                <div class="overlay-gradient"></div>
                                
                                <div class="icon-pulse">
                                    <?php if(!empty($lab['img']) && file_exists("images/uploads/".$lab['img'])): ?>
                                        <img src="images/uploads/<?php echo $lab['img']; ?>" alt="<?php echo $lab['lab_name']; ?>" class="img-circle">
                                    <?php else: ?>
                                        <div class="icon-placeholder">
                                            <i class="fas fa-flask"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <span class="city-tag">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($lab['city'] ?? 'N/A'); ?>
                                </span>

                                <span class="status-badge <?php echo ($lab_status == 'active') ? 'active' : 'inactive'; ?>">
                                    <i class="fas fa-<?php echo ($lab_status == 'active') ? 'check-circle' : 'times-circle'; ?>"></i>
                                    <?php echo ($lab_status == 'active') ? 'Active' : 'Inactive'; ?>
                                </span>

                                <span class="lab-id">
                                    <i class="fas fa-id-badge"></i> #<?php echo $lab['lab_id']; ?>
                                </span>
                            </div>

                            <div class="card-body-next">
                                <h5 class="lab-name-next">
                                    <a href="lab-single.php?hid=<?php echo $lab['lab_id']; ?>">
                                        <?php echo htmlspecialchars($lab['lab_name']); ?>
                                    </a>
                                </h5>
                                
                                <div class="lab-address-next">
                                    <i class="fas fa-address-card"></i>
                                    <?php echo htmlspecialchars($lab['address'] ?? 'Address not available'); ?>
                                </div>

                                <div class="lab-meta-next">
                                    <?php if(!empty($lab['phone'])): ?>
                                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($lab['phone']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($lab['email'])): ?>
                                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($lab['email']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($lab['city'])): ?>
                                        <span><i class="fas fa-city"></i> <?php echo htmlspecialchars($lab['city']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if(!empty($lab['about'])): ?>
                                    <div class="lab-description-next">
                                        <?php echo htmlspecialchars(substr($lab['about'], 0, 80)); ?>...
                                    </div>
                                <?php endif; ?>

                                <div class="lab-footer-next">
                                    <div class="contact-info">
                                        <?php if(!empty($lab['phone'])): ?>
                                            <span class="phone">
                                                <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($lab['phone']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="lab-single.php?hid=<?php echo $lab['lab_id']; ?>" class="btn-detail-next">
                                        <span class="btn-shine"></span>
                                        <span class="btn-content">
                                            <i class="fas fa-eye"></i> Detail
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="col-lg-12">
                <div class="empty-state-premium">
                    <i class="fas fa-flask"></i>
                    <h3>No Laboratories Found</h3>
                    <p>We couldn't find any laboratories matching your search criteria.</p>
                    <a href="laboratories.php" class="btn-empty">
                        <i class="fas fa-sync-alt"></i> View All Laboratories
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('footer.php'); ?>