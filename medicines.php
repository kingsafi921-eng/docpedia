<?php
$title = 'Medicines - Doctorpedia';
include('header.php');
include('connect.php');

// Get search parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($connect, $_GET['category']) : '';

// Build query
$query = "SELECT * FROM medicines WHERE status = 'active'";
if(!empty($search)) {
    $query .= " AND (medicine_name LIKE '%$search%' OR generic_name LIKE '%$search%' OR category LIKE '%$search%' OR manufacturer LIKE '%$search%')";
}
if(!empty($category)) {
    $query .= " AND category = '$category'";
}
$query .= " ORDER BY medicine_id DESC";

$medicines_result = mysqli_query($connect, $query);
$total_medicines = mysqli_num_rows($medicines_result);

// Get categories for filter
$categories_query = "SELECT DISTINCT category FROM medicines WHERE category IS NOT NULL AND status = 'active' ORDER BY category";
$categories_result = mysqli_query($connect, $categories_query);
?>

<style>
    /* ========================================
       PREMIUM MEDICINES DASHBOARD
       Modern, Attractive, Professional
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
    .medicines-section-premium .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .medicines-section-premium .row > [class*="col-"] {
        padding-left: 12px;
        padding-right: 12px;
        padding-bottom: 28px;
    }

    /* ----- HERO SECTION ----- */
    .medicines-hero-premium {
        background: linear-gradient(135deg, rgba(15, 15, 35, 0.92) 0%, rgba(30, 30, 80, 0.85) 50%, rgba(60, 40, 120, 0.80) 100%),
                    url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 35px 0 30px 0;
        color: white;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid rgba(102,126,234,0.3);
    }

    .medicines-hero-premium .hero-bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }

    /* Floating Pills */
    .medicines-hero-premium .floating-pills {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 1;
    }
    .medicines-hero-premium .floating-pills .pill {
        position: absolute;
        font-size: 35px;
        opacity: 0.05;
        color: white;
        animation: floatPill 25s infinite ease-in-out;
    }
    .medicines-hero-premium .floating-pills .pill:nth-child(1) { top: 5%; left: 5%; animation-delay: 0s; font-size: 50px; }
    .medicines-hero-premium .floating-pills .pill:nth-child(2) { top: 10%; right: 10%; animation-delay: 4s; font-size: 40px; }
    .medicines-hero-premium .floating-pills .pill:nth-child(3) { bottom: 15%; left: 8%; animation-delay: 8s; font-size: 45px; }
    .medicines-hero-premium .floating-pills .pill:nth-child(4) { bottom: 10%; right: 5%; animation-delay: 12s; font-size: 35px; }
    .medicines-hero-premium .floating-pills .pill:nth-child(5) { top: 45%; left: 50%; animation-delay: 16s; font-size: 30px; }
    .medicines-hero-premium .floating-pills .pill:nth-child(6) { top: 0%; left: 45%; animation-delay: 20s; font-size: 28px; }

    @keyframes floatPill {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(30px, -35px) rotate(12deg); }
        50% { transform: translate(-20px, -70px) rotate(-8deg); }
        75% { transform: translate(40px, -25px) rotate(18deg); }
    }

    .medicines-hero-premium .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Hero Logo */
    .medicines-hero-premium .hero-logo {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 8px;
    }
    .medicines-hero-premium .hero-logo .logo-icon {
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
    .medicines-hero-premium .hero-logo .logo-text {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .medicines-hero-premium .hero-logo .logo-text .highlight {
        background: linear-gradient(135deg, #667eea 0%, #a78bfa 50%, #f093fb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .medicines-hero-premium .hero-logo .logo-text .sub {
        font-weight: 300;
        font-size: 14px;
        opacity: 0.6;
        display: block;
        -webkit-text-fill-color: rgba(255,255,255,0.6);
    }

    .medicines-hero-premium .hero-subtitle {
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

    /* ----- MEDICINES SECTION ----- */
    .medicines-section-premium {
        padding: 25px 0 60px;
        background: var(--bg);
        min-height: 100vh;
    }

    /* ----- NEXT LEVEL GLASS CARD ----- */
    .medicine-card-next {
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

    .medicine-card-next .card-glow {
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

    .medicine-card-next:hover .card-glow {
        opacity: 1;
        transform: scale(1.5);
    }

    .medicine-card-next:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 20px 60px rgba(102,126,234,0.12), 0 0 0 1px rgba(102,126,234,0.08);
    }

    .medicine-card-next:hover .card-image-next .overlay-gradient {
        opacity: 0.7;
    }

    .medicine-card-next:hover .card-image-next .icon-pulse {
        transform: scale(1.1);
    }

    .medicine-card-next:hover .med-name-next a {
        color: #667eea;
    }

    .medicine-card-next .card-image-next {
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

    .medicine-card-next .card-image-next .shimmer-bg {
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

    .medicine-card-next .card-image-next .overlay-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,0.3) 100%);
        transition: opacity 0.4s;
        opacity: 0.3;
    }

    .medicine-card-next .card-image-next .icon-pulse {
        position: relative;
        z-index: 2;
        transition: all 0.4s;
    }

    .medicine-card-next .card-image-next .icon-pulse .img-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .medicine-card-next .card-image-next .icon-pulse .icon-placeholder {
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

    .medicine-card-next .card-image-next .category-tag {
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

    .medicine-card-next .card-image-next .stock-badge {
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

    .medicine-card-next .card-image-next .stock-badge.in-stock {
        background: rgba(46,204,113,0.92);
    }

    .medicine-card-next .card-image-next .stock-badge.out-of-stock {
        background: rgba(231,76,60,0.92);
    }

    .medicine-card-next .card-image-next .stock-count {
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

    .medicine-card-next .card-body-next {
        padding: 18px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .medicine-card-next .card-body-next .med-name-next {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .medicine-card-next .card-body-next .med-name-next a {
        color: #1a1a2e;
        text-decoration: none;
        transition: color 0.3s;
    }

    .medicine-card-next .card-body-next .med-generic-next {
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

    .medicine-card-next .card-body-next .med-generic-next i {
        color: #667eea;
        font-size: 12px;
    }

    .medicine-card-next .card-body-next .med-meta-next {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }

    .medicine-card-next .card-body-next .med-meta-next span {
        font-size: 9px;
        color: #7f8c8d;
        background: linear-gradient(135deg, #f0f2f5 0%, #e8ecf1 100%);
        padding: 3px 10px;
        border-radius: 30px;
        font-weight: 600;
        border: 1px solid rgba(0,0,0,0.02);
        white-space: nowrap;
    }

    .medicine-card-next .card-body-next .med-meta-next span i {
        color: #667eea;
        margin-right: 3px;
        font-size: 9px;
    }

    .medicine-card-next .card-body-next .med-description-next {
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

    .medicine-card-next .card-body-next .med-footer-next {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 2px solid #f0f2f5;
        margin-top: auto;
    }

    .medicine-card-next .card-body-next .med-footer-next .price-next {
        font-size: 18px;
        font-weight: 700;
        color: #2ecc71;
        display: block;
        line-height: 1.2;
    }

    .medicine-card-next .card-body-next .med-footer-next .price-next .currency-next {
        font-size: 11px;
        font-weight: 600;
        color: #7f8c8d;
        margin-right: 2px;
    }

    .medicine-card-next .card-body-next .med-footer-next .price-label-next {
        font-size: 9px;
        color: #7f8c8d;
        opacity: 0.6;
        display: block;
    }

    .medicine-card-next .card-body-next .med-footer-next .btn-detail-next {
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

    .medicine-card-next .card-body-next .med-footer-next .btn-detail-next .btn-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transition: left 0.6s;
    }

    .medicine-card-next:hover .btn-shine {
        left: 100%;
    }

    .medicine-card-next .card-body-next .med-footer-next .btn-detail-next .btn-content {
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
        .medicine-card-next .card-body-next .med-name-next { font-size: 15px; }
        .medicine-card-next .card-body-next .med-footer-next .price-next { font-size: 17px; }
    }

    @media (max-width: 992px) {
        .medicines-hero-premium .hero-logo .logo-text { font-size: 22px; }
        .hero-stats-premium .stat-item .number { font-size: 22px; }
        .hero-stats-premium .stat-item { padding: 10px 18px; min-width: 80px; }
        .medicine-card-next .card-image-next { height: 120px; min-height: 120px; }
        .medicine-card-next .card-image-next .icon-pulse .img-circle,
        .medicine-card-next .card-image-next .icon-pulse .icon-placeholder { width: 60px; height: 60px; font-size: 26px; }
    }

    @media (max-width: 768px) {
        .medicines-section-premium .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        .medicines-section-premium .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
            padding-bottom: 20px;
        }

        .medicines-hero-premium { padding: 25px 0 20px; background-attachment: scroll; }
        .medicines-hero-premium .hero-logo .logo-icon { width: 44px; height: 44px; font-size: 22px; }
        .medicines-hero-premium .hero-logo .logo-text { font-size: 18px; }
        .medicines-hero-premium .hero-logo .logo-text .sub { font-size: 11px; }
        .medicines-hero-premium .hero-subtitle { font-size: 13px; }
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
        .medicine-card-next .card-image-next { height: 100px; min-height: 100px; font-size: 30px; }
        .medicine-card-next .card-image-next .category-tag { font-size: 8px; padding: 3px 10px; top: 10px; right: 10px; }
        .medicine-card-next .card-image-next .stock-badge { font-size: 8px; padding: 3px 10px; bottom: 10px; right: 10px; }
        .medicine-card-next .card-image-next .stock-count { font-size: 8px; padding: 2px 10px; bottom: 10px; left: 10px; }
        .medicine-card-next .card-image-next .icon-pulse .img-circle,
        .medicine-card-next .card-image-next .icon-pulse .icon-placeholder { width: 50px; height: 50px; font-size: 22px; }
        .medicine-card-next .card-body-next { padding: 14px 16px 16px; }
        .medicine-card-next .card-body-next .med-name-next { font-size: 14px; }
        .medicine-card-next .card-body-next .med-footer-next .price-next { font-size: 16px; }
        .medicine-card-next .card-body-next .med-footer-next .btn-detail-next { font-size: 10px; padding: 5px 14px; }
        .medicine-card-next .card-body-next .med-description-next { font-size: 11px; min-height: 30px; }
        .medicine-card-next .card-body-next .med-meta-next span { font-size: 8px; padding: 2px 8px; }
    }

    @media (max-width: 480px) {
        .medicines-section-premium .row {
            margin-left: -5px;
            margin-right: -5px;
        }
        .medicines-section-premium .row > [class*="col-"] {
            padding-left: 5px;
            padding-right: 5px;
            padding-bottom: 16px;
        }

        .medicines-hero-premium .hero-logo .logo-text { font-size: 15px; }
        .medicines-hero-premium .hero-logo .logo-icon { width: 36px; height: 36px; font-size: 16px; }
        .medicines-hero-premium .hero-subtitle { font-size: 12px; }
        .hero-stats-premium .stat-item { padding: 6px 10px; }
        .hero-stats-premium .stat-item .number { font-size: 15px; }
        .hero-stats-premium .stat-item .label { font-size: 7px; }
        .medicine-card-next .card-body-next .med-name-next { font-size: 13px; }
        .medicine-card-next .card-body-next .med-footer-next .price-next { font-size: 14px; }
        .medicine-card-next .card-body-next .med-footer-next .btn-detail-next { font-size: 9px; padding: 4px 12px; }
        .medicine-card-next .card-body-next .med-description-next { font-size: 10px; min-height: 26px; }
        .medicine-card-next .card-body-next .med-meta-next span { font-size: 8px; padding: 2px 6px; }
        .medicine-card-next .card-body-next .med-footer-next { padding-top: 10px; }
        .search-section-premium { padding: 10px 0 14px; }
        .search-box-premium .search-input { font-size: 12px; padding: 8px 14px; }
        .empty-state-premium i { font-size: 48px; }
        .empty-state-premium h3 { font-size: 18px; }
    }
</style>

<!-- ===== HERO SECTION ===== -->
<section class="medicines-hero-premium">
    <div class="hero-bg-pattern"></div>
    <div class="floating-pills">
        <span class="pill"><i class="fas fa-pills"></i></span>
        <span class="pill"><i class="fas fa-capsules"></i></span>
        <span class="pill"><i class="fas fa-tablets"></i></span>
        <span class="pill"><i class="fas fa-syringe"></i></span>
        <span class="pill"><i class="fas fa-prescription-bottle-alt"></i></span>
        <span class="pill"><i class="fas fa-capsules"></i></span>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="hero-logo">
                        <div class="logo-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="logo-text">
                            <span class="highlight">Medicines</span> Directory
                            <span class="sub">Premium Healthcare Solutions</span>
                        </div>
                    </div>

                    <p class="hero-subtitle">
                        Discover authentic, high-quality medicines from trusted manufacturers worldwide.
                        Search by name, category, or brand with ease.
                    </p>

                    <div class="hero-stats-premium">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-pills"></i></span>
                            <span class="number"><?php echo $total_medicines; ?></span>
                            <span class="label">Total Medicines</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-tags"></i></span>
                            <span class="number"><?php echo mysqli_num_rows($categories_result); ?>+</span>
                            <span class="label">Categories</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fas fa-shield-alt"></i></span>
                            <span class="number">100%</span>
                            <span class="label">Authentic</span>
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
            <form method="GET" action="medicines.php" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%; align-items: center;">
                <input type="text" name="search" class="search-input" placeholder="Search by name, generic, or brand..." value="<?php echo htmlspecialchars($search); ?>">
                
                <select name="category" class="category-select">
                    <option value="">All Categories</option>
                    <?php 
                    mysqli_data_seek($categories_result, 0);
                    while($cat = mysqli_fetch_assoc($categories_result)): 
                    ?>
                        <option value="<?php echo $cat['category']; ?>" <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                            <?php echo $cat['category']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Find
                </button>
                <a href="medicines.php" class="reset-btn">
                    <i class="fas fa-times"></i> Reset
                </a>
            </form>
        </div>
    </div>
</section>

<!-- ===== MEDICINES SECTION - NEXT LEVEL GLASS CARDS ===== -->
<section class="medicines-section-premium">
    <div class="container">
        <?php if($total_medicines > 0): ?>
            <div class="row">
                <?php while($medicine = mysqli_fetch_assoc($medicines_result)): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="medicine-card-next">
                            <div class="card-glow"></div>
                            
                            <div class="card-image-next">
                                <div class="shimmer-bg"></div>
                                <div class="overlay-gradient"></div>
                                
                                <div class="icon-pulse">
                                    <?php if(!empty($medicine['img']) && file_exists("images/uploads/".$medicine['img'])): ?>
                                        <img src="images/uploads/<?php echo $medicine['img']; ?>" alt="<?php echo $medicine['medicine_name']; ?>" class="img-circle">
                                    <?php else: ?>
                                        <div class="icon-placeholder">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <span class="category-tag">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($medicine['category'] ?? 'General'); ?>
                                </span>

                                <span class="stock-badge <?php echo ($medicine['stock'] > 0) ? 'in-stock' : 'out-of-stock'; ?>">
                                    <i class="fas fa-<?php echo ($medicine['stock'] > 0) ? 'check' : 'times'; ?>"></i>
                                    <?php echo ($medicine['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
                                </span>

                                <span class="stock-count">
                                    <i class="fas fa-boxes"></i> <?php echo $medicine['stock']; ?> units
                                </span>
                            </div>

                            <div class="card-body-next">
                                <h5 class="med-name-next">
                                    <a href="medicine-single.php?hid=<?php echo $medicine['medicine_id']; ?>">
                                        <?php echo htmlspecialchars($medicine['medicine_name']); ?>
                                    </a>
                                </h5>
                                
                                <div class="med-generic-next">
                                    <i class="fas fa-flask"></i>
                                    <?php echo htmlspecialchars($medicine['generic_name'] ?? 'Generic'); ?>
                                </div>

                                <div class="med-meta-next">
                                    <?php if(!empty($medicine['strength'])): ?>
                                        <span><i class="fas fa-weight-scale"></i> <?php echo htmlspecialchars($medicine['strength']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($medicine['dosage_form'])): ?>
                                        <span><i class="fas fa-capsules"></i> <?php echo htmlspecialchars($medicine['dosage_form']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($medicine['manufacturer'])): ?>
                                        <span><i class="fas fa-industry"></i> <?php echo htmlspecialchars($medicine['manufacturer']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if(!empty($medicine['description'])): ?>
                                    <div class="med-description-next">
                                        <?php echo htmlspecialchars(substr($medicine['description'], 0, 80)); ?>...
                                    </div>
                                <?php endif; ?>

                                <div class="med-footer-next">
                                    <div>
                                        <span class="price-next">
                                            <span class="currency-next">Rs.</span> <?php echo number_format($medicine['price'], 2); ?>
                                        </span>
                                        <span class="price-label-next">per unit</span>
                                    </div>
                                    
                                    <a href="medicine-single.php?hid=<?php echo $medicine['medicine_id']; ?>" class="btn-detail-next">
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
                    <i class="fas fa-pills"></i>
                    <h3>No Medicines Found</h3>
                    <p>We couldn't find any medicines matching your search criteria.</p>
                    <a href="medicines.php" class="btn-empty">
                        <i class="fas fa-sync-alt"></i> View All Medicines
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('footer.php'); ?>