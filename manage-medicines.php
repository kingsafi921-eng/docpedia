<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

// Get page parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// ============ DOCTOR CRUD OPERATIONS ============

// Delete Doctor
if(isset($_GET['delete_doctor'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_doctor']);
    $img_query = mysqli_query($connect, "SELECT doc_image FROM doctors WHERE doc_id = '$id'");
    $img_row = mysqli_fetch_assoc($img_query);
    if(!empty($img_row['doc_image']) && file_exists($img_row['doc_image'])) {
        unlink($img_row['doc_image']);
    }
    mysqli_query($connect, "DELETE FROM doctors WHERE doc_id = '$id'");
    $msg = "Doctor deleted successfully!";
    $msg_type = "success";
    header('Location: ?page=doctors&msg=' . urlencode($msg) . '&msg_type=' . $msg_type);
    exit();
}

// Add Doctor
if(isset($_POST['add_doctor'])) {
    $doc_name = mysqli_real_escape_string($connect, $_POST['doc_name']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $hos_address = mysqli_real_escape_string($connect, $_POST['hos_address']);
    $exp = mysqli_real_escape_string($connect, $_POST['exp']);
    $fee = mysqli_real_escape_string($connect, $_POST['fee']);
    $days = mysqli_real_escape_string($connect, $_POST['days']);
    $time = mysqli_real_escape_string($connect, $_POST['time']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $qualification = mysqli_real_escape_string($connect, $_POST['qualification']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $category = mysqli_real_escape_string($connect, $_POST['category']);
    
    $image_path = '';
    if(isset($_FILES['doc_image']) && $_FILES['doc_image']['error'] == 0) {
        $target_dir = "uploads/doctors/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['doc_image']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['doc_image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }
    
    $insert = "INSERT INTO doctors (doc_name, spec, hos_address, exp, fee, days, time, about, qualification, phone, email, category, doc_image) 
               VALUES ('$doc_name', '$spec', '$hos_address', '$exp', '$fee', '$days', '$time', '$about', '$qualification', '$phone', '$email', '$category', '$image_path')";
    
    if(mysqli_query($connect, $insert)) {
        $msg = "Doctor added successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error: " . mysqli_error($connect);
        $msg_type = "danger";
    }
}

// Update Doctor
if(isset($_POST['update_doctor'])) {
    $doc_id = mysqli_real_escape_string($connect, $_POST['doc_id']);
    $doc_name = mysqli_real_escape_string($connect, $_POST['doc_name']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $hos_address = mysqli_real_escape_string($connect, $_POST['hos_address']);
    $exp = mysqli_real_escape_string($connect, $_POST['exp']);
    $fee = mysqli_real_escape_string($connect, $_POST['fee']);
    $days = mysqli_real_escape_string($connect, $_POST['days']);
    $time = mysqli_real_escape_string($connect, $_POST['time']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $qualification = mysqli_real_escape_string($connect, $_POST['qualification']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $category = mysqli_real_escape_string($connect, $_POST['category']);
    
    $image_update = '';
    if(isset($_FILES['doc_image']) && $_FILES['doc_image']['error'] == 0) {
        $target_dir = "uploads/doctors/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['doc_image']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['doc_image']['tmp_name'], $target_file)) {
            $old_img_query = mysqli_query($connect, "SELECT doc_image FROM doctors WHERE doc_id = '$doc_id'");
            $old_img = mysqli_fetch_assoc($old_img_query);
            if(!empty($old_img['doc_image']) && file_exists($old_img['doc_image'])) {
                unlink($old_img['doc_image']);
            }
            $image_update = ", doc_image = '$target_file'";
        }
    }
    
    $update = "UPDATE doctors SET 
                doc_name = '$doc_name',
                spec = '$spec',
                hos_address = '$hos_address',
                exp = '$exp',
                fee = '$fee',
                days = '$days',
                time = '$time',
                about = '$about',
                qualification = '$qualification',
                phone = '$phone',
                email = '$email',
                category = '$category'
                $image_update
                WHERE doc_id = '$doc_id'";
    
    if(mysqli_query($connect, $update)) {
        $msg = "Doctor updated successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error: " . mysqli_error($connect);
        $msg_type = "danger";
    }
}

// Handle Add Lab
if(isset($_POST['add_lab'])) {
    $lab_name = mysqli_real_escape_string($connect, $_POST['lab_name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $website = mysqli_real_escape_string($connect, $_POST['website']);
    
    $insert = "INSERT INTO labs (lab_name, email, phone, address, about, website) 
               VALUES ('$lab_name', '$email', '$phone', '$address', '$about', '$website')";
    
    if(mysqli_query($connect, $insert)) {
        $msg = "Lab added successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error: " . mysqli_error($connect);
        $msg_type = "danger";
    }
}

// Delete Lab
if(isset($_GET['delete_lab'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_lab']);
    mysqli_query($connect, "DELETE FROM labs WHERE lab_id = '$id'");
    header('Location: ?page=labs');
    exit();
}

// Delete Speciality
if(isset($_GET['delete_speciality'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_speciality']);
    mysqli_query($connect, "DELETE FROM disease WHERE disease_id = '$id'");
    header('Location: ?page=specialities');
    exit();
}

// Add Speciality
if(isset($_POST['add_speciality'])) {
    $name = mysqli_real_escape_string($connect, $_POST['speciality_name']);
    $desc = mysqli_real_escape_string($connect, $_POST['speciality_description']);
    
    $check = mysqli_query($connect, "SELECT * FROM disease WHERE disease_name = '$name'");
    if(mysqli_num_rows($check) > 0) {
        $msg = "Speciality already exists!";
        $msg_type = "danger";
    } else {
        $insert = "INSERT INTO disease (disease_name, disease_description) VALUES ('$name', '$desc')";
        if(mysqli_query($connect, $insert)) {
            $msg = "Speciality added successfully!";
            $msg_type = "success";
        }
    }
}

// Delete Pharmacy
if(isset($_GET['delete_pharmacy'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_pharmacy']);
    mysqli_query($connect, "DELETE FROM pharmacies WHERE pharmacy_id = '$id'");
    header('Location: ?page=pharmacies');
    exit();
}

// Delete Medicine
if(isset($_GET['delete_medicine'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_medicine']);
    mysqli_query($connect, "DELETE FROM medicines WHERE medicine_id = '$id'");
    header('Location: ?page=medicines');
    exit();
}

// Get edit doctor data
$edit_doctor = null;
if(isset($_GET['edit_doctor'])) {
    $edit_id = mysqli_real_escape_string($connect, $_GET['edit_doctor']);
    $edit_query = mysqli_query($connect, "SELECT * FROM doctors WHERE doc_id = '$edit_id'");
    $edit_doctor = mysqli_fetch_assoc($edit_query);
}

// Get all specialities for dropdown
$specialities_list = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_name");

// Get message from URL
if(isset($_GET['msg'])) {
    $msg = urldecode($_GET['msg']);
    $msg_type = isset($_GET['msg_type']) ? $_GET['msg_type'] : 'success';
}

// Get counts for sidebar badge
$total_doctors = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM doctors"));
$total_labs = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM labs"));
$total_specialities = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM disease"));
$total_pharmacies = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pharmacies"));
$total_medicines = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines"));

// ✅ INCLUDE HEADER AFTER ALL PHP PROCESSING
include('dashboard-header.php');
?>

<!-- ===== PREMIUM STYLES ===== -->
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #10b981;
        --success-dark: #059669;
        --warning: #f59e0b;
        --warning-dark: #d97706;
        --danger: #ef4444;
        --danger-dark: #dc2626;
        --info: #3b82f6;
        --info-dark: #2563eb;
        --dark: #0f172a;
        --gray: #64748b;
        --gray-lighter: #e2e8f0;
        --bg: #f1f5f9;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
        --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
        --shadow-lg: 0 10px 50px rgba(0,0,0,0.12);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--bg);
        color: var(--dark);
        -webkit-font-smoothing: antialiased;
    }

    .admin-dashboard-wrapper {
        display: flex;
        margin-top: 80px;
        min-height: calc(100vh - 80px);
        background: var(--bg);
    }

    /* ===== SIDEBAR ===== */
    .admin-sidebar {
        width: 280px;
        min-height: calc(100vh - 80px);
        background: linear-gradient(180deg, #0f0f1f 0%, #1a1a2e 35%, #16213e 65%, #0f3460 100%);
        color: #fff;
        padding: 0;
        position: sticky;
        top: 80px;
        height: calc(100vh - 80px);
        overflow-y: auto;
        flex-shrink: 0;
        z-index: 50;
        box-shadow: 4px 0 40px rgba(0,0,0,0.3);
        border-right: 1px solid rgba(255,255,255,0.04);
    }

    .admin-sidebar::-webkit-scrollbar { width: 4px; }
    .admin-sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 10px; }

    .sidebar-brand {
        padding: 24px 20px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background: rgba(255,255,255,0.02);
    }

    .sidebar-brand .brand-logo {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sidebar-brand .brand-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 30px rgba(102,126,234,0.35);
        flex-shrink: 0;
    }

    .sidebar-brand .brand-text h4 {
        font-weight: 800;
        font-size: 22px;
        margin: 0;
        background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sidebar-brand .brand-text small {
        font-size: 10px;
        color: rgba(255,255,255,0.25);
        display: block;
        margin-top: -2px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .sidebar-nav { padding: 16px 0 30px; }

    .sidebar-nav .nav-section {
        padding: 18px 22px 8px;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.15);
        font-weight: 700;
    }

    .sidebar-nav .nav-item {
        margin: 2px 12px;
        border-radius: 10px;
    }

    .sidebar-nav .nav-item a {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        color: rgba(255,255,255,0.4);
        text-decoration: none;
        border-radius: 10px;
        transition: var(--transition);
        font-size: 13px;
        font-weight: 500;
        gap: 12px;
        position: relative;
    }

    .sidebar-nav .nav-item a i {
        width: 20px;
        font-size: 16px;
        text-align: center;
        color: rgba(255,255,255,0.2);
        flex-shrink: 0;
    }

    .sidebar-nav .nav-item a .nav-text { flex: 1; }

    .sidebar-nav .nav-item a .badge-pill {
        margin-left: auto;
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.35);
        padding: 1px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .sidebar-nav .nav-item a .badge-pill.primary { background: rgba(102,126,234,0.2); color: #a78bfa; }
    .sidebar-nav .nav-item a .badge-pill.success { background: rgba(16,185,129,0.2); color: #6ee7b7; }
    .sidebar-nav .nav-item a .badge-pill.warning { background: rgba(245,158,11,0.2); color: #fcd34d; }
    .sidebar-nav .nav-item a .badge-pill.danger { background: rgba(239,68,68,0.2); color: #fca5a5; }
    .sidebar-nav .nav-item a .badge-pill.info { background: rgba(59,130,246,0.2); color: #93c5fd; }

    .sidebar-nav .nav-item:hover a {
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.8);
    }

    .sidebar-nav .nav-item a.active {
        background: rgba(102,126,234,0.12);
        color: #fff;
        border: 1px solid rgba(102,126,234,0.15);
    }

    .sidebar-nav .nav-item a.active i {
        color: var(--primary);
    }

    .sidebar-nav .nav-item a .nav-indicator {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 22px;
        background: linear-gradient(180deg, var(--primary), var(--secondary));
        border-radius: 0 4px 4px 0;
        opacity: 0;
    }

    .sidebar-nav .nav-item a.active .nav-indicator { opacity: 1; }

    .sidebar-footer {
        padding: 16px 18px;
        border-top: 1px solid rgba(255,255,255,0.04);
        margin-top: auto;
    }

    .sidebar-footer .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        background: rgba(255,255,255,0.03);
        margin-bottom: 10px;
    }

    .sidebar-footer .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .sidebar-footer .user-details .name {
        font-weight: 600;
        font-size: 13px;
        color: rgba(255,255,255,0.8);
    }
    .sidebar-footer .user-details .role {
        font-size: 10px;
        color: rgba(255,255,255,0.25);
        text-transform: uppercase;
    }

    .sidebar-footer .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px;
        border-radius: 8px;
        background: rgba(255,255,255,0.02);
        color: rgba(255,255,255,0.3);
        text-decoration: none;
        font-size: 13px;
        border: 1px solid rgba(255,255,255,0.04);
    }

    .sidebar-footer .logout-btn:hover {
        background: rgba(239,68,68,0.1);
        color: var(--danger);
    }

    /* ===== MAIN CONTENT ===== */
    .admin-content {
        flex: 1;
        padding: 30px 35px 40px;
        background: var(--bg);
        min-height: calc(100vh - 80px);
    }

    /* ===== MOBILE TOGGLE ===== */
    .mobile-sidebar-toggle {
        display: none;
        background: none;
        border: none;
        color: var(--dark);
        font-size: 24px;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        height: calc(100vh - 80px);
        background: rgba(0,0,0,0.5);
        z-index: 999;
        backdrop-filter: blur(4px);
    }

    .sidebar-overlay.active { display: block; }

    @media (max-width: 768px) {
        .admin-sidebar {
            position: fixed;
            left: -100%;
            top: 80px;
            width: 280px;
            height: calc(100vh - 80px);
            z-index: 1000;
        }
        .admin-sidebar.open { left: 0; }
        .admin-content { padding: 15px 18px; width: 100%; }
        .mobile-sidebar-toggle { display: flex !important; align-items: center; justify-content: center; }
        .stats-grid-premium { grid-template-columns: 1fr 1fr; gap: 12px; }
        .page-header-modern { flex-direction: column; align-items: flex-start; }
    }

    @media (max-width: 480px) {
        .admin-content { padding: 10px 12px; }
        .stats-grid-premium { grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-card-premium { padding: 12px 14px; }
        .stat-card-premium .stat-number { font-size: 18px; }
    }

    /* ===== STATISTICS CARDS ===== */
    .stats-grid-premium {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card-premium {
        background: white;
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid var(--gray-lighter);
        position: relative;
        overflow: hidden;
    }

    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .stat-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .stat-card-premium .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-card-premium .stat-info { flex: 1; }
    .stat-card-premium .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }
    .stat-card-premium .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--gray);
        margin: 0;
    }

    .stat-doctor::before { background: linear-gradient(90deg, var(--primary), var(--secondary)); }
    .stat-doctor .stat-icon { background: rgba(102,126,234,0.12); color: var(--primary); }

    .stat-lab::before { background: linear-gradient(90deg, #f093fb, #f5576c); }
    .stat-lab .stat-icon { background: rgba(245,87,108,0.12); color: #f5576c; }

    .stat-speciality::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
    .stat-speciality .stat-icon { background: rgba(79,172,254,0.12); color: #4facfe; }

    .stat-pharmacy::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }
    .stat-pharmacy .stat-icon { background: rgba(67,233,123,0.12); color: #2ecc71; }

    .stat-medicine::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .stat-medicine .stat-icon { background: rgba(251,191,36,0.12); color: #f59e0b; }

    /* ===== PAGE HEADER ===== */
    .page-header-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header-modern .header-left h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .page-header-modern .header-left h2 i {
        color: var(--primary);
        margin-right: 10px;
    }

    .page-header-modern .header-left .breadcrumb-custom {
        font-size: 13px;
        color: var(--gray);
        margin-top: 4px;
    }

    .page-header-modern .header-left .breadcrumb-custom span {
        color: var(--primary);
        font-weight: 500;
    }

    .page-header-modern .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .page-header-modern .header-actions .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 13px;
        transition: var(--transition);
        border: none;
        color: white;
        text-decoration: none;
    }

    .page-header-modern .header-actions .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .page-header-modern .header-actions .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    /* ===== CARDS ===== */
    .card-premium {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        background: white;
    }

    .card-premium .card-header {
        background: white;
        border-bottom: 1px solid var(--gray-lighter);
        padding: 18px 24px;
        font-weight: 700;
    }

    .card-premium .card-body {
        padding: 24px;
    }

    /* ===== MANAGE CONTENT CARDS WITH ACTIONS ===== */
    .manage-card {
        background: #f8fafc;
        border-radius: var(--radius);
        padding: 20px;
        text-align: center;
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
    }

    .manage-card:hover {
        border-color: var(--primary);
        background: #eef2ff;
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .manage-card .icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .manage-card .title {
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        font-size: 14px;
    }

    .manage-card .count {
        font-size: 12px;
        color: var(--gray);
    }

    .manage-card .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .manage-card .action-buttons .btn {
        padding: 4px 12px;
        font-size: 10px;
        border-radius: 6px;
        border: none;
        color: white;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .manage-card .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-sm-action {
        padding: 4px 12px;
        font-size: 10px;
        border-radius: 6px;
        border: none;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: var(--transition);
    }

    .btn-sm-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-view-action { background: var(--info); }
    .btn-edit-action { background: var(--warning); }
    .btn-delete-action { background: var(--danger); }

    /* ==========================================
           RESPONSIVE
           ========================================== */
    @media (max-width: 992px) {
        .stats-grid-premium {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid-premium {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-grid-premium {
            grid-template-columns: 1fr 1fr;
        }
        .manage-card .action-buttons .btn {
            font-size: 9px;
            padding: 3px 8px;
        }
    }
</style>

<div class="admin-dashboard-wrapper">
    <!-- ===== SIDEBAR OVERLAY ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon"><i class="fas fa-heartbeat"></i></div>
                <div class="brand-text">
                    <h4>Docpedia</h4>
                    <small><i class="fas fa-circle"></i> Admin Panel <i class="fas fa-circle"></i> v3.0</small>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            
            <div class="nav-item">
                <a href="?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-th-large"></i>
                    <span class="nav-text">Dashboard</span>
                    <span class="badge-pill info">Live</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=doctors" class="<?php echo ($page == 'doctors') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-user-md"></i>
                    <span class="nav-text">Doctors</span>
                    <span class="badge-pill primary"><?php echo $total_doctors; ?></span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=labs" class="<?php echo ($page == 'labs') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-flask"></i>
                    <span class="nav-text">Labs</span>
                    <span class="badge-pill success"><?php echo $total_labs; ?></span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=specialities" class="<?php echo ($page == 'specialities') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-stethoscope"></i>
                    <span class="nav-text">Specialities</span>
                    <span class="badge-pill warning"><?php echo $total_specialities; ?></span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=pharmacies" class="<?php echo ($page == 'pharmacies') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-hospital"></i>
                    <span class="nav-text">Pharmacies</span>
                    <span class="badge-pill danger"><?php echo $total_pharmacies; ?></span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=medicines" class="<?php echo ($page == 'medicines') ? 'active' : ''; ?>">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-pills"></i>
                    <span class="nav-text">Medicines</span>
                    <span class="badge-pill warning"><?php echo $total_medicines; ?></span>
                </a>
            </div>

            <div class="nav-section">Management</div>

            <div class="nav-item">
                <a href="?page=add-doctor">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-user-plus"></i>
                    <span class="nav-text">Add Doctor</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=add-lab">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-flask"></i>
                    <span class="nav-text">Add Lab</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=add-medicine">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-pills"></i>
                    <span class="nav-text">Add Medicine</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="dashboard-appointments.php">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-calendar-check"></i>
                    <span class="nav-text">Appointments</span>
                    <span class="badge-pill info">View</span>
                </a>
            </div>

            <div class="nav-section">Settings</div>

            <div class="nav-item">
                <a href="#">
                    <span class="nav-indicator"></span>
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <div class="name">Admin User</div>
                    <div class="role">Super Administrator</div>
                </div>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="admin-content">
        <div class="page-header-modern">
            <div class="header-left">
                <button class="mobile-sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>
                    <i class="fas fa-tachometer-alt"></i>
                    <?php 
                    $page_titles = [
                        'dashboard' => 'Dashboard',
                        'doctors' => 'Manage Doctors',
                        'add-doctor' => 'Add New Doctor',
                        'edit-doctor' => 'Edit Doctor',
                        'labs' => 'Manage Labs',
                        'add-lab' => 'Add New Lab',
                        'specialities' => 'Manage Specialities',
                        'pharmacies' => 'Manage Pharmacies',
                        'medicines' => 'Manage Medicines',
                        'add-medicine' => 'Add New Medicine',
                        'add-pharmacy' => 'Add New Pharmacy'
                    ];
                    echo isset($page_titles[$page]) ? $page_titles[$page] : 'Dashboard';
                    ?>
                </h2>
                <div class="breadcrumb-custom">
                    <i class="fas fa-home"></i> / Admin / <span><?php echo isset($page_titles[$page]) ? $page_titles[$page] : 'Dashboard'; ?></span>
                </div>
            </div>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- ===== MESSAGE ALERT ===== -->
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show mb-4" role="alert" style="border-radius:12px;border-left:4px solid <?php echo ($msg_type == 'success') ? '#10b981' : '#ef4444'; ?>;">
                <i class="fas fa-<?php echo ($msg_type == 'success') ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($page == 'dashboard'): ?>
        
        <!-- ===== STATISTICS CARDS ===== -->
        <div class="stats-grid-premium">
            <a href="?page=doctors" class="stat-card-premium stat-doctor">
                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_doctors; ?></div>
                    <p class="stat-label">Total Doctors</p>
                </div>
            </a>

            <a href="?page=labs" class="stat-card-premium stat-lab">
                <div class="stat-icon"><i class="fas fa-flask"></i></div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_labs; ?></div>
                    <p class="stat-label">Total Labs</p>
                </div>
            </a>

            <a href="?page=specialities" class="stat-card-premium stat-speciality">
                <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_specialities; ?></div>
                    <p class="stat-label">Specialities</p>
                </div>
            </a>

            <a href="?page=pharmacies" class="stat-card-premium stat-pharmacy">
                <div class="stat-icon"><i class="fas fa-hospital"></i></div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_pharmacies; ?></div>
                    <p class="stat-label">Pharmacies</p>
                </div>
            </a>

            <a href="?page=medicines" class="stat-card-premium stat-medicine">
                <div class="stat-icon"><i class="fas fa-pills"></i></div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_medicines; ?></div>
                    <p class="stat-label">Medicines</p>
                </div>
            </a>
        </div>

        <!-- ===== QUICK ACTIONS ===== -->
        <div class="card-premium mb-4">
            <div class="card-header">
                <i class="fas fa-bolt" style="color: var(--primary);"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <a href="?page=add-doctor" class="btn" style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;border:none;border-radius:10px;padding:14px;font-weight:600;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;width:100%;">
                            <i class="fas fa-user-md"></i> Add Doctor
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="?page=add-lab" class="btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:10px;padding:14px;font-weight:600;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;width:100%;">
                            <i class="fas fa-flask"></i> Add Lab
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="?page=add-medicine" class="btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;border-radius:10px;padding:14px;font-weight:600;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;width:100%;">
                            <i class="fas fa-pills"></i> Add Medicine
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="?page=add-pharmacy" class="btn" style="background:linear-gradient(135deg,#f6ad55,#ed8936);color:white;border:none;border-radius:10px;padding:14px;font-weight:600;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;width:100%;">
                            <i class="fas fa-hospital"></i> Add Pharmacy
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="?page=specialities" class="btn" style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;border:none;border-radius:10px;padding:14px;font-weight:600;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;width:100%;">
                            <i class="fas fa-stethoscope"></i> Add Speciality
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== MANAGE CONTENT WITH ACTION BUTTONS ===== -->
        <div class="card-premium">
            <div class="card-header">
                <i class="fas fa-list" style="color: var(--primary);"></i> Manage Content
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- ===== DOCTORS ===== -->
                    <div class="col-md-2 col-6">
                        <div class="manage-card">
                            <div class="icon" style="color:var(--primary);"><i class="fas fa-user-md"></i></div>
                            <p class="title">Doctors</p>
                            <p class="count"><?php echo $total_doctors; ?> records</p>
                            <div class="action-buttons">
                                <a href="?page=doctors" class="btn btn-view-action" title="View All">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?page=add-doctor" class="btn btn-edit-action" title="Add New">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ===== LABS ===== -->
                    <div class="col-md-2 col-6">
                        <div class="manage-card">
                            <div class="icon" style="color:#f5576c;"><i class="fas fa-flask"></i></div>
                            <p class="title">Labs</p>
                            <p class="count"><?php echo $total_labs; ?> records</p>
                            <div class="action-buttons">
                                <a href="?page=labs" class="btn btn-view-action" title="View All">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?page=add-lab" class="btn btn-edit-action" title="Add New">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SPECIALITIES ===== -->
                    <div class="col-md-2 col-6">
                        <div class="manage-card">
                            <div class="icon" style="color:#4facfe;"><i class="fas fa-stethoscope"></i></div>
                            <p class="title">Specialities</p>
                            <p class="count"><?php echo $total_specialities; ?> records</p>
                            <div class="action-buttons">
                                <a href="?page=specialities" class="btn btn-view-action" title="View All">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?page=specialities" class="btn btn-edit-action" title="Add New">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ===== PHARMACIES ===== -->
                    <div class="col-md-2 col-6">
                        <div class="manage-card">
                            <div class="icon" style="color:#10b981;"><i class="fas fa-hospital"></i></div>
                            <p class="title">Pharmacies</p>
                            <p class="count"><?php echo $total_pharmacies; ?> records</p>
                            <div class="action-buttons">
                                <a href="?page=pharmacies" class="btn btn-view-action" title="View All">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?page=add-pharmacy" class="btn btn-edit-action" title="Add New">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ===== MEDICINES ===== -->
                    <div class="col-md-2 col-6">
                        <div class="manage-card">
                            <div class="icon" style="color:#f59e0b;"><i class="fas fa-pills"></i></div>
                            <p class="title">Medicines</p>
                            <p class="count"><?php echo $total_medicines; ?> records</p>
                            <div class="action-buttons">
                                <a href="?page=medicines" class="btn btn-view-action" title="View All">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?page=add-medicine" class="btn btn-edit-action" title="Add New">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        
        <!-- ===== DYNAMIC CONTENT ===== -->
        <?php
        switch($page) {
            case 'doctors':
                $doctors = mysqli_query($connect, "SELECT * FROM doctors ORDER BY doc_id DESC");
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-user-md" style="color:var(--primary);"></i> Manage Doctors</h4>
                    <a href="?page=add-doctor" class="btn" style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;border:none;border-radius:8px;padding:8px 18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                </div>
                
                <div class="table-responsive" style="background:white;border-radius:12px;padding:0;overflow:auto;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <table class="table table-hover" style="margin:0;font-size:13px;min-width:900px;">
                        <thead style="background:#1a1a2e;color:white;">
                            <tr>
                                <th style="padding:12px 15px;">ID</th>
                                <th style="padding:12px 15px;">Image</th>
                                <th style="padding:12px 15px;">Name</th>
                                <th style="padding:12px 15px;">Specialization</th>
                                <th style="padding:12px 15px;">Hospital</th>
                                <th style="padding:12px 15px;">Fee</th>
                                <th style="padding:12px 15px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($doctors) > 0): ?>
                            <?php while($doctor = mysqli_fetch_assoc($doctors)) { ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $doctor['doc_id']; ?></td>
                                <td style="padding:10px 15px;">
                                    <?php if(!empty($doctor['doc_image']) && file_exists($doctor['doc_image'])): ?>
                                        <img src="<?php echo $doctor['doc_image']; ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                    <?php else: ?>
                                        <img src="images/uploads/no.png" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                    <?php endif; ?>
                                </td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($doctor['doc_name']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($doctor['spec']); ?></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($doctor['hos_address']); ?></td>
                                <td style="padding:10px 15px;"><span class="badge" style="background:#10b981;color:white;padding:4px 12px;border-radius:20px;">Rs. <?php echo $doctor['fee']; ?></span></td>
                                <td style="padding:10px 15px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                                        <a href="single.php?hid=<?php echo $doctor['doc_id']; ?>" class="btn btn-sm" style="background:#3b82f6;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="?page=edit-doctor&edit_doctor=<?php echo $doctor['doc_id']; ?>" class="btn btn-sm" style="background:#f59e0b;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?page=doctors&delete_doctor=<?php echo $doctor['doc_id']; ?>" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php else: ?>
                            <tr><td colspan="7" style="text-align:center;padding:40px 20px;"><i class="fas fa-user-md" style="font-size:40px;color:#ddd;"></i><p class="mt-2">No doctors found.</p></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            
            // ============ ADD DOCTOR ============
            case 'add-doctor':
                ?>
                <div class="card" style="border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:30px;">
                        <form method="POST" action="?page=add-doctor" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Doctor Name <span class="text-danger">*</span></label>
                                        <input type="text" name="doc_name" class="form-control" placeholder="Enter doctor name" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Specialization <span class="text-danger">*</span></label>
                                        <select name="spec" class="form-control" required style="border-radius:8px;padding:12px;">
                                            <option value="">Select Specialization</option>
                                            <?php while($row = mysqli_fetch_assoc($specialities_list)): ?>
                                                <option value="<?php echo $row['disease_name']; ?>"><?php echo $row['disease_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Category</label>
                                        <select name="category" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="Doctors">Doctors</option>
                                            <option value="Clinics">Clinics</option>
                                            <option value="Labs">Labs</option>
                                            <option value="Pharmacies">Pharmacies</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Experience (Years) <span class="text-danger">*</span></label>
                                        <input type="number" name="exp" class="form-control" placeholder="e.g., 5" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Consultation Fee <span class="text-danger">*</span></label>
                                        <input type="number" name="fee" class="form-control" placeholder="e.g., 1000" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Hospital/Clinic Address <span class="text-danger">*</span></label>
                                        <input type="text" name="hos_address" class="form-control" placeholder="Enter hospital address" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Available Days <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" placeholder="e.g., Mon-Fri" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Available Time <span class="text-danger">*</span></label>
                                        <input type="text" name="time" class="form-control" placeholder="e.g., 9:00 AM - 5:00 PM" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Qualification</label>
                                        <input type="text" name="qualification" class="form-control" placeholder="e.g., MBBS, FCPS" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">About Doctor</label>
                                        <textarea name="about" class="form-control" rows="3" placeholder="Enter doctor description" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Doctor Image</label>
                                        <input type="file" name="doc_image" class="form-control" accept="image/*" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="add_doctor" class="btn" style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;border:none;border-radius:8px;padding:12px 30px;font-weight:600;">
                                        <i class="fas fa-save"></i> Add Doctor
                                    </button>
                                    <a href="?page=doctors" class="btn btn-secondary" style="border-radius:8px;padding:12px 30px;text-decoration:none;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;
            
            // ============ EDIT DOCTOR ============
            case 'edit-doctor':
                if($edit_doctor):
                ?>
                <div class="card" style="border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:30px;">
                        <form method="POST" action="?page=edit-doctor" enctype="multipart/form-data">
                            <input type="hidden" name="doc_id" value="<?php echo $edit_doctor['doc_id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Doctor Name <span class="text-danger">*</span></label>
                                        <input type="text" name="doc_name" class="form-control" value="<?php echo htmlspecialchars($edit_doctor['doc_name']); ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Specialization <span class="text-danger">*</span></label>
                                        <select name="spec" class="form-control" required style="border-radius:8px;padding:12px;">
                                            <option value="">Select Specialization</option>
                                            <?php 
                                            mysqli_data_seek($specialities_list, 0);
                                            while($row = mysqli_fetch_assoc($specialities_list)): 
                                            ?>
                                                <option value="<?php echo $row['disease_name']; ?>" <?php echo ($row['disease_name'] == $edit_doctor['spec']) ? 'selected' : ''; ?>>
                                                    <?php echo $row['disease_name']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Category</label>
                                        <select name="category" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="Doctors" <?php echo ($edit_doctor['category'] == 'Doctors') ? 'selected' : ''; ?>>Doctors</option>
                                            <option value="Clinics" <?php echo ($edit_doctor['category'] == 'Clinics') ? 'selected' : ''; ?>>Clinics</option>
                                            <option value="Labs" <?php echo ($edit_doctor['category'] == 'Labs') ? 'selected' : ''; ?>>Labs</option>
                                            <option value="Pharmacies" <?php echo ($edit_doctor['category'] == 'Pharmacies') ? 'selected' : ''; ?>>Pharmacies</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Experience (Years) <span class="text-danger">*</span></label>
                                        <input type="number" name="exp" class="form-control" value="<?php echo $edit_doctor['exp']; ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Consultation Fee <span class="text-danger">*</span></label>
                                        <input type="number" name="fee" class="form-control" value="<?php echo $edit_doctor['fee']; ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo $edit_doctor['phone']; ?>" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Hospital/Clinic Address <span class="text-danger">*</span></label>
                                        <input type="text" name="hos_address" class="form-control" value="<?php echo htmlspecialchars($edit_doctor['hos_address']); ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Available Days <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" value="<?php echo $edit_doctor['days']; ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Available Time <span class="text-danger">*</span></label>
                                        <input type="text" name="time" class="form-control" value="<?php echo $edit_doctor['time']; ?>" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo $edit_doctor['email']; ?>" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Qualification</label>
                                        <input type="text" name="qualification" class="form-control" value="<?php echo $edit_doctor['qualification']; ?>" placeholder="e.g., MBBS, FCPS" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">About Doctor</label>
                                        <textarea name="about" class="form-control" rows="3" style="border-radius:8px;padding:12px;"><?php echo htmlspecialchars($edit_doctor['about']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Current Image</label>
                                        <?php if(!empty($edit_doctor['doc_image']) && file_exists($edit_doctor['doc_image'])): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo $edit_doctor['doc_image']; ?>" style="width:100px;height:100px;border-radius:10px;object-fit:cover;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="doc_image" class="form-control" accept="image/*" style="border-radius:8px;padding:12px;">
                                        <small class="text-muted">Leave empty to keep current image</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="update_doctor" class="btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;border-radius:8px;padding:12px 30px;font-weight:600;">
                                        <i class="fas fa-save"></i> Update Doctor
                                    </button>
                                    <a href="?page=doctors" class="btn btn-secondary" style="border-radius:8px;padding:12px 30px;text-decoration:none;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                else:
                    echo '<div class="alert alert-danger">Doctor not found!</div>';
                endif;
                break;
            
            // ============ LABS ============
            case 'labs':
                $labs = mysqli_query($connect, "SELECT * FROM labs ORDER BY lab_id DESC");
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-flask" style="color:#10b981;"></i> Manage Labs</h4>
                    <a href="?page=add-lab" class="btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:8px;padding:8px 18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                </div>
                
                <div class="table-responsive" style="background:white;border-radius:12px;padding:0;overflow:auto;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <table class="table table-hover" style="margin:0;font-size:13px;min-width:800px;">
                        <thead style="background:#1a1a2e;color:white;">
                            <tr>
                                <th style="padding:12px 15px;">ID</th>
                                <th style="padding:12px 15px;">Lab Name</th>
                                <th style="padding:12px 15px;">Address</th>
                                <th style="padding:12px 15px;">Phone</th>
                                <th style="padding:12px 15px;">Email</th>
                                <th style="padding:12px 15px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($labs) > 0): ?>
                            <?php while($lab = mysqli_fetch_assoc($labs)) { ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $lab['lab_id']; ?></td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($lab['lab_name']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($lab['address']); ?></td>
                                <td style="padding:10px 15px;"><?php echo $lab['phone']; ?></td>
                                <td style="padding:10px 15px;"><?php echo $lab['email']; ?></td>
                                <td style="padding:10px 15px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                                        <a href="?page=labs&delete_lab=<?php echo $lab['lab_id']; ?>" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:40px 20px;">No labs found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            
            // ============ ADD LAB ============
            case 'add-lab':
                ?>
                <div class="card" style="border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:30px;">
                        <form method="POST" action="?page=add-lab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Lab Name <span class="text-danger">*</span></label>
                                        <input type="text" name="lab_name" class="form-control" placeholder="Enter lab name" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Enter address" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">About</label>
                                        <textarea name="about" class="form-control" rows="3" placeholder="Enter lab description" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Website</label>
                                        <input type="text" name="website" class="form-control" placeholder="Enter website URL" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="add_lab" class="btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:8px;padding:12px 30px;font-weight:600;">
                                        <i class="fas fa-save"></i> Add Lab
                                    </button>
                                    <a href="?page=labs" class="btn btn-secondary" style="border-radius:8px;padding:12px 30px;text-decoration:none;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;
            
            // ============ SPECIALITIES ============
            case 'specialities':
                $diseases = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_id DESC");
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-stethoscope" style="color:#3b82f6;"></i> Manage Specialities</h4>
                </div>
                
                <div class="card mb-4" style="border:none;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:24px;">
                        <form method="POST" action="?page=specialities">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" name="speciality_name" class="form-control" placeholder="Speciality Name" required style="border-radius:8px;padding:12px;">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="speciality_description" class="form-control" placeholder="Description (optional)" style="border-radius:8px;padding:12px;">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="add_speciality" class="btn" style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;border:none;border-radius:8px;padding:12px;width:100%;font-weight:600;">
                                        <i class="fas fa-save"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive" style="background:white;border-radius:12px;padding:0;overflow:auto;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <table class="table table-hover" style="margin:0;font-size:13px;">
                        <thead style="background:#1a1a2e;color:white;">
                            <tr>
                                <th style="padding:12px 15px;">ID</th>
                                <th style="padding:12px 15px;">Speciality Name</th>
                                <th style="padding:12px 15px;">Description</th>
                                <th style="padding:12px 15px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($diseases) > 0): ?>
                            <?php while($disease = mysqli_fetch_assoc($diseases)) { ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $disease['disease_id']; ?></td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($disease['disease_name']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($disease['disease_description'] ?? 'N/A'); ?></td>
                                <td style="padding:10px 15px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;">
                                        <a href="?page=specialities&delete_speciality=<?php echo $disease['disease_id']; ?>" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;padding:40px 20px;">No specialities found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            
            // ============ PHARMACIES ============
            case 'pharmacies':
                $pharmacies = mysqli_query($connect, "SELECT * FROM pharmacies ORDER BY pharmacy_id DESC");
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-hospital" style="color:#f6ad55;"></i> Manage Pharmacies</h4>
                    <a href="?page=add-pharmacy" class="btn" style="background:linear-gradient(135deg,#f6ad55,#ed8936);color:white;border:none;border-radius:8px;padding:8px 18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                </div>
                
                <div class="table-responsive" style="background:white;border-radius:12px;padding:0;overflow:auto;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <table class="table table-hover" style="margin:0;font-size:13px;min-width:800px;">
                        <thead style="background:#1a1a2e;color:white;">
                            <tr>
                                <th style="padding:12px 15px;">ID</th>
                                <th style="padding:12px 15px;">Pharmacy Name</th>
                                <th style="padding:12px 15px;">Address</th>
                                <th style="padding:12px 15px;">Phone</th>
                                <th style="padding:12px 15px;">Email</th>
                                <th style="padding:12px 15px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($pharmacies) > 0): ?>
                            <?php while($pharmacy = mysqli_fetch_assoc($pharmacies)) { ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $pharmacy['pharmacy_id']; ?></td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($pharmacy['pharmacy_name']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($pharmacy['address']); ?></td>
                                <td style="padding:10px 15px;"><?php echo $pharmacy['phone']; ?></td>
                                <td style="padding:10px 15px;"><?php echo $pharmacy['email']; ?></td>
                                <td style="padding:10px 15px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;">
                                        <a href="?page=pharmacies&delete_pharmacy=<?php echo $pharmacy['pharmacy_id']; ?>" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:40px 20px;">No pharmacies found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            
            // ============ ADD PHARMACY ============
            case 'add-pharmacy':
                ?>
                <div class="card" style="border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:30px;">
                        <form method="POST" action="?page=add-pharmacy" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Pharmacy Name <span class="text-danger">*</span></label>
                                        <input type="text" name="pharmacy_name" class="form-control" placeholder="Enter pharmacy name" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Status</label>
                                        <select name="status" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Enter address" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">About</label>
                                        <textarea name="about" class="form-control" rows="3" placeholder="Enter pharmacy description" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Website</label>
                                        <input type="text" name="website" class="form-control" placeholder="www.example.com" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Image</label>
                                        <input type="file" name="img" class="form-control" accept="image/*" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="add_pharmacy" class="btn" style="background:linear-gradient(135deg,#f6ad55,#ed8936);color:white;border:none;border-radius:8px;padding:12px 30px;font-weight:600;">
                                        <i class="fas fa-save"></i> Add Pharmacy
                                    </button>
                                    <a href="?page=pharmacies" class="btn btn-secondary" style="border-radius:8px;padding:12px 30px;text-decoration:none;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;
            
            // ============ MEDICINES ============
            case 'medicines':
                $medicines = mysqli_query($connect, "SELECT * FROM medicines ORDER BY medicine_id DESC");
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-pills" style="color:#f59e0b;"></i> Manage Medicines</h4>
                    <a href="?page=add-medicine" class="btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;border-radius:8px;padding:8px 18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                </div>
                
                <div class="table-responsive" style="background:white;border-radius:12px;padding:0;overflow:auto;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <table class="table table-hover" style="margin:0;font-size:13px;min-width:900px;">
                        <thead style="background:#1a1a2e;color:white;">
                            <tr>
                                <th style="padding:12px 15px;">ID</th>
                                <th style="padding:12px 15px;">Medicine Name</th>
                                <th style="padding:12px 15px;">Generic Name</th>
                                <th style="padding:12px 15px;">Category</th>
                                <th style="padding:12px 15px;">Price</th>
                                <th style="padding:12px 15px;">Stock</th>
                                <th style="padding:12px 15px;">Status</th>
                                <th style="padding:12px 15px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($medicines) > 0): ?>
                            <?php while($medicine = mysqli_fetch_assoc($medicines)) { 
                                $status = $medicine['status'] ?? 'active';
                            ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $medicine['medicine_id']; ?></td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($medicine['medicine_name']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($medicine['generic_name'] ?? 'N/A'); ?></td>
                                <td style="padding:10px 15px;"><?php echo htmlspecialchars($medicine['category'] ?? 'N/A'); ?></td>
                                <td style="padding:10px 15px;"><span class="badge" style="background:#10b981;color:white;padding:4px 12px;border-radius:20px;">Rs. <?php echo number_format($medicine['price'], 2); ?></span></td>
                                <td style="padding:10px 15px;"><?php echo $medicine['stock']; ?></td>
                                <td style="padding:10px 15px;">
                                    <span class="badge" style="background:<?php echo ($status == 'active') ? '#10b981' : '#ef4444'; ?>;color:white;padding:4px 12px;border-radius:20px;">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td style="padding:10px 15px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                                        <a href="?page=medicines&delete_medicine=<?php echo $medicine['medicine_id']; ?>" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:10px;" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php else: ?>
                            <tr><td colspan="8" style="text-align:center;padding:40px 20px;"><i class="fas fa-pills" style="font-size:40px;color:#ddd;"></i><p class="mt-2">No medicines found.</p></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            
            // ============ ADD MEDICINE ============
            case 'add-medicine':
                ?>
                <div class="card" style="border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body" style="padding:30px;">
                        <form method="POST" action="?page=add-medicine" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Medicine Name <span class="text-danger">*</span></label>
                                        <input type="text" name="medicine_name" class="form-control" placeholder="Enter medicine name" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Generic Name</label>
                                        <input type="text" name="generic_name" class="form-control" placeholder="Enter generic name" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Category</label>
                                        <select name="category" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="">Select Category</option>
                                            <option value="Pain Relief">Pain Relief</option>
                                            <option value="Antibiotics">Antibiotics</option>
                                            <option value="Blood Pressure">Blood Pressure</option>
                                            <option value="Diabetes">Diabetes</option>
                                            <option value="Gastrointestinal">Gastrointestinal</option>
                                            <option value="Respiratory">Respiratory</option>
                                            <option value="Antihistamine">Antihistamine</option>
                                            <option value="Antidepressant">Antidepressant</option>
                                            <option value="Cholesterol">Cholesterol</option>
                                            <option value="Anxiety">Anxiety</option>
                                            <option value="Vitamin">Vitamin</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Manufacturer</label>
                                        <input type="text" name="manufacturer" class="form-control" placeholder="Enter manufacturer name" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="fw-bold">Price (Rs.) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="fw-bold">Stock <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control" placeholder="0" required style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="fw-bold">Status</label>
                                        <select name="status" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Strength</label>
                                        <input type="text" name="strength" class="form-control" placeholder="e.g., 500mg" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Dosage Form</label>
                                        <select name="dosage_form" class="form-control" style="border-radius:8px;padding:12px;">
                                            <option value="">Select Dosage Form</option>
                                            <option value="Tablet">Tablet</option>
                                            <option value="Capsule">Capsule</option>
                                            <option value="Syrup">Syrup</option>
                                            <option value="Injection">Injection</option>
                                            <option value="Inhaler">Inhaler</option>
                                            <option value="Cream">Cream</option>
                                            <option value="Ointment">Ointment</option>
                                            <option value="Drops">Drops</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Enter medicine description" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Side Effects</label>
                                        <textarea name="side_effects" class="form-control" rows="2" placeholder="Enter side effects" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Precautions</label>
                                        <textarea name="precautions" class="form-control" rows="2" placeholder="Enter precautions" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">How to Use</label>
                                        <textarea name="how_to_use" class="form-control" rows="2" placeholder="Enter how to use instructions" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Ingredients</label>
                                        <textarea name="ingredients" class="form-control" rows="2" placeholder="Enter ingredients" style="border-radius:8px;padding:12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="fw-bold">Image</label>
                                        <input type="file" name="img" class="form-control" accept="image/*" style="border-radius:8px;padding:12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="add_medicine" class="btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;border-radius:8px;padding:12px 30px;font-weight:600;">
                                        <i class="fas fa-save"></i> Add Medicine
                                    </button>
                                    <a href="?page=medicines" class="btn btn-secondary" style="border-radius:8px;padding:12px 30px;text-decoration:none;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;
            
            default:
                echo '<div class="alert alert-warning">Page not found!</div>';
                break;
        }
        ?>
        
        <?php endif; ?>
    </main>
</div>

<script>
// ===== SIDEBAR TOGGLE =====
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

// ===== CLOSE SIDEBAR ON ESCAPE =====
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
    }
});

// ===== AUTO CLOSE ALERTS =====
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
});
</script>

<?php include('dashboard-footer.php'); ?>