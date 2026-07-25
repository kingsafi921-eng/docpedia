<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

// Handle Status Update
if(isset($_POST['update_status'])) {
    $appointment_id = mysqli_real_escape_string($connect, $_POST['appointment_id']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    if(!in_array($status, $valid_statuses)) {
        $error_msg = "Invalid status value.";
    } else {
        $update = "UPDATE appointments SET status = '$status' WHERE appointment_id = '$appointment_id'";
        if(mysqli_query($connect, $update)) {
            $success_msg = "Appointment status updated successfully!";
        } else {
            $error_msg = "Error: " . mysqli_error($connect);
        }
    }
}

// Handle Delete
if(isset($_POST['delete_appointment'])) {
    $appointment_id = mysqli_real_escape_string($connect, $_POST['appointment_id']);
    
    $delete = "DELETE FROM appointments WHERE appointment_id = '$appointment_id'";
    if(mysqli_query($connect, $delete)) {
        $success_msg = "Appointment deleted successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($connect);
    }
}

// Handle Bulk Status Update
if(isset($_POST['bulk_update_status']) && isset($_POST['selected_appointments'])) {
    $selected_ids = $_POST['selected_appointments'];
    $new_status = mysqli_real_escape_string($connect, $_POST['bulk_status']);
    
    if(!empty($selected_ids) && is_array($selected_ids)) {
        $ids = implode("','", array_map(function($id) use ($connect) {
            return mysqli_real_escape_string($connect, $id);
        }, $selected_ids));
        
        $update = "UPDATE appointments SET status = '$new_status' WHERE appointment_id IN ('$ids')";
        if(mysqli_query($connect, $update)) {
            $success_msg = count($selected_ids) . " appointments updated to " . ucfirst($new_status) . "!";
        } else {
            $error_msg = "Error: " . mysqli_error($connect);
        }
    }
}

// Get filter parameters
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($connect, $_GET['filter']) : 'all';
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($connect, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($connect, $_GET['date_to']) : '';

// Build query with filters
$where_conditions = [];
if($filter != 'all') {
    $where_conditions[] = "a.status = '$filter'";
}
if(!empty($search)) {
    $where_conditions[] = "(a.patient_name LIKE '%$search%' OR a.patient_email LIKE '%$search%' OR a.patient_phone LIKE '%$search%' OR d.doc_name LIKE '%$search%')";
}
if(!empty($date_from)) {
    $where_conditions[] = "a.appointment_date >= '$date_from'";
}
if(!empty($date_to)) {
    $where_conditions[] = "a.appointment_date <= '$date_to'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// ===== FIXED: Removed d.doc_image from SELECT =====
$appointments_query = "
    SELECT a.*, d.doc_name, d.spec 
    FROM appointments a 
    LEFT JOIN doctors d ON a.doc_id = d.doc_id 
    $where_clause
    ORDER BY a.created_at DESC
";
$appointments_result = mysqli_query($connect, $appointments_query);

// Get counts
$total_query = "SELECT COUNT(*) as total FROM appointments a LEFT JOIN doctors d ON a.doc_id = d.doc_id $where_clause";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'];

$status_counts = [];
$statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
foreach($statuses as $status) {
    $status_query = "SELECT COUNT(*) as count FROM appointments WHERE status = '$status'";
    $status_result = mysqli_query($connect, $status_query);
    if($status_result) {
        $status_row = mysqli_fetch_assoc($status_result);
        $status_counts[$status] = $status_row['count'];
    } else {
        $status_counts[$status] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Manager - Doctorpedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary: #764ba2;
            --success: #48bb78;
            --warning: #f6ad55;
            --danger: #fc8181;
            --info: #63b3ed;
            --dark: #1a202c;
            --gray: #4a5568;
            --light-gray: #edf2f7;
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f7fafc;
            color: var(--dark);
        }

        .main-content {
            padding: 24px 32px 40px;
            min-height: calc(100vh - 80px);
            max-width: 100%;
            margin: 0 auto;
        }

        .top-bar {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }

        .top-bar .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }

        .top-bar .breadcrumb-item a {
            color: var(--gray);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .top-bar .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .top-bar .breadcrumb-item.active {
            color: var(--dark);
            font-weight: 600;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-bar-actions .btn {
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
            font-size: 13px;
            transition: var(--transition);
        }

        .top-bar-actions .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }

        .top-bar-actions .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: #e2e8f0;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card .stat-info {
            flex: 1;
        }

        .stat-card .stat-number {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.2;
            color: var(--dark);
        }

        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--gray);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-total .stat-icon { background: #e9d8fd; color: var(--secondary); }
        .stat-total::before { background: var(--primary-gradient); }
        .stat-pending .stat-icon { background: #fefcbf; color: #d69e2e; }
        .stat-pending::before { background: var(--warning); }
        .stat-confirmed .stat-icon { background: #c6f6d5; color: #276749; }
        .stat-confirmed::before { background: var(--success); }
        .stat-completed .stat-icon { background: #bee3f8; color: #2b6cb0; }
        .stat-completed::before { background: var(--info); }
        .stat-cancelled .stat-icon { background: #fed7d7; color: #9b2c2c; }
        .stat-cancelled::before { background: var(--danger); }

        .toolbar {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .toolbar .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .toolbar .filter-header h6 {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .toolbar .filter-header h6 i {
            color: var(--primary);
            margin-right: 8px;
        }

        .toolbar .filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .toolbar .filter-row .search-box {
            flex: 2;
            min-width: 250px;
            position: relative;
        }

        .toolbar .filter-row .search-box input {
            width: 100%;
            padding: 10px 16px 10px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: var(--transition);
            background: #f7fafc;
            font-family: 'Inter', sans-serif;
        }

        .toolbar .filter-row .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .toolbar .filter-row .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .toolbar .filter-row .date-filters {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .toolbar .filter-row .date-filters .date-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar .filter-row .date-filters .date-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray);
            margin: 0;
        }

        .toolbar .filter-row .date-filters input[type="date"] {
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            background: #f7fafc;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            min-width: 140px;
        }

        .toolbar .filter-row .date-filters input[type="date"]:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
        }

        .toolbar .filter-row .status-filters {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .toolbar .filter-row .status-filters .btn-filter {
            padding: 6px 16px;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .toolbar .filter-row .status-filters .btn-filter:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .toolbar .filter-row .status-filters .btn-filter.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .toolbar .filter-row .status-filters .btn-filter .count-badge {
            display: inline-block;
            background: rgba(0,0,0,0.1);
            padding: 0 8px;
            border-radius: 10px;
            font-size: 10px;
            margin-left: 4px;
        }

        .toolbar .filter-row .status-filters .btn-filter.active .count-badge {
            background: rgba(255,255,255,0.2);
        }

        .toolbar .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .toolbar .filter-actions .btn-clear {
            padding: 8px 16px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray);
            transition: var(--transition);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .toolbar .filter-actions .btn-clear:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: #fff5f5;
        }

        .toolbar .filter-actions .btn-apply {
            padding: 8px 20px;
            border-radius: 10px;
            border: none;
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            font-size: 12px;
            transition: var(--transition);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .toolbar .filter-actions .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .active-filters .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #f7fafc;
            border-radius: 20px;
            font-size: 12px;
            color: var(--gray);
            border: 1px solid #e2e8f0;
        }

        .active-filters .filter-tag i {
            font-size: 10px;
            color: var(--primary);
        }

        .active-filters .filter-tag .remove-filter {
            cursor: pointer;
            color: var(--danger);
            font-size: 12px;
            margin-left: 4px;
        }

        .active-filters .filter-tag .remove-filter:hover {
            color: #c53030;
        }

        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .table-card .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            background: #fafbfc;
        }

        .table-card .card-header .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-card .card-header h5 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: var(--dark);
        }

        .table-card .card-header .badge-count {
            background: var(--primary);
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .table-card .card-header .bulk-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-card .card-header .bulk-actions select {
            padding: 8px 14px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-size: 13px;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        .table-card .card-header .bulk-actions select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .table-card .card-header .bulk-actions .btn-apply {
            padding: 8px 18px;
            border-radius: 10px;
            border: none;
            background: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 12px;
            transition: var(--transition);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .table-card .card-header .bulk-actions .btn-apply:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .table-card .card-body {
            padding: 0;
            overflow-x: auto;
        }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 1000px;
        }

        .table-card table thead {
            background: #f7fafc;
        }

        .table-card table th {
            padding: 14px 18px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            background: #f7fafc;
            z-index: 5;
        }

        .table-card table td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-card table tbody tr:hover {
            background: #f7fafc;
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 240px;
        }

        .patient-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 17px;
            flex-shrink: 0;
        }

        .patient-info {
            flex: 1;
            min-width: 0;
        }

        .patient-info .name {
            font-weight: 700;
            color: var(--dark);
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .patient-info .details {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 2px;
        }

        .patient-info .details span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: var(--gray);
        }

        .patient-info .details i {
            font-size: 11px;
            width: 16px;
            color: #a0aec0;
        }

        .doctor-cell .doc-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
        }

        .doctor-cell .doc-spec {
            font-size: 12px;
            color: var(--gray);
        }

        .doctor-cell .doc-spec i {
            width: 14px;
            font-size: 10px;
        }

        .date-cell .date {
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
        }

        .date-cell .time {
            font-size: 12px;
            color: var(--gray);
        }

        .date-cell .time i {
            width: 14px;
            font-size: 10px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge i {
            font-size: 8px;
        }

        .status-pending {
            background: #fefcbf;
            color: #975a16;
        }

        .status-confirmed {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-completed {
            background: #bee3f8;
            color: #2a4365;
        }

        .status-cancelled {
            background: #fed7d7;
            color: #9b2c2c;
        }

        .action-group {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            transition: var(--transition);
            cursor: pointer;
            color: white;
            position: relative;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-action .tooltip-text {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) scale(0.8);
            background: var(--dark);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition);
        }

        .btn-action:hover .tooltip-text {
            opacity: 1;
            transform: translateX(-50%) scale(1);
        }

        .btn-view { background: #3182ce; }
        .btn-view:hover { background: #2b6cb0; }
        .btn-verify { background: #48bb78; }
        .btn-verify:hover { background: #38a169; }
        .btn-cancel { background: #fc8181; }
        .btn-cancel:hover { background: #f56565; }
        .btn-complete { background: #805ad5; }
        .btn-complete:hover { background: #6b46c1; }
        .btn-delete { background: #fc8181; }
        .btn-delete:hover { background: #f56565; }

        .btn-status-done {
            background: #a0aec0;
            color: white;
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 20px 28px;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 28px;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 16px 28px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 24px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray);
            font-weight: 600;
        }

        .detail-item .value {
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
            margin-top: 2px;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #a0aec0;
            margin: 0 auto 20px;
        }

        .empty-state h4 {
            color: var(--dark);
            font-weight: 700;
        }

        .empty-state p {
            color: var(--gray);
            max-width: 400px;
            margin: 8px auto 20px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 16px 20px;
            }
            .top-bar {
                padding: 12px 20px;
                flex-wrap: wrap;
                gap: 10px;
            }
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
            .stat-card {
                padding: 14px 16px;
            }
            .stat-card .stat-number {
                font-size: 20px;
            }
            .toolbar {
                padding: 16px 18px;
            }
            .toolbar .filter-row .search-box {
                min-width: 180px;
                flex: 1 1 100%;
            }
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 12px 14px;
            }
            .top-bar {
                padding: 10px 14px;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .top-bar-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .stat-card {
                padding: 12px 14px;
            }
            .stat-card .stat-number {
                font-size: 18px;
            }
            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .toolbar {
                padding: 14px 16px;
            }
            .toolbar .filter-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .toolbar .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            .toolbar .filter-row .search-box {
                min-width: auto;
            }
            .toolbar .filter-row .date-filters {
                flex-direction: column;
                align-items: stretch;
            }
            .toolbar .filter-row .date-filters .date-group {
                flex-direction: column;
                align-items: stretch;
            }
            .toolbar .filter-row .date-filters input[type="date"] {
                width: 100%;
            }
            .toolbar .filter-row .status-filters {
                justify-content: flex-start;
            }
            .toolbar .filter-actions {
                flex-wrap: wrap;
            }
            .toolbar .filter-actions .btn-clear,
            .toolbar .filter-actions .btn-apply {
                flex: 1;
                text-align: center;
            }
            .table-card .card-header {
                padding: 14px 16px;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .table-card .card-header .bulk-actions {
                flex-wrap: wrap;
            }
            .table-card table {
                font-size: 12px;
                min-width: 700px;
            }
            .table-card table th,
            .table-card table td {
                padding: 10px 12px;
            }
            .patient-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            .patient-info .name {
                max-width: 120px;
                font-size: 13px;
            }
            .patient-info .details {
                flex-direction: column;
                gap: 2px;
            }
            .modal-body {
                padding: 20px;
            }
            .modal-header {
                padding: 16px 20px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 8px 10px;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .stat-card .stat-number {
                font-size: 16px;
            }
            .stat-card .stat-label {
                font-size: 10px;
            }
            .stat-card .stat-icon {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }
            .toolbar .filter-row .status-filters .btn-filter {
                font-size: 10px;
                padding: 4px 10px;
            }
            .table-card table {
                font-size: 11px;
                min-width: 600px;
            }
            .table-card table th,
            .table-card table td {
                padding: 8px 10px;
            }
            .patient-cell {
                min-width: 140px;
                gap: 8px;
            }
            .patient-avatar {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
            .patient-info .name {
                max-width: 80px;
                font-size: 12px;
            }
            .patient-info .details span {
                font-size: 10px;
            }
            .patient-info .details i {
                font-size: 9px;
                width: 12px;
            }
            .status-badge {
                font-size: 10px;
                padding: 3px 10px;
            }
            .btn-action {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<!-- ===== TOP BAR ===== -->
<div class="top-bar">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-calendar-check"></i> Appointments</li>
        </ol>
    </nav>
    <div class="top-bar-actions">
        <a href="doctor.php" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-plus-circle"></i> New Appointment
        </a>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">
    <!-- Success/Error Messages -->
    <?php if(isset($success_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--border-radius); border-left: 4px solid #48bb78;">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--border-radius); border-left: 4px solid #fc8181;">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ===== STATISTICS ===== -->
    <div class="stats-grid">
        <a href="dashboard-appointments.php" class="stat-card stat-total">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $total; ?></div>
                <p class="stat-label">Total Appointments</p>
            </div>
        </a>
        <a href="dashboard-appointments.php?filter=pending" class="stat-card stat-pending">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-number"><?php echo isset($status_counts['pending']) ? $status_counts['pending'] : 0; ?></div>
                <p class="stat-label">Pending</p>
            </div>
        </a>
        <a href="dashboard-appointments.php?filter=confirmed" class="stat-card stat-confirmed">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-number"><?php echo isset($status_counts['confirmed']) ? $status_counts['confirmed'] : 0; ?></div>
                <p class="stat-label">Confirmed</p>
            </div>
        </a>
        <a href="dashboard-appointments.php?filter=completed" class="stat-card stat-completed">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-info">
                <div class="stat-number"><?php echo isset($status_counts['completed']) ? $status_counts['completed'] : 0; ?></div>
                <p class="stat-label">Completed</p>
            </div>
        </a>
        <a href="dashboard-appointments.php?filter=cancelled" class="stat-card stat-cancelled">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info">
                <div class="stat-number"><?php echo isset($status_counts['cancelled']) ? $status_counts['cancelled'] : 0; ?></div>
                <p class="stat-label">Cancelled</p>
            </div>
        </a>
    </div>

    <!-- ===== TOOLBAR WITH FILTERS ===== -->
    <div class="toolbar">
        <div class="filter-header">
            <h6><i class="fas fa-sliders-h"></i> Advanced Filters</h6>
            <div class="filter-actions">
                <a href="dashboard-appointments.php" class="btn-clear">
                    <i class="fas fa-times"></i> Clear All Filters
                </a>
                <button class="btn-apply" onclick="applyFilters()">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
            </div>
        </div>

        <div class="filter-row">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by patient name, email, phone, or doctor..." value="<?php echo htmlspecialchars($search); ?>">
            </div>

            <div class="date-filters">
                <div class="date-group">
                    <label><i class="fas fa-calendar-alt"></i> From</label>
                    <input type="date" id="dateFrom" value="<?php echo $date_from; ?>">
                </div>
                <span style="color: #a0aec0; font-weight: 300;">—</span>
                <div class="date-group">
                    <label>To</label>
                    <input type="date" id="dateTo" value="<?php echo $date_to; ?>">
                </div>
            </div>
        </div>

        <div class="filter-row" style="margin-top: 12px;">
            <div class="status-filters">
                <a href="dashboard-appointments.php" class="btn-filter <?php echo ($filter == 'all') ? 'active' : ''; ?>">
                    All <span class="count-badge"><?php echo $total; ?></span>
                </a>
                <a href="dashboard-appointments.php?filter=pending" class="btn-filter <?php echo ($filter == 'pending') ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i> Pending <span class="count-badge"><?php echo isset($status_counts['pending']) ? $status_counts['pending'] : 0; ?></span>
                </a>
                <a href="dashboard-appointments.php?filter=confirmed" class="btn-filter <?php echo ($filter == 'confirmed') ? 'active' : ''; ?>">
                    <i class="fas fa-check-circle"></i> Confirmed <span class="count-badge"><?php echo isset($status_counts['confirmed']) ? $status_counts['confirmed'] : 0; ?></span>
                </a>
                <a href="dashboard-appointments.php?filter=completed" class="btn-filter <?php echo ($filter == 'completed') ? 'active' : ''; ?>">
                    <i class="fas fa-check-double"></i> Completed <span class="count-badge"><?php echo isset($status_counts['completed']) ? $status_counts['completed'] : 0; ?></span>
                </a>
                <a href="dashboard-appointments.php?filter=cancelled" class="btn-filter <?php echo ($filter == 'cancelled') ? 'active' : ''; ?>">
                    <i class="fas fa-times-circle"></i> Cancelled <span class="count-badge"><?php echo isset($status_counts['cancelled']) ? $status_counts['cancelled'] : 0; ?></span>
                </a>
            </div>
        </div>

        <?php if($filter != 'all' || !empty($search) || !empty($date_from) || !empty($date_to)): ?>
            <div class="active-filters">
                <span style="font-size: 12px; font-weight: 600; color: var(--gray);">Active Filters:</span>
                
                <?php if($filter != 'all'): ?>
                    <span class="filter-tag">
                        <i class="fas fa-tag"></i> Status: <?php echo ucfirst($filter); ?>
                        <a href="dashboard-appointments.php" class="remove-filter" title="Remove this filter">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(!empty($search)): ?>
                    <span class="filter-tag">
                        <i class="fas fa-search"></i> "<?php echo htmlspecialchars($search); ?>"
                        <a href="dashboard-appointments.php<?php echo ($filter != 'all') ? '?filter='.$filter : ''; ?>" class="remove-filter" title="Remove this filter">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(!empty($date_from)): ?>
                    <span class="filter-tag">
                        <i class="fas fa-calendar-alt"></i> From: <?php echo date('d M Y', strtotime($date_from)); ?>
                        <a href="dashboard-appointments.php<?php echo ($filter != 'all') ? '?filter='.$filter : ''; ?><?php echo (!empty($search)) ? '&search='.urlencode($search) : ''; ?>" class="remove-filter" title="Remove this filter">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(!empty($date_to)): ?>
                    <span class="filter-tag">
                        <i class="fas fa-calendar-alt"></i> To: <?php echo date('d M Y', strtotime($date_to)); ?>
                        <a href="dashboard-appointments.php<?php echo ($filter != 'all') ? '?filter='.$filter : ''; ?><?php echo (!empty($search)) ? '&search='.urlencode($search) : ''; ?><?php echo (!empty($date_from)) ? '&date_from='.$date_from : ''; ?>" class="remove-filter" title="Remove this filter">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <span class="filter-tag" style="background: var(--primary); color: white; border-color: var(--primary);">
                    <i class="fas fa-list"></i> <?php echo $total; ?> results found
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="table-card">
        <div class="card-header">
            <div class="header-left">
                <h5><i class="fas fa-list-ul" style="color: var(--primary);"></i> Appointment List</h5>
                <span class="badge-count"><?php echo $total; ?> entries</span>
            </div>
            <div class="bulk-actions">
                <form method="POST" id="bulkForm" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <select name="bulk_status" id="bulkStatus" class="form-select form-select-sm" style="width: auto; border-radius: 10px; border-color: #e2e8f0; font-size: 12px;">
                        <option value="">Bulk Action</option>
                        <option value="confirmed">Mark as Confirmed</option>
                        <option value="completed">Mark as Completed</option>
                        <option value="cancelled">Mark as Cancelled</option>
                        <option value="pending">Mark as Pending</option>
                    </select>
                    <button type="submit" name="bulk_update_status" class="btn-apply" onclick="return confirm('Apply this action to selected appointments?')">
                        <i class="fas fa-check"></i> Apply
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if($appointments_result && mysqli_num_rows($appointments_result) > 0): ?>
                <form id="tableForm" method="POST">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 280px;">Patient Details</th>
                                <th style="min-width: 160px;">Doctor</th>
                                <th style="min-width: 140px;">Date & Time</th>
                                <th style="min-width: 110px;">Status</th>
                                <th style="min-width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1; 
                            while($app = mysqli_fetch_assoc($appointments_result)): 
                                $initial = !empty($app['patient_name']) ? strtoupper(substr($app['patient_name'], 0, 1)) : '?';
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_appointments[]" value="<?php echo $app['appointment_id']; ?>" class="form-check-input row-checkbox">
                                    </td>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <div class="patient-cell">
                                            <div class="patient-avatar"><?php echo $initial; ?></div>
                                            <div class="patient-info">
                                                <div class="name" title="<?php echo htmlspecialchars($app['patient_name']); ?>">
                                                    <?php echo htmlspecialchars($app['patient_name']); ?>
                                                </div>
                                                <div class="details">
                                                    <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($app['patient_email']); ?></span>
                                                    <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($app['patient_phone']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="doctor-cell">
                                            <div class="doc-name">
                                                <?php echo !empty($app['doc_name']) ? htmlspecialchars($app['doc_name']) : '<span style="color: #fc8181;">Not Assigned</span>'; ?>
                                            </div>
                                            <?php if(!empty($app['spec'])): ?>
                                                <div class="doc-spec"><i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($app['spec']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date"><i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($app['appointment_date'])); ?></div>
                                            <div class="time"><i class="far fa-clock"></i> <?php echo htmlspecialchars($app['appointment_time']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $app['status']; ?>">
                                            <i class="fas fa-circle"></i> <?php echo ucfirst($app['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <button type="button" class="btn-action btn-view" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $app['appointment_id']; ?>">
                                                <i class="fas fa-eye"></i>
                                                <span class="tooltip-text">View Details</span>
                                            </button>
                                            
                                            <?php if($app['status'] == 'pending'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" name="update_status" class="btn-action btn-verify" onclick="return confirm('Verify this appointment?')">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span class="tooltip-text">Verify</span>
                                                    </button>
                                                </form>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" name="update_status" class="btn-action btn-cancel" onclick="return confirm('Cancel this appointment?')">
                                                        <i class="fas fa-times-circle"></i>
                                                        <span class="tooltip-text">Cancel</span>
                                                    </button>
                                                </form>
                                            <?php elseif($app['status'] == 'confirmed'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" name="update_status" class="btn-action btn-complete" onclick="return confirm('Mark as completed?')">
                                                        <i class="fas fa-check-double"></i>
                                                        <span class="tooltip-text">Complete</span>
                                                    </button>
                                                </form>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" name="update_status" class="btn-action btn-cancel" onclick="return confirm('Cancel this appointment?')">
                                                        <i class="fas fa-times-circle"></i>
                                                        <span class="tooltip-text">Cancel</span>
                                                    </button>
                                                </form>
                                            <?php elseif($app['status'] == 'completed'): ?>
                                                <span class="btn-status-done"><i class="fas fa-check"></i> Done</span>
                                            <?php elseif($app['status'] == 'cancelled'): ?>
                                                <span class="btn-status-done"><i class="fas fa-ban"></i> Cancelled</span>
                                            <?php endif; ?>
                                            
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                <button type="submit" name="delete_appointment" class="btn-action btn-delete" onclick="return confirm('Permanently delete this appointment? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="tooltip-text">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- ===== VIEW MODAL ===== -->
                                <div class="modal fade" id="viewModal<?php echo $app['appointment_id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-calendar-check me-2"></i> Appointment Details
                                                    <span class="badge bg-light text-dark ms-2">#<?php echo $app['appointment_id']; ?></span>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="detail-grid">
                                                    <div class="detail-item">
                                                        <span class="label">Patient Name</span>
                                                        <span class="value"><strong><?php echo htmlspecialchars($app['patient_name']); ?></strong></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Patient Email</span>
                                                        <span class="value"><?php echo htmlspecialchars($app['patient_email']); ?></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Patient Phone</span>
                                                        <span class="value"><?php echo htmlspecialchars($app['patient_phone']); ?></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Doctor</span>
                                                        <span class="value">
                                                            <?php if(!empty($app['doc_name'])): ?>
                                                                <strong><?php echo htmlspecialchars($app['doc_name']); ?></strong>
                                                                <?php if(!empty($app['spec'])): ?>
                                                                    <br><small style="color: var(--gray);"><?php echo htmlspecialchars($app['spec']); ?></small>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span style="color: #fc8181;">Not assigned</span>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Appointment Date</span>
                                                        <span class="value"><strong><?php echo date('l, d M Y', strtotime($app['appointment_date'])); ?></strong></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Appointment Time</span>
                                                        <span class="value"><strong><?php echo htmlspecialchars($app['appointment_time']); ?></strong></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Status</span>
                                                        <span class="value">
                                                            <span class="status-badge status-<?php echo $app['status']; ?>">
                                                                <i class="fas fa-circle"></i> <?php echo ucfirst($app['status']); ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="label">Booked On</span>
                                                        <span class="value"><?php echo date('l, d M Y h:i A', strtotime($app['created_at'])); ?></span>
                                                    </div>
                                                    <?php if(!empty($app['message'])): ?>
                                                        <div class="detail-item full-width">
                                                            <span class="label">Additional Message</span>
                                                            <span class="value" style="background: #f7fafc; padding: 12px 16px; border-radius: 10px; margin-top: 4px; font-weight: 400; line-height: 1.6;">
                                                                <?php echo nl2br(htmlspecialchars($app['message'])); ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    <i class="fas fa-times"></i> Close
                                                </button>
                                                <?php if($app['status'] == 'pending'): ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" name="update_status" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check-circle"></i> Verify
                                                        </button>
                                                    </form>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" name="update_status" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times-circle"></i> Cancel
                                                        </button>
                                                    </form>
                                                <?php elseif($app['status'] == 'confirmed'): ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" name="update_status" class="btn btn-info btn-sm" style="background: #805ad5; border-color: #805ad5;">
                                                            <i class="fas fa-check-double"></i> Complete
                                                        </button>
                                                    </form>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" name="update_status" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times-circle"></i> Cancel
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </form>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                    <h4>No Appointments Found</h4>
                    <p>No appointments match your current filters. Try adjusting your search or filters.</p>
                    <a href="dashboard-appointments.php" class="btn" style="background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 10px 28px; font-weight: 600;">
                        <i class="fas fa-undo"></i> Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // Search with debounce
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const value = this.value;
                const url = new URL(window.location.href);
                if(value) {
                    url.searchParams.set('search', value);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 500);
        });
    }

    // Date filters
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    
    function applyDateFilters() {
        const url = new URL(window.location.href);
        if(dateFrom && dateFrom.value) {
            url.searchParams.set('date_from', dateFrom.value);
        } else {
            url.searchParams.delete('date_from');
        }
        if(dateTo && dateTo.value) {
            url.searchParams.set('date_to', dateTo.value);
        } else {
            url.searchParams.delete('date_to');
        }
        window.location.href = url.toString();
    }
    
    if(dateFrom) {
        dateFrom.addEventListener('change', applyDateFilters);
    }
    if(dateTo) {
        dateTo.addEventListener('change', applyDateFilters);
    }

    // Apply Filters Function
    window.applyFilters = function() {
        const url = new URL(window.location.href);
        
        const searchVal = document.getElementById('searchInput').value;
        if(searchVal) {
            url.searchParams.set('search', searchVal);
        } else {
            url.searchParams.delete('search');
        }
        
        const fromVal = document.getElementById('dateFrom').value;
        const toVal = document.getElementById('dateTo').value;
        
        if(fromVal) {
            url.searchParams.set('date_from', fromVal);
        } else {
            url.searchParams.delete('date_from');
        }
        if(toVal) {
            url.searchParams.set('date_to', toVal);
        } else {
            url.searchParams.delete('date_to');
        }
        
        window.location.href = url.toString();
    };

    // Bulk form validation
    const bulkForm = document.getElementById('bulkForm');
    if(bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.row-checkbox:checked');
            if(selected.length === 0) {
                e.preventDefault();
                alert('Please select at least one appointment.');
                return false;
            }
            const status = document.getElementById('bulkStatus').value;
            if(!status) {
                e.preventDefault();
                alert('Please select an action to apply.');
                return false;
            }
        });
    }

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if(closeBtn) closeBtn.click();
        }, 5000);
    });
});
</script>

<?php include('dashboard-footer.php'); ?>