<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

// Handle Delete
if(isset($_GET['delete_pharmacy']) && !empty($_GET['delete_pharmacy'])) {
    $pharmacy_id = mysqli_real_escape_string($connect, $_GET['delete_pharmacy']);
    $delete_query = "DELETE FROM pharmacies WHERE pharmacy_id = '$pharmacy_id'";
    if(mysqli_query($connect, $delete_query)) {
        $_SESSION['success'] = "Pharmacy deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($connect);
    }
    header("Location: dashboard-pharmacies.php");
    exit();
}

// Handle Toggle
if(isset($_GET['toggle_pharmacy']) && !empty($_GET['toggle_pharmacy'])) {
    $pharmacy_id = mysqli_real_escape_string($connect, $_GET['toggle_pharmacy']);
    $status_query = "SELECT status FROM pharmacies WHERE pharmacy_id = '$pharmacy_id'";
    $status_result = mysqli_query($connect, $status_query);
    $status_data = mysqli_fetch_assoc($status_result);
    $new_status = ($status_data['status'] == 'active') ? 'inactive' : 'active';
    $update_query = "UPDATE pharmacies SET status = '$new_status' WHERE pharmacy_id = '$pharmacy_id'";
    if(mysqli_query($connect, $update_query)) {
        $_SESSION['success'] = "Status updated!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($connect);
    }
    header("Location: dashboard-pharmacies.php");
    exit();
}

// Get data
$query = "SELECT * FROM pharmacies ORDER BY pharmacy_id DESC";
$pharmacies = mysqli_query($connect, $query);
$total = mysqli_num_rows($pharmacies);
?>

<!-- ============================================
     DIRECT INLINE STYLING - NO CONFLICTS
     ============================================ -->
<style>
    /* RESET ANY CONFLICTS */
    .pharmacy-container * {
        box-sizing: border-box;
    }
    
    .pharmacy-container {
        margin-top: 100px;
        padding: 30px;
        background: #f4f6f9;
        min-height: 100vh;
    }
    
    /* HEADER */
    .pharmacy-container .ph-header {
        background: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .pharmacy-container .ph-header h3 {
        margin: 0;
        font-size: 22px;
        color: #1a1a2e;
        font-weight: 700;
    }
    .pharmacy-container .ph-header h3 i {
        color: #ffc107;
        margin-right: 10px;
    }
    .pharmacy-container .ph-header .count-badge {
        background: #667eea;
        color: white;
        padding: 3px 14px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        margin-left: 8px;
    }
    
    /* ADD BUTTON */
    .pharmacy-container .btn-add-pharmacy {
        background: #2ecc71;
        color: white !important;
        padding: 10px 25px;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }
    .pharmacy-container .btn-add-pharmacy:hover {
        background: #27ae60;
        transform: translateY(-2px);
        color: white !important;
    }
    
    /* TABLE */
    .pharmacy-container .ph-table-wrap {
        background: white;
        border-radius: 10px;
        overflow: auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .pharmacy-container .ph-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 800px;
    }
    .pharmacy-container .ph-table thead {
        background: #1a1a2e;
        color: white;
    }
    .pharmacy-container .ph-table thead th {
        padding: 14px 16px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .pharmacy-container .ph-table tbody tr {
        border-bottom: 1px solid #f0f2f5;
        transition: 0.3s;
    }
    .pharmacy-container .ph-table tbody tr:hover {
        background: #f8faff;
    }
    .pharmacy-container .ph-table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
    }
    .pharmacy-container .ph-table tbody td strong {
        color: #1a1a2e;
    }
    
    /* STATUS BADGE */
    .pharmacy-container .status-badge {
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
    }
    .pharmacy-container .status-badge.active {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .pharmacy-container .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* ============================================
       COLORFUL ACTION BUTTONS - DIRECT STYLING
       ============================================ */
    .pharmacy-container .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    
    /* ALL BUTTONS COMMON */
    .pharmacy-container .action-buttons .btn-action {
        padding: 6px 16px;
        border: none;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
        cursor: pointer;
        white-space: nowrap;
        font-family: inherit;
        line-height: 1.5;
        min-height: 32px;
    }
    .pharmacy-container .action-buttons .btn-action:hover {
        transform: translateY(-2px);
        color: white !important;
    }
    .pharmacy-container .action-buttons .btn-action i {
        font-size: 12px;
    }
    
    /* VIEW - GREEN */
    .pharmacy-container .action-buttons .btn-view {
        background: #2ecc71;
        color: white !important;
        box-shadow: 0 3px 10px rgba(46,204,113,0.25);
    }
    .pharmacy-container .action-buttons .btn-view:hover {
        background: #27ae60;
        box-shadow: 0 6px 20px rgba(46,204,113,0.35);
        color: white !important;
    }
    
    /* EDIT - BLUE */
    .pharmacy-container .action-buttons .btn-edit {
        background: #3498db;
        color: white !important;
        box-shadow: 0 3px 10px rgba(52,152,219,0.25);
    }
    .pharmacy-container .action-buttons .btn-edit:hover {
        background: #2980b9;
        box-shadow: 0 6px 20px rgba(52,152,219,0.35);
        color: white !important;
    }
    
    /* TOGGLE - YELLOW */
    .pharmacy-container .action-buttons .btn-toggle {
        background: #f39c12;
        color: white !important;
        box-shadow: 0 3px 10px rgba(243,156,18,0.25);
    }
    .pharmacy-container .action-buttons .btn-toggle:hover {
        background: #e67e22;
        box-shadow: 0 6px 20px rgba(243,156,18,0.35);
        color: white !important;
    }
    .pharmacy-container .action-buttons .btn-toggle.active-btn {
        background: #2ecc71;
        box-shadow: 0 3px 10px rgba(46,204,113,0.25);
    }
    .pharmacy-container .action-buttons .btn-toggle.active-btn:hover {
        background: #27ae60;
        box-shadow: 0 6px 20px rgba(46,204,113,0.35);
    }
    
    /* DELETE - RED */
    .pharmacy-container .action-buttons .btn-delete {
        background: #e74c3c;
        color: white !important;
        box-shadow: 0 3px 10px rgba(231,76,60,0.25);
    }
    .pharmacy-container .action-buttons .btn-delete:hover {
        background: #c0392b;
        box-shadow: 0 6px 20px rgba(231,76,60,0.35);
        color: white !important;
    }
    
    /* ALERTS */
    .pharmacy-container .alert-box {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pharmacy-container .alert-box.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .pharmacy-container .alert-box.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .pharmacy-container .alert-box i {
        font-size: 18px;
    }
    
    /* EMPTY STATE */
    .pharmacy-container .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .pharmacy-container .empty-state i {
        font-size: 50px;
        color: #ddd;
    }
    .pharmacy-container .empty-state h4 {
        color: #1a1a2e;
        margin: 10px 0 5px;
        font-weight: 700;
    }
    .pharmacy-container .empty-state p {
        color: #7f8c8d;
        margin: 0;
    }
    
    /* DEBUG */
    .pharmacy-container .debug-info {
        background: #fff3cd;
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 15px;
        border: 1px solid #ffc107;
        font-size: 13px;
    }
    .pharmacy-container .debug-info strong {
        color: #856404;
    }
    .pharmacy-container .debug-info .highlight {
        background: #ffc107;
        padding: 2px 10px;
        border-radius: 4px;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .pharmacy-container .ph-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .pharmacy-container .ph-table-wrap {
            overflow-x: auto;
        }
        .pharmacy-container .ph-table {
            min-width: 700px;
            font-size: 12px;
        }
        .pharmacy-container .action-buttons {
            flex-direction: column;
            gap: 4px;
        }
        .pharmacy-container .action-buttons .btn-action {
            width: 100%;
            justify-content: center;
            padding: 8px 16px;
        }
        .pharmacy-container .btn-add-pharmacy {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="pharmacy-container">
    <div class="container-fluid">
        
        <!-- ALERTS -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-box success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-box error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <!-- HEADER -->
        <div class="ph-header">
            <h3>
                <i class="fas fa-hospital"></i> Manage Pharmacies
                <span class="count-badge"><?php echo $total; ?></span>
            </h3>
            <a href="dashboard-add-pharmacy.php" class="btn-add-pharmacy">
                <i class="fas fa-plus-circle"></i> Add Pharmacy
            </a>
        </div>
        
        <!-- TABLE -->
        <div class="ph-table-wrap">
            <table class="ph-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pharmacy Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="min-width:290px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total > 0): 
                        $i = 1;
                        while($row = mysqli_fetch_assoc($pharmacies)): 
                            $status = isset($row['status']) ? $row['status'] : 'active';
                            $toggle_text = ($status == 'active') ? 'Deactivate' : 'Activate';
                            $toggle_icon = ($status == 'active') ? 'fa-pause' : 'fa-play';
                            $toggle_class = ($status == 'active') ? 'active-btn' : '';
                    ?>
                        <tr>
                            <td><strong><?php echo $i++; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($row['pharmacy_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['address'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['email'] ?? '-'); ?></td>
                            <td>
                                <span class="status-badge <?php echo $status; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- VIEW - GREEN -->
                                    <a href="dashboard-view-pharmacy.php?id=<?php echo $row['pharmacy_id']; ?>" 
                                       class="btn-action btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                    <!-- EDIT - BLUE -->
                                    <a href="dashboard-edit-pharmacy.php?id=<?php echo $row['pharmacy_id']; ?>" 
                                       class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    <!-- TOGGLE - YELLOW -->
                                    <a href="?toggle_pharmacy=<?php echo $row['pharmacy_id']; ?>" 
                                       class="btn-action btn-toggle <?php echo $toggle_class; ?>"
                                       onclick="return confirm('Are you sure you want to <?php echo strtolower($toggle_text); ?> this pharmacy?')">
                                        <i class="fas <?php echo $toggle_icon; ?>"></i>
                                        <?php echo $toggle_text; ?>
                                    </a>
                                    
                                    <!-- DELETE - RED -->
                                    <a href="?delete_pharmacy=<?php echo $row['pharmacy_id']; ?>" 
                                       class="btn-action btn-delete"
                                       onclick="return confirm('⚠️ Delete <?php echo htmlspecialchars($row['pharmacy_name']); ?>? This cannot be undone!')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-hospital"></i>
                                    <h4>No Pharmacies Found</h4>
                                    <p>Click "Add Pharmacy" to get started.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- ============================================
             DEBUG INFO - SHOWS DATA STATUS
             ============================================ -->
        <div class="debug-info">
            <strong><i class="fas fa-info-circle"></i> Debug Info:</strong><br>
            Total Pharmacies in Database: <span class="highlight"><?php echo $total; ?></span><br>
            <?php if($total == 0): ?>
                <span style="color:#856404;">⚠️ No pharmacies found. Please add some data.</span>
            <?php else: ?>
                <span style="color:#155724;">✅ Data found. Buttons should be visible above.</span>
            <?php endif; ?>
        </div>
        
        <!-- ============================================
             TEST BUTTONS - ALWAYS VISIBLE
             ============================================ -->
        <div style="margin-top:20px;padding:20px;background:#e8f4fd;border-radius:10px;border:2px dashed #3498db;">
            <h5 style="margin:0 0 10px 0;color:#1a1a2e;">
                <i class="fas fa-tools"></i> Test Buttons (Always Visible)
            </h5>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button style="background:#2ecc71;color:white;padding:8px 20px;border:none;border-radius:25px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-eye"></i> View (Green)
                </button>
                <button style="background:#3498db;color:white;padding:8px 20px;border:none;border-radius:25px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-edit"></i> Edit (Blue)
                </button>
                <button style="background:#f39c12;color:white;padding:8px 20px;border:none;border-radius:25px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-pause"></i> Deactivate (Yellow)
                </button>
                <button style="background:#e74c3c;color:white;padding:8px 20px;border:none;border-radius:25px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-trash"></i> Delete (Red)
                </button>
            </div>
            <p style="margin:10px 0 0 0;font-size:13px;color:#2980b9;">
                ✅ If you see these test buttons but not the ones in the table, then the issue is with the data in your database.
            </p>
        </div>
        
    </div>
</div>

<?php include('dashboard-footer.php'); ?>