<?php
// ===== START OUTPUT BUFFERING =====
ob_start();

session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}
include('connect.php');

// ===== PAGE PARAMETER =====
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Get all counts
$total_doctors = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM doctors"));
$total_labs = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM labs"));
$total_specialities = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM disease"));
$total_pharmacies = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pharmacies"));
$total_medicines = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines"));
$total_appointments = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM appointments"));
$total_blogs = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM blogs"));

// Get recent activity
$recent_doctors = mysqli_query($connect, "SELECT * FROM doctors ORDER BY doc_id DESC LIMIT 5");

// Get all specialities for dropdown
$specialities_list = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_name");

// ======================================================
// ===== CRUD OPERATIONS =====
// ======================================================

// ===== ADD BLOG =====
if(isset($_POST['add_blog'])) {
    $blog_title = mysqli_real_escape_string($connect, $_POST['blog_title']);
    $blog_description = mysqli_real_escape_string($connect, $_POST['blog_description']);
    $blog_content = mysqli_real_escape_string($connect, $_POST['blog_content']);
    $author = mysqli_real_escape_string($connect, $_POST['author']);
    $created_date = mysqli_real_escape_string($connect, $_POST['created_date']);
    
    $blog_img = '';
    if(isset($_FILES['blog_img']) && $_FILES['blog_img']['error'] == 0) {
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($_FILES['blog_img']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['blog_img']['tmp_name'], $target_file)) {
            $blog_img = $image_name;
        }
    }
    
    $insert = "INSERT INTO blogs (blog_title, blog_description, blog_content, blog_img, author, created_date) 
               VALUES ('$blog_title', '$blog_description', '$blog_content', '$blog_img', '$author', '$created_date')";
    
    if(mysqli_query($connect, $insert)) {
        echo '<script>window.location.href="?page=blogs&msg=Blog added successfully";</script>';
    } else {
        $msg = "Error: " . mysqli_error($connect);
        $msg_type = "danger";
    }
}

// ===== UPDATE BLOG =====
if(isset($_POST['update_blog'])) {
    $blog_id = mysqli_real_escape_string($connect, $_POST['blog_id']);
    $blog_title = mysqli_real_escape_string($connect, $_POST['blog_title']);
    $blog_description = mysqli_real_escape_string($connect, $_POST['blog_description']);
    $blog_content = mysqli_real_escape_string($connect, $_POST['blog_content']);
    $author = mysqli_real_escape_string($connect, $_POST['author']);
    $created_date = mysqli_real_escape_string($connect, $_POST['created_date']);
    
    $blog_img_update = '';
    if(isset($_FILES['blog_img']) && $_FILES['blog_img']['error'] == 0) {
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($_FILES['blog_img']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['blog_img']['tmp_name'], $target_file)) {
            $blog_img_update = ", blog_img = '$image_name'";
        }
    }
    
    $update = "UPDATE blogs SET 
                blog_title = '$blog_title',
                blog_description = '$blog_description',
                blog_content = '$blog_content',
                author = '$author',
                created_date = '$created_date'
                $blog_img_update
                WHERE blog_id = '$blog_id'";
    
    if(mysqli_query($connect, $update)) {
        echo '<script>window.location.href="?page=blogs&msg=Blog updated successfully";</script>';
    }
}

// ===== DELETE BLOG =====
if(isset($_GET['delete_blog'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_blog']);
    mysqli_query($connect, "DELETE FROM blogs WHERE blog_id = '$id'");
    echo '<script>window.location.href="?page=blogs&msg=Blog deleted successfully";</script>';
    exit();
}

// ===== ADD DOCTOR =====
if(isset($_POST['add_doctor'])) {
    $doc_name = mysqli_real_escape_string($connect, $_POST['doc_name']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $hos_address = mysqli_real_escape_string($connect, $_POST['hos_address']);
    $exp = mysqli_real_escape_string($connect, $_POST['exp']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    $disease_id = mysqli_real_escape_string($connect, $_POST['disease_id']);
    
    $image_path = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "uploads/doctors/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($_FILES['img']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }
    
    $insert = "INSERT INTO doctors (doc_name, spec, img, number, hos_address, exp, disease_id) 
               VALUES ('$doc_name', '$spec', '$image_path', '$number', '$hos_address', '$exp', '$disease_id')";
    
    if(mysqli_query($connect, $insert)) {
        echo '<script>window.location.href="?page=doctors&msg=Doctor added successfully";</script>';
    } else {
        $msg = "Error: " . mysqli_error($connect);
        $msg_type = "danger";
    }
}

// ===== UPDATE DOCTOR =====
if(isset($_POST['update_doctor'])) {
    $doc_id = mysqli_real_escape_string($connect, $_POST['doc_id']);
    $doc_name = mysqli_real_escape_string($connect, $_POST['doc_name']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $hos_address = mysqli_real_escape_string($connect, $_POST['hos_address']);
    $exp = mysqli_real_escape_string($connect, $_POST['exp']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    $disease_id = mysqli_real_escape_string($connect, $_POST['disease_id']);
    
    $update = "UPDATE doctors SET 
                doc_name = '$doc_name',
                spec = '$spec',
                number = '$number',
                hos_address = '$hos_address',
                exp = '$exp',
                disease_id = '$disease_id'
                WHERE doc_id = '$doc_id'";
    
    if(mysqli_query($connect, $update)) {
        echo '<script>window.location.href="?page=doctors&msg=Doctor updated successfully";</script>';
    }
}

// ===== DELETE DOCTOR =====
if(isset($_GET['delete_doctor'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_doctor']);
    mysqli_query($connect, "DELETE FROM doctors WHERE doc_id = '$id'");
    echo '<script>window.location.href="?page=doctors&msg=Doctor deleted successfully";</script>';
    exit();
}

// ===== ADD LAB =====
if(isset($_POST['add_lab'])) {
    $lab_name = mysqli_real_escape_string($connect, $_POST['lab_name']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    
    $image_path = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "uploads/labs/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($_FILES['img']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }
    
    $insert = "INSERT INTO labs (lab_name, address, phone, img) 
               VALUES ('$lab_name', '$address', '$phone', '$image_path')";
    
    if(mysqli_query($connect, $insert)) {
        echo '<script>window.location.href="?page=labs&msg=Lab added successfully";</script>';
    }
}

// ===== UPDATE LAB =====
if(isset($_POST['update_lab'])) {
    $lab_id = mysqli_real_escape_string($connect, $_POST['lab_id']);
    $lab_name = mysqli_real_escape_string($connect, $_POST['lab_name']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    
    $update = "UPDATE labs SET 
                lab_name = '$lab_name',
                address = '$address',
                phone = '$phone'
                WHERE lab_id = '$lab_id'";
    
    if(mysqli_query($connect, $update)) {
        echo '<script>window.location.href="?page=labs&msg=Lab updated successfully";</script>';
    }
}

// ===== DELETE LAB =====
if(isset($_GET['delete_lab'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_lab']);
    mysqli_query($connect, "DELETE FROM labs WHERE lab_id = '$id'");
    echo '<script>window.location.href="?page=labs&msg=Lab deleted successfully";</script>';
    exit();
}

// ===== ADD MEDICINE =====
if(isset($_POST['add_medicine'])) {
    $medicine_name = mysqli_real_escape_string($connect, $_POST['medicine_name']);
    $generic_name = mysqli_real_escape_string($connect, $_POST['generic_name']);
    $category = mysqli_real_escape_string($connect, $_POST['category']);
    $manufacturer = mysqli_real_escape_string($connect, $_POST['manufacturer']);
    $price = mysqli_real_escape_string($connect, $_POST['price']);
    $stock = mysqli_real_escape_string($connect, $_POST['stock']);
    $strength = mysqli_real_escape_string($connect, $_POST['strength']);
    $dosage_form = mysqli_real_escape_string($connect, $_POST['dosage_form']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $side_effects = mysqli_real_escape_string($connect, $_POST['side_effects']);
    $precautions = mysqli_real_escape_string($connect, $_POST['precautions']);
    $how_to_use = mysqli_real_escape_string($connect, $_POST['how_to_use']);
    $ingredients = mysqli_real_escape_string($connect, $_POST['ingredients']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    $img_name = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "uploads/medicines/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $img_name = time() . '_' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img_name);
    }
    
    $query = "INSERT INTO medicines (medicine_name, generic_name, category, manufacturer, price, stock, strength, dosage_form, description, side_effects, precautions, how_to_use, ingredients, status, img) 
              VALUES ('$medicine_name', '$generic_name', '$category', '$manufacturer', '$price', '$stock', '$strength', '$dosage_form', '$description', '$side_effects', '$precautions', '$how_to_use', '$ingredients', '$status', '$img_name')";
    
    if(mysqli_query($connect, $query)) {
        echo '<script>window.location.href="?page=medicines&msg=Medicine added successfully";</script>';
    }
}

// ===== DELETE MEDICINE =====
if(isset($_GET['delete_medicine'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_medicine']);
    mysqli_query($connect, "DELETE FROM medicines WHERE medicine_id = '$id'");
    echo '<script>window.location.href="?page=medicines&msg=Medicine deleted successfully";</script>';
    exit();
}

// ===== ADD PHARMACY =====
if(isset($_POST['add_pharmacy'])) {
    $pharmacy_name = mysqli_real_escape_string($connect, $_POST['pharmacy_name']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    $img_name = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "uploads/pharmacies/";
        if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $img_name = time() . '_' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img_name);
    }
    
    $query = "INSERT INTO pharmacies (pharmacy_name, address, phone, img, status) 
              VALUES ('$pharmacy_name', '$address', '$phone', '$img_name', '$status')";
    
    if(mysqli_query($connect, $query)) {
        echo '<script>window.location.href="?page=pharmacies&msg=Pharmacy added successfully";</script>';
    }
}

// ===== DELETE PHARMACY =====
if(isset($_GET['delete_pharmacy'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_pharmacy']);
    mysqli_query($connect, "DELETE FROM pharmacies WHERE pharmacy_id = '$id'");
    echo '<script>window.location.href="?page=pharmacies&msg=Pharmacy deleted successfully";</script>';
    exit();
}

// ===== ADD SPECIALITY =====
if(isset($_POST['add_speciality'])) {
    $name = mysqli_real_escape_string($connect, $_POST['speciality_name']);
    
    $check = mysqli_query($connect, "SELECT * FROM disease WHERE disease_name = '$name'");
    if(mysqli_num_rows($check) > 0) {
        $msg = "Speciality already exists!";
        $msg_type = "danger";
    } else {
        $insert = "INSERT INTO disease (disease_name) VALUES ('$name')";
        if(mysqli_query($connect, $insert)) {
            echo '<script>window.location.href="?page=specialities&msg=Speciality added successfully";</script>';
        }
    }
}

// ===== DELETE SPECIALITY =====
if(isset($_GET['delete_speciality'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete_speciality']);
    mysqli_query($connect, "DELETE FROM disease WHERE disease_id = '$id'");
    echo '<script>window.location.href="?page=specialities&msg=Speciality deleted successfully";</script>';
    exit();
}

// ===== UPDATE MEDICINE STATUS =====
if(isset($_GET['medicine_status']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $status = mysqli_real_escape_string($connect, $_GET['medicine_status']);
    mysqli_query($connect, "UPDATE medicines SET status = '$status' WHERE medicine_id = '$id'");
    echo '<script>window.location.href="?page=medicines&msg=Status updated to ' . $status . '";</script>';
    exit();
}

// ===== UPDATE PHARMACY STATUS =====
if(isset($_GET['pharmacy_status']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $status = mysqli_real_escape_string($connect, $_GET['pharmacy_status']);
    mysqli_query($connect, "UPDATE pharmacies SET status = '$status' WHERE pharmacy_id = '$id'");
    echo '<script>window.location.href="?page=pharmacies&msg=Status updated to ' . $status . '";</script>';
    exit();
}

// ===== SAVE TIMING =====
if(isset($_POST['save_timing'])) {
    $doc_id = mysqli_real_escape_string($connect, $_POST['doc_id']);
    $days = $_POST['days'] ?? [];
    $start_times = $_POST['start_time'] ?? [];
    $end_times = $_POST['end_time'] ?? [];
    $available = $_POST['available'] ?? [];
    
    // Delete existing timing for this doctor
    mysqli_query($connect, "DELETE FROM doctor_timing WHERE doc_id = '$doc_id'");
    
    // Insert new timing
    foreach($days as $day) {
        $start_time = isset($start_times[$day]) ? mysqli_real_escape_string($connect, $start_times[$day]) : '';
        $end_time = isset($end_times[$day]) ? mysqli_real_escape_string($connect, $end_times[$day]) : '';
        $is_available = isset($available[$day]) ? 1 : 0;
        
        $insert = "INSERT INTO doctor_timing (doc_id, day_of_week, start_time, end_time, is_available) 
                   VALUES ('$doc_id', '$day', '$start_time', '$end_time', '$is_available')";
        mysqli_query($connect, $insert);
    }
    
    echo '<script>window.location.href="?page=manage-timing&msg=Timing saved successfully";</script>';
    exit();
}

// ===== Get edit data =====
$edit_doctor = null;
if(isset($_GET['edit_doctor'])) {
    $edit_id = mysqli_real_escape_string($connect, $_GET['edit_doctor']);
    $edit_query = mysqli_query($connect, "SELECT * FROM doctors WHERE doc_id = '$edit_id'");
    $edit_doctor = mysqli_fetch_assoc($edit_query);
}

$edit_lab = null;
if(isset($_GET['edit_lab'])) {
    $edit_id = mysqli_real_escape_string($connect, $_GET['edit_lab']);
    $edit_query = mysqli_query($connect, "SELECT * FROM labs WHERE lab_id = '$edit_id'");
    $edit_lab = mysqli_fetch_assoc($edit_query);
}

// ===== Get edit blog data =====
$edit_blog = null;
if(isset($_GET['edit_blog'])) {
    $edit_id = mysqli_real_escape_string($connect, $_GET['edit_blog']);
    $edit_query = mysqli_query($connect, "SELECT * FROM blogs WHERE blog_id = '$edit_id'");
    $edit_blog = mysqli_fetch_assoc($edit_query);
}

include('dashboard-header.php');
?>

<!-- ======================================================
    COMPLETE SIDEBAR WITH ALL OPTIONS INCLUDING BLOGS
    ====================================================== -->
<style>
    /* ===== SIDEBAR STYLES ===== */
    .admin-sidebar {
        position: fixed;
        left: 0;
        top: 80px;
        width: 260px;
        height: calc(100vh - 80px);
        background: linear-gradient(180deg, #0f0f1f 0%, #1a1a2e 50%, #16213e 100%);
        color: #fff;
        padding: 0;
        overflow-y: auto;
        z-index: 100;
        transition: all 0.3s ease;
        border-right: 1px solid rgba(255,255,255,0.04);
    }

    .admin-sidebar::-webkit-scrollbar { width: 4px; }
    .admin-sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 10px; }

    .sidebar-brand {
        padding: 20px 20px 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background: rgba(255,255,255,0.02);
    }

    .sidebar-brand .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-brand .brand-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    }

    .sidebar-brand .brand-text h4 {
        font-weight: 700;
        font-size: 18px;
        margin: 0;
        color: #fff;
    }

    .sidebar-brand .brand-text small {
        font-size: 10px;
        color: rgba(255,255,255,0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidebar-nav {
        padding: 10px 0 20px;
    }

    .sidebar-nav .nav-section {
        padding: 15px 20px 6px;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.15);
        font-weight: 700;
    }

    .sidebar-nav .nav-item {
        margin: 1px 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sidebar-nav .nav-item a {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        color: rgba(255,255,255,0.4);
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        gap: 12px;
        transition: all 0.3s ease;
        position: relative;
    }

    .sidebar-nav .nav-item a i {
        width: 20px;
        font-size: 15px;
        text-align: center;
        color: rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }

    .sidebar-nav .nav-item a .badge-sidebar {
        margin-left: auto;
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.35);
        padding: 1px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .sidebar-nav .nav-item a .badge-sidebar.primary { background: rgba(102,126,234,0.2); color: #a78bfa; }
    .sidebar-nav .nav-item a .badge-sidebar.success { background: rgba(16,185,129,0.2); color: #6ee7b7; }
    .sidebar-nav .nav-item a .badge-sidebar.warning { background: rgba(245,158,11,0.2); color: #fcd34d; }
    .sidebar-nav .nav-item a .badge-sidebar.danger { background: rgba(239,68,68,0.2); color: #fca5a5; }
    .sidebar-nav .nav-item a .badge-sidebar.info { background: rgba(59,130,246,0.2); color: #93c5fd; }

    .sidebar-nav .nav-item a:hover {
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.8);
    }

    .sidebar-nav .nav-item a:hover i {
        color: rgba(255,255,255,0.5);
    }

    .sidebar-nav .nav-item a.active {
        background: rgba(102,126,234,0.12);
        color: #fff;
        border: 1px solid rgba(102,126,234,0.15);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
    }

    .sidebar-nav .nav-item a.active i {
        color: #667eea;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content-area {
        margin-left: 260px;
        padding: 20px 25px;
        min-height: calc(100vh - 80px);
        background: #f3f4f6;
    }

    @media (max-width: 768px) {
        .admin-sidebar {
            left: -280px;
            width: 280px;
        }
        .admin-sidebar.open {
            left: 0;
        }
        .main-content-area {
            margin-left: 0;
            padding: 15px;
        }
        .sidebar-toggle-btn {
            display: flex !important;
        }
    }

    .sidebar-toggle-btn {
        display: none;
        background: none;
        border: none;
        color: #333;
        font-size: 24px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sidebar-toggle-btn:hover {
        background: rgba(0,0,0,0.05);
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        height: calc(100vh - 80px);
        background: rgba(0,0,0,0.4);
        z-index: 99;
        backdrop-filter: blur(4px);
    }
    .sidebar-overlay.active { display: block; }

    /* ===== TABLE STYLES ===== */
    .table-wrap {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        overflow-x: auto;
    }
    .table-wrap table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }
    .table-wrap table thead {
        background: #1a1a2e;
        color: white;
    }
    .table-wrap table thead th {
        padding: 12px 15px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .table-wrap table tbody tr {
        border-bottom: 1px solid #eee;
    }
    .table-wrap table tbody tr:hover {
        background: #f8faff;
    }
    .table-wrap table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    .btn-edit {
        background: #3498db;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        text-decoration: none;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
    }
    .btn-edit:hover { background: #2980b9; color: white; }

    .btn-delete {
        background: #e74c3c;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        text-decoration: none;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
    }
    .btn-delete:hover { background: #c0392b; color: white; }

    .btn-toggle {
        background: #f39c12;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        text-decoration: none;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
    }
    .btn-toggle:hover { background: #e67e22; color: white; }

    .btn-add {
        background: #2ecc71;
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-add:hover { background: #27ae60; color: white; }

    .status-badge {
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
    }
    .status-badge.active { background: #d4edda; color: #155724; }
    .status-badge.inactive { background: #f8d7da; color: #721c24; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.confirmed { background: #d4edda; color: #155724; }
    .status-badge.completed { background: #dbeafe; color: #1e40af; }
    .status-badge.cancelled { background: #f8d7da; color: #721c24; }

    .header-bar {
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .header-bar h3 {
        margin: 0;
        font-size: 20px;
    }
    .header-bar h3 i { margin-right: 8px; }
    .header-bar .count-badge {
        background: #667eea;
        color: white;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 14px;
        margin-left: 8px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        display: block;
        margin-bottom: 4px;
    }
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    .form-control:focus {
        border-color: #667eea;
        outline: none;
    }
    .form-group.full-width { grid-column: span 2; }

    .alert-msg {
        padding: 10px 20px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .alert-msg.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-msg.danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .stat-card .stat-icon { font-size: 30px; }
    .stat-card .stat-number { font-size: 28px; font-weight: 700; color: #333; }
    .stat-card .stat-label { color: #666; font-size: 13px; }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }
    .quick-action-item {
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    .quick-action-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .quick-action-item i { font-size: 24px; display: block; margin-bottom: 5px; }

    .timing-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .timing-card {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .timing-card h5 {
        margin: 0 0 10px;
        color: #333;
        font-size: 14px;
        text-align: center;
    }
    .timing-card .time-input {
        display: flex;
        gap: 5px;
        align-items: center;
        margin-bottom: 8px;
    }
    .timing-card .time-input input[type="time"] {
        padding: 4px 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 12px;
        flex: 1;
    }
    .timing-card .time-input span {
        font-size: 12px;
        color: #666;
    }
    .timing-card label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #666;
        cursor: pointer;
    }
    .timing-card label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .quick-actions-grid { grid-template-columns: repeat(3, 1fr); }
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
        .timing-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .quick-actions-grid { grid-template-columns: repeat(2, 1fr); }
        .timing-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<!-- ======================================================
    SIDEBAR OVERLAY
    ====================================================== -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ======================================================
    COMPLETE SIDEBAR - ALL OPTIONS INCLUDING BLOGS
    ====================================================== -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">D</div>
            <div class="brand-text">
                <h4>Docpedia</h4>
                <small>Admin Panel</small>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- ===== MAIN SECTION ===== -->
        <div class="nav-section">Main</div>

        <div class="nav-item">
            <a href="?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
                <span class="badge-sidebar primary">Live</span>
            </a>
        </div>

        <!-- ===== LISTINGS SECTION ===== -->
        <div class="nav-section">Listings</div>

        <div class="nav-item">
            <a href="?page=doctors" class="<?php echo ($page == 'doctors') ? 'active' : ''; ?>">
                <i class="fas fa-user-md"></i>
                <span>Doctors</span>
                <span class="badge-sidebar primary"><?php echo $total_doctors; ?></span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=labs" class="<?php echo ($page == 'labs') ? 'active' : ''; ?>">
                <i class="fas fa-flask"></i>
                <span>Labs</span>
                <span class="badge-sidebar success"><?php echo $total_labs; ?></span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=specialities" class="<?php echo ($page == 'specialities') ? 'active' : ''; ?>">
                <i class="fas fa-stethoscope"></i>
                <span>Specialities</span>
                <span class="badge-sidebar warning"><?php echo $total_specialities; ?></span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=pharmacies" class="<?php echo ($page == 'pharmacies') ? 'active' : ''; ?>">
                <i class="fas fa-hospital"></i>
                <span>Pharmacies</span>
                <span class="badge-sidebar danger"><?php echo $total_pharmacies; ?></span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=medicines" class="<?php echo ($page == 'medicines') ? 'active' : ''; ?>">
                <i class="fas fa-pills"></i>
                <span>Medicines</span>
                <span class="badge-sidebar warning"><?php echo $total_medicines; ?></span>
            </a>
        </div>

        <!-- ===== BLOGS SECTION ===== -->
        <div class="nav-section">Blogs</div>

        <div class="nav-item">
            <a href="?page=blogs" class="<?php echo ($page == 'blogs') ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i>
                <span>Health Blogs</span>
                <span class="badge-sidebar primary"><?php echo $total_blogs; ?></span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=add-blog" class="<?php echo ($page == 'add-blog') ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i>
                <span>Add Blog</span>
            </a>
        </div>

        <!-- ===== APPOINTMENTS SECTION ===== -->
        <div class="nav-section">Appointments</div>

        <div class="nav-item">
            <a href="?page=appointments" class="<?php echo ($page == 'appointments') ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
                <span class="badge-sidebar primary"><?php echo $total_appointments; ?></span>
            </a>
        </div>

        <!-- ===== MANAGEMENT SECTION ===== -->
        <div class="nav-section">Management</div>

        <div class="nav-item">
            <a href="?page=add-doctor" class="<?php echo ($page == 'add-doctor') ? 'active' : ''; ?>">
                <i class="fas fa-user-plus"></i>
                <span>Add Doctor</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=add-lab" class="<?php echo ($page == 'add-lab') ? 'active' : ''; ?>">
                <i class="fas fa-flask"></i>
                <span>Add Lab</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=add-medicine" class="<?php echo ($page == 'add-medicine') ? 'active' : ''; ?>">
                <i class="fas fa-pills"></i>
                <span>Add Medicine</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=add-pharmacy" class="<?php echo ($page == 'add-pharmacy') ? 'active' : ''; ?>">
                <i class="fas fa-hospital"></i>
                <span>Add Pharmacy</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="?page=add-speciality" class="<?php echo ($page == 'add-speciality') ? 'active' : ''; ?>">
                <i class="fas fa-stethoscope"></i>
                <span>Add Speciality</span>
            </a>
        </div>

        <!-- ==============================================
            MANAGE TIMING
            ============================================== -->
        <div class="nav-item">
            <a href="?page=manage-timing" class="<?php echo ($page == 'manage-timing') ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i>
                <span>Manage Timing</span>
            </a>
        </div>

        <!-- ===== USER SECTION ===== -->
        <div class="nav-section">User</div>

        <div class="nav-item">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i>
                <span style="color: #ef4444;">Sign Out</span>
            </a>
        </div>
    </nav>
</aside>

<!-- ======================================================
    MAIN CONTENT AREA
    ====================================================== -->
<div class="main-content-area" id="mainContent">

    <!-- ===== TOP BAR ===== -->
    <div class="header-bar">
        <div>
            <h3>
                <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <?php
                $page_titles = [
                    'dashboard' => 'Dashboard',
                    'doctors' => 'Manage Doctors',
                    'add-doctor' => 'Add New Doctor',
                    'edit-doctor' => 'Edit Doctor',
                    'labs' => 'Manage Labs',
                    'add-lab' => 'Add New Lab',
                    'edit-lab' => 'Edit Lab',
                    'medicines' => 'Manage Medicines',
                    'add-medicine' => 'Add New Medicine',
                    'pharmacies' => 'Manage Pharmacies',
                    'add-pharmacy' => 'Add New Pharmacy',
                    'specialities' => 'Manage Specialities',
                    'add-speciality' => 'Add New Speciality',
                    'appointments' => 'Appointments',
                    'manage-timing' => 'Manage Timing',
                    'blogs' => 'Manage Blogs',
                    'add-blog' => 'Add New Blog',
                    'edit-blog' => 'Edit Blog'
                ];
                echo isset($page_titles[$page]) ? $page_titles[$page] : 'Dashboard';
                ?>
            </h3>
            <p style="margin:0;color:#666;font-size:13px;">Manage your healthcare platform efficiently.</p>
        </div>
    </div>

    <!-- ===== MESSAGE ===== -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert-msg success">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($msg) && $msg_type == 'danger'): ?>
        <div class="alert-msg danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <!-- ======================================================
        PAGE CONTENT
        ====================================================== -->

    <!-- ====== DASHBOARD ====== -->
    <?php if($page == 'dashboard'): ?>
        <div class="stats-grid">
            <a href="?page=doctors" class="stat-card">
                <div class="stat-icon" style="color:#667eea;"><i class="fas fa-user-md"></i></div>
                <div class="stat-number"><?php echo $total_doctors; ?></div>
                <div class="stat-label">Doctors</div>
            </a>
            <a href="?page=labs" class="stat-card">
                <div class="stat-icon" style="color:#10b981;"><i class="fas fa-flask"></i></div>
                <div class="stat-number"><?php echo $total_labs; ?></div>
                <div class="stat-label">Labs</div>
            </a>
            <a href="?page=specialities" class="stat-card">
                <div class="stat-icon" style="color:#3b82f6;"><i class="fas fa-stethoscope"></i></div>
                <div class="stat-number"><?php echo $total_specialities; ?></div>
                <div class="stat-label">Specialities</div>
            </a>
            <a href="?page=pharmacies" class="stat-card">
                <div class="stat-icon" style="color:#f59e0b;"><i class="fas fa-hospital"></i></div>
                <div class="stat-number"><?php echo $total_pharmacies; ?></div>
                <div class="stat-label">Pharmacies</div>
            </a>
            <a href="?page=medicines" class="stat-card">
                <div class="stat-icon" style="color:#ef4444;"><i class="fas fa-pills"></i></div>
                <div class="stat-number"><?php echo $total_medicines; ?></div>
                <div class="stat-label">Medicines</div>
            </a>
            <a href="?page=blogs" class="stat-card">
                <div class="stat-icon" style="color:#8b5cf6;"><i class="fas fa-blog"></i></div>
                <div class="stat-number"><?php echo $total_blogs; ?></div>
                <div class="stat-label">Blogs</div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);margin-bottom:20px;">
            <h4 style="margin-top:0;margin-bottom:15px;"><i class="fas fa-bolt" style="color:#667eea;"></i> Quick Actions</h4>
            <div class="quick-actions-grid">
                <a href="?page=add-doctor" class="quick-action-item" style="background:#eef2ff;color:#4f46e5;">
                    <i class="fas fa-user-md"></i> Add Doctor
                </a>
                <a href="?page=add-lab" class="quick-action-item" style="background:#d1fae5;color:#10b981;">
                    <i class="fas fa-flask"></i> Add Lab
                </a>
                <a href="?page=add-medicine" class="quick-action-item" style="background:#fef3c7;color:#f59e0b;">
                    <i class="fas fa-pills"></i> Add Medicine
                </a>
                <a href="?page=add-pharmacy" class="quick-action-item" style="background:#dbeafe;color:#3b82f6;">
                    <i class="fas fa-hospital"></i> Add Pharmacy
                </a>
                <a href="?page=add-speciality" class="quick-action-item" style="background:#ede9fe;color:#7c3aed;">
                    <i class="fas fa-stethoscope"></i> Add Speciality
                </a>
                <a href="?page=add-blog" class="quick-action-item" style="background:#fce4ec;color:#e91e63;">
                    <i class="fas fa-blog"></i> Add Blog
                </a>
            </div>
        </div>

    <!-- ======================================================
        BLOGS LIST
        ====================================================== -->
    <?php elseif($page == 'blogs'): ?>
        <?php $blogs = mysqli_query($connect, "SELECT * FROM blogs ORDER BY blog_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-blog" style="color:#8b5cf6;"></i> All Blogs <span style="background:#8b5cf6;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($blogs); ?></span></h4>
                <a href="?page=add-blog" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="padding:10px 15px;">ID</th>
                        <th style="padding:10px 15px;">Image</th>
                        <th style="padding:10px 15px;">Title</th>
                        <th style="padding:10px 15px;">Author</th>
                        <th style="padding:10px 15px;">Date</th>
                        <th style="padding:10px 15px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($blogs) > 0): ?>
                        <?php while($blog = mysqli_fetch_assoc($blogs)): ?>
                            <tr>
                                <td style="padding:10px 15px;"><?php echo $blog['blog_id']; ?></td>
                                <td style="padding:10px 15px;">
                                    <?php 
                                    if(!empty($blog['blog_img']) && file_exists("images/uploads/".$blog['blog_img'])) { 
                                    ?>
                                        <img src="images/uploads/<?php echo $blog['blog_img']; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                    <?php } else { ?>
                                        <img src="assets/img/no-image.png" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                    <?php } ?>
                                </td>
                                <td style="padding:10px 15px;"><strong><?php echo htmlspecialchars($blog['blog_title']); ?></strong></td>
                                <td style="padding:10px 15px;"><?php echo isset($blog['author']) ? htmlspecialchars($blog['author']) : 'Admin'; ?></td>
                                <td style="padding:10px 15px;">
                                    <?php 
                                    $date = isset($blog['created_date']) ? $blog['created_date'] : (isset($blog['blog_date']) ? $blog['blog_date'] : date('Y-m-d'));
                                    echo date('M d, Y', strtotime($date)); 
                                    ?>
                                </td>
                                <td style="padding:10px 15px;">
                                    <a href="?page=edit-blog&edit_blog=<?php echo $blog['blog_id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="?page=blogs&delete_blog=<?php echo $blog['blog_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this blog?')"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:30px;color:#999;">No blogs found. <a href="?page=add-blog" style="color:#667eea;font-weight:600;">Create your first blog</a></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== ADD BLOG ====== -->
    <?php elseif($page == 'add-blog'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-blog" style="color:#8b5cf6;"></i> Add New Blog</h4>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Title <span style="color:red;">*</span></label>
                        <input type="text" name="blog_title" class="form-control" required>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Description (Short) <span style="color:red;">*</span></label>
                        <textarea name="blog_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Content (Full) <span style="color:red;">*</span></label>
                        <textarea name="blog_content" class="form-control" rows="8" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author" class="form-control" placeholder="Author name" value="Admin">
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="datetime-local" name="created_date" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Image</label>
                        <input type="file" name="blog_img" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 800x500px</small>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_blog" style="background:#8b5cf6;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Blog
                    </button>
                    <a href="?page=blogs" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== EDIT BLOG ====== -->
    <?php elseif($page == 'edit-blog' && $edit_blog): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-edit" style="color:#f59e0b;"></i> Edit Blog</h4>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="blog_id" value="<?php echo $edit_blog['blog_id']; ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Title <span style="color:red;">*</span></label>
                        <input type="text" name="blog_title" class="form-control" value="<?php echo htmlspecialchars($edit_blog['blog_title']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Description (Short) <span style="color:red;">*</span></label>
                        <textarea name="blog_description" class="form-control" rows="3" required><?php echo htmlspecialchars($edit_blog['blog_description'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Content (Full) <span style="color:red;">*</span></label>
                        <textarea name="blog_content" class="form-control" rows="8" required><?php echo htmlspecialchars($edit_blog['blog_content'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author" class="form-control" value="<?php echo isset($edit_blog['author']) ? htmlspecialchars($edit_blog['author']) : 'Admin'; ?>">
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="datetime-local" name="created_date" class="form-control" value="<?php echo isset($edit_blog['created_date']) ? date('Y-m-d\TH:i', strtotime($edit_blog['created_date'])) : date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Blog Image</label>
                        <?php if(!empty($edit_blog['blog_img']) && file_exists("images/uploads/".$edit_blog['blog_img'])): ?>
                            <div style="margin-bottom:10px;">
                                <img src="images/uploads/<?php echo $edit_blog['blog_img']; ?>" style="width:120px;height:80px;object-fit:cover;border-radius:8px;">
                                <small class="text-muted">Current image</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="blog_img" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="update_blog" style="background:#f59e0b;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Update Blog
                    </button>
                    <a href="?page=blogs" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ======================================================
        MANAGE TIMING
        ====================================================== -->
    <?php elseif($page == 'manage-timing'): ?>
        <?php 
        $doctors_list = mysqli_query($connect, "SELECT doc_id, doc_name FROM doctors ORDER BY doc_name");
        $selected_doctor = isset($_GET['doc_id']) ? $_GET['doc_id'] : 0;
        $doctor_timing = [];
        
        if($selected_doctor > 0) {
            $timing_query = mysqli_query($connect, "SELECT * FROM doctor_timing WHERE doc_id = '$selected_doctor'");
            while($row = mysqli_fetch_assoc($timing_query)) {
                $doctor_timing[$row['day_of_week']] = $row;
            }
        }
        ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-clock" style="color:#667eea;"></i> Manage Doctor Timing</h4>
            
            <!-- Select Doctor -->
            <form method="GET" action="?page=manage-timing" style="margin-bottom:20px;">
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <label style="font-weight:600;font-size:14px;color:#555;">Select Doctor:</label>
                    <select name="doc_id" class="form-control" style="width:auto;min-width:250px;">
                        <option value="0">-- Select Doctor --</option>
                        <?php while($doc = mysqli_fetch_assoc($doctors_list)): ?>
                            <option value="<?php echo $doc['doc_id']; ?>" <?php echo ($selected_doctor == $doc['doc_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($doc['doc_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" style="background:#667eea;color:white;padding:8px 20px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-search"></i> Load Timing
                    </button>
                    <?php if($selected_doctor > 0): ?>
                        <a href="?page=manage-timing" style="padding:8px 20px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if($selected_doctor > 0): ?>
                <?php 
                $doctor_name = mysqli_fetch_assoc(mysqli_query($connect, "SELECT doc_name FROM doctors WHERE doc_id = '$selected_doctor'"));
                ?>
                <div style="background:#f0f4ff;padding:10px 15px;border-radius:8px;margin-bottom:20px;">
                    <strong>Setting timing for:</strong> <?php echo htmlspecialchars($doctor_name['doc_name']); ?>
                </div>

                <form method="POST" action="?page=manage-timing">
                    <input type="hidden" name="doc_id" value="<?php echo $selected_doctor; ?>">
                    
                    <div class="timing-grid">
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach($days as $day):
                            $timing = isset($doctor_timing[$day]) ? $doctor_timing[$day] : null;
                            $start_time = $timing ? $timing['start_time'] : '';
                            $end_time = $timing ? $timing['end_time'] : '';
                            $is_available = $timing ? $timing['is_available'] : 1;
                        ?>
                        <div class="timing-card">
                            <h5><?php echo $day; ?></h5>
                            <div class="time-input">
                                <input type="time" name="start_time[<?php echo $day; ?>]" value="<?php echo $start_time; ?>" placeholder="Start">
                                <span>to</span>
                                <input type="time" name="end_time[<?php echo $day; ?>]" value="<?php echo $end_time; ?>" placeholder="End">
                            </div>
                            <label>
                                <input type="checkbox" name="available[<?php echo $day; ?>]" <?php echo ($is_available) ? 'checked' : ''; ?>>
                                Available
                            </label>
                            <input type="hidden" name="days[]" value="<?php echo $day; ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="display:flex;gap:10px;margin-top:20px;">
                        <button type="submit" name="save_timing" style="background:#667eea;color:white;padding:12px 30px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                            <i class="fas fa-save"></i> Save Timing
                        </button>
                        <a href="?page=manage-timing" style="padding:12px 30px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                    </div>
                </form>
            <?php else: ?>
                <div style="text-align:center;padding:40px;color:#999;">
                    <i class="fas fa-clock" style="font-size:48px;display:block;margin-bottom:15px;color:#ddd;"></i>
                    <p style="font-size:16px;">Please select a doctor to manage their timing.</p>
                </div>
            <?php endif; ?>
        </div>

    <!-- ======================================================
        OTHER PAGES (ADD DOCTOR, DOCTORS LIST, ETC.)
        ====================================================== -->
    <!-- ====== ADD DOCTOR ====== -->
    <?php elseif($page == 'add-doctor'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-user-plus" style="color:#667eea;"></i> Add New Doctor</h4>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Doctor Name <span style="color:red;">*</span></label>
                        <input type="text" name="doc_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Specialization <span style="color:red;">*</span></label>
                        <select name="spec" class="form-control" required>
                            <option value="">Select Specialization</option>
                            <?php while($row = mysqli_fetch_assoc($specialities_list)): ?>
                                <option value="<?php echo $row['disease_name']; ?>"><?php echo $row['disease_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Speciality ID</label>
                        <select name="disease_id" class="form-control">
                            <option value="">Select Speciality</option>
                            <?php 
                            $disease_list = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_name");
                            while($row = mysqli_fetch_assoc($disease_list)): ?>
                                <option value="<?php echo $row['disease_id']; ?>"><?php echo $row['disease_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="number" class="form-control" placeholder="0300-1234567">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Hospital Address</label>
                        <input type="text" name="hos_address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="exp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Doctor Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_doctor" style="background:#667eea;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Doctor
                    </button>
                    <a href="?page=doctors" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== DOCTORS LIST ====== -->
    <?php elseif($page == 'doctors'): ?>
        <?php $doctors = mysqli_query($connect, "SELECT d.*, dis.disease_name FROM doctors d LEFT JOIN disease dis ON d.disease_id = dis.disease_id ORDER BY d.doc_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-user-md" style="color:#667eea;"></i> All Doctors <span style="background:#667eea;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($doctors); ?></span></h4>
                <a href="?page=add-doctor" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Speciality</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($doctors) > 0): ?>
                        <?php while($doc = mysqli_fetch_assoc($doctors)): ?>
                            <tr>
                                <td><?php echo $doc['doc_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($doc['doc_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($doc['spec']); ?></td>
                                <td><?php echo $doc['number']; ?></td>
                                <td>
                                    <a href="?page=edit-doctor&edit_doctor=<?php echo $doc['doc_id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="?page=doctors&delete_doctor=<?php echo $doc['doc_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;padding:30px;color:#999;">No doctors found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== EDIT DOCTOR ====== -->
    <?php elseif($page == 'edit-doctor' && $edit_doctor): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-edit" style="color:#f59e0b;"></i> Edit Doctor</h4>
            <form method="POST">
                <input type="hidden" name="doc_id" value="<?php echo $edit_doctor['doc_id']; ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Doctor Name <span style="color:red;">*</span></label>
                        <input type="text" name="doc_name" class="form-control" value="<?php echo htmlspecialchars($edit_doctor['doc_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Specialization <span style="color:red;">*</span></label>
                        <select name="spec" class="form-control" required>
                            <?php 
                            mysqli_data_seek($specialities_list, 0);
                            while($row = mysqli_fetch_assoc($specialities_list)): ?>
                                <option value="<?php echo $row['disease_name']; ?>" <?php echo ($row['disease_name'] == $edit_doctor['spec']) ? 'selected' : ''; ?>>
                                    <?php echo $row['disease_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="number" class="form-control" value="<?php echo $edit_doctor['number']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="exp" class="form-control" value="<?php echo $edit_doctor['exp']; ?>">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Hospital Address</label>
                        <input type="text" name="hos_address" class="form-control" value="<?php echo htmlspecialchars($edit_doctor['hos_address']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Speciality ID</label>
                        <select name="disease_id" class="form-control">
                            <?php 
                            $disease_list = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_name");
                            while($row = mysqli_fetch_assoc($disease_list)): ?>
                                <option value="<?php echo $row['disease_id']; ?>" <?php echo ($row['disease_id'] == $edit_doctor['disease_id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['disease_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="update_doctor" style="background:#f59e0b;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Update Doctor
                    </button>
                    <a href="?page=doctors" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== ADD LAB ====== -->
    <?php elseif($page == 'add-lab'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-flask" style="color:#10b981;"></i> Add New Lab</h4>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Lab Name <span style="color:red;">*</span></label>
                        <input type="text" name="lab_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_lab" style="background:#10b981;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Lab
                    </button>
                    <a href="?page=labs" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== LABS LIST ====== -->
    <?php elseif($page == 'labs'): ?>
        <?php $labs = mysqli_query($connect, "SELECT * FROM labs ORDER BY lab_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-flask" style="color:#10b981;"></i> All Labs <span style="background:#10b981;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($labs); ?></span></h4>
                <a href="?page=add-lab" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($labs) > 0): ?>
                        <?php while($lab = mysqli_fetch_assoc($labs)): ?>
                            <tr>
                                <td><?php echo $lab['lab_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($lab['lab_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($lab['address']); ?></td>
                                <td><?php echo $lab['phone']; ?></td>
                                <td>
                                    <a href="?page=edit-lab&edit_lab=<?php echo $lab['lab_id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="?page=labs&delete_lab=<?php echo $lab['lab_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;padding:30px;color:#999;">No labs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== EDIT LAB ====== -->
    <?php elseif($page == 'edit-lab' && $edit_lab): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-edit" style="color:#f59e0b;"></i> Edit Lab</h4>
            <form method="POST">
                <input type="hidden" name="lab_id" value="<?php echo $edit_lab['lab_id']; ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Lab Name <span style="color:red;">*</span></label>
                        <input type="text" name="lab_name" class="form-control" value="<?php echo htmlspecialchars($edit_lab['lab_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $edit_lab['phone']; ?>">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($edit_lab['address']); ?>">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="update_lab" style="background:#f59e0b;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Update Lab
                    </button>
                    <a href="?page=labs" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== MEDICINES LIST ====== -->
    <?php elseif($page == 'medicines'): ?>
        <?php $medicines = mysqli_query($connect, "SELECT * FROM medicines ORDER BY medicine_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-pills" style="color:#ef4444;"></i> All Medicines <span style="background:#ef4444;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($medicines); ?></span></h4>
                <a href="?page=add-medicine" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($medicines) > 0): ?>
                        <?php while($med = mysqli_fetch_assoc($medicines)): ?>
                            <tr>
                                <td><?php echo $med['medicine_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($med['medicine_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($med['category']); ?></td>
                                <td>Rs. <?php echo number_format($med['price'], 2); ?></td>
                                <td><?php echo $med['stock']; ?></td>
                                <td>
                                    <span class="status-badge <?php echo ($med['status'] == 'active') ? 'active' : 'inactive'; ?>">
                                        <?php echo ucfirst($med['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=medicines&medicine_status=<?php echo ($med['status'] == 'active') ? 'inactive' : 'active'; ?>&id=<?php echo $med['medicine_id']; ?>" class="btn-toggle">
                                        <?php echo ($med['status'] == 'active') ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>'; ?>
                                    </a>
                                    <a href="?page=medicines&delete_medicine=<?php echo $med['medicine_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;padding:30px;color:#999;">No medicines found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== ADD MEDICINE ====== -->
    <?php elseif($page == 'add-medicine'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-pills" style="color:#ef4444;"></i> Add New Medicine</h4>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Medicine Name <span style="color:red;">*</span></label>
                        <input type="text" name="medicine_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Generic Name</label>
                        <input type="text" name="generic_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <option value="Pain Relief">Pain Relief</option>
                            <option value="Antibiotics">Antibiotics</option>
                            <option value="Blood Pressure">Blood Pressure</option>
                            <option value="Diabetes">Diabetes</option>
                            <option value="Vitamin">Vitamin</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Manufacturer</label>
                        <input type="text" name="manufacturer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Price (Rs.) <span style="color:red;">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stock <span style="color:red;">*</span></label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Strength</label>
                        <input type="text" name="strength" class="form-control" placeholder="500mg">
                    </div>
                    <div class="form-group">
                        <label>Dosage Form</label>
                        <select name="dosage_form" class="form-control">
                            <option value="">Select Dosage</option>
                            <option value="Tablet">Tablet</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Injection">Injection</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Side Effects</label>
                        <textarea name="side_effects" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Precautions</label>
                        <textarea name="precautions" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_medicine" style="background:#ef4444;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Medicine
                    </button>
                    <a href="?page=medicines" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== PHARMACIES LIST ====== -->
    <?php elseif($page == 'pharmacies'): ?>
        <?php $pharmacies = mysqli_query($connect, "SELECT * FROM pharmacies ORDER BY pharmacy_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-hospital" style="color:#f59e0b;"></i> All Pharmacies <span style="background:#f59e0b;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($pharmacies); ?></span></h4>
                <a href="?page=add-pharmacy" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($pharmacies) > 0): ?>
                        <?php while($pharm = mysqli_fetch_assoc($pharmacies)): ?>
                            <tr>
                                <td><?php echo $pharm['pharmacy_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($pharm['pharmacy_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($pharm['address']); ?></td>
                                <td><?php echo $pharm['phone']; ?></td>
                                <td>
                                    <span class="status-badge <?php echo ($pharm['status'] == 'active') ? 'active' : 'inactive'; ?>">
                                        <?php echo ucfirst($pharm['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=pharmacies&pharmacy_status=<?php echo ($pharm['status'] == 'active') ? 'inactive' : 'active'; ?>&id=<?php echo $pharm['pharmacy_id']; ?>" class="btn-toggle">
                                        <?php echo ($pharm['status'] == 'active') ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>'; ?>
                                    </a>
                                    <a href="?page=pharmacies&delete_pharmacy=<?php echo $pharm['pharmacy_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:30px;color:#999;">No pharmacies found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== ADD PHARMACY ====== -->
    <?php elseif($page == 'add-pharmacy'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-hospital" style="color:#f59e0b;"></i> Add New Pharmacy</h4>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label>Pharmacy Name <span style="color:red;">*</span></label>
                        <input type="text" name="pharmacy_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_pharmacy" style="background:#f59e0b;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Pharmacy
                    </button>
                    <a href="?page=pharmacies" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== SPECIALITIES LIST ====== -->
    <?php elseif($page == 'specialities'): ?>
        <?php $specialities = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_id DESC"); ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-stethoscope" style="color:#3b82f6;"></i> All Specialities <span style="background:#3b82f6;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($specialities); ?></span></h4>
                <a href="?page=add-speciality" class="btn-add"><i class="fas fa-plus-circle"></i> Add New</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($specialities) > 0): ?>
                        <?php while($spec = mysqli_fetch_assoc($specialities)): ?>
                            <tr>
                                <td><?php echo $spec['disease_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($spec['disease_name']); ?></strong></td>
                                <td>
                                    <a href="?page=specialities&delete_speciality=<?php echo $spec['disease_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center;padding:30px;color:#999;">No specialities found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== ADD SPECIALITY ====== -->
    <?php elseif($page == 'add-speciality'): ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:20px;"><i class="fas fa-stethoscope" style="color:#3b82f6;"></i> Add New Speciality</h4>
            <form method="POST">
                <div class="form-group">
                    <label>Speciality Name <span style="color:red;">*</span></label>
                    <input type="text" name="speciality_name" class="form-control" required>
                </div>
                <div style="display:flex;gap:10px;margin-top:15px;">
                    <button type="submit" name="add_speciality" style="background:#3b82f6;color:white;padding:10px 25px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-save"></i> Save Speciality
                    </button>
                    <a href="?page=specialities" style="padding:10px 25px;border:2px solid #ddd;border-radius:8px;text-decoration:none;color:#666;font-weight:600;">Cancel</a>
                </div>
            </form>
        </div>

    <!-- ====== APPOINTMENTS ====== -->
    <?php elseif($page == 'appointments'): ?>
        <?php 
        $appointments = mysqli_query($connect, "SELECT a.*, d.doc_name FROM appointments a LEFT JOIN doctors d ON a.doc_id = d.doc_id ORDER BY a.created_at DESC");
        ?>
        <div style="background:white;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);overflow-x:auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
                <h4 style="margin:0;"><i class="fas fa-calendar-check" style="color:#8b5cf6;"></i> All Appointments <span style="background:#8b5cf6;color:white;padding:2px 10px;border-radius:20px;font-size:14px;margin-left:8px;"><?php echo mysqli_num_rows($appointments); ?></span></h4>
                <a href="dashboard-appointments.php" style="background:#8b5cf6;color:white;padding:8px 20px;border-radius:30px;text-decoration:none;font-weight:600;">
                    <i class="fas fa-eye"></i> View All
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($appointments) > 0): ?>
                        <?php while($app = mysqli_fetch_assoc($appointments)): ?>
                            <tr>
                                <td><?php echo $app['appointment_id']; ?></td>
                                <td><?php echo htmlspecialchars($app['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($app['doc_name'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d M Y', strtotime($app['appointment_date'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $app['status']; ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="dashboard-appointments.php" class="btn-edit"><i class="fas fa-eye"></i> View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:30px;color:#999;">No appointments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <!-- ====== DEFAULT - PAGE NOT FOUND ====== -->
    <?php else: ?>
        <div style="background:#f8d7da;color:#721c24;padding:20px;border-radius:8px;border:1px solid #f5c6cb;text-align:center;">
            <i class="fas fa-exclamation-circle" style="font-size:30px;display:block;margin-bottom:10px;"></i>
            <h4>Page Not Found!</h4>
            <p>The page you are looking for does not exist. Please go back to the <a href="?page=dashboard" style="color:#667eea;font-weight:600;">Dashboard</a>.</p>
        </div>
    <?php endif; ?>

</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('adminSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    });

    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        document.getElementById('adminSidebar').classList.remove('open');
        this.classList.remove('active');
    });
</script>

<?php include('dashboard-footer.php'); 
ob_end_flush(); ?>