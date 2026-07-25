<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

// Handle Delete
if(isset($_GET['delete_medicine']) && !empty($_GET['delete_medicine'])) {
    $medicine_id = mysqli_real_escape_string($connect, $_GET['delete_medicine']);
    mysqli_query($connect, "DELETE FROM medicines WHERE medicine_id = '$medicine_id'");
    $_SESSION['success'] = "Medicine deleted successfully!";
    header("Location: dashboard-medicines.php");
    exit();
}

// Handle Toggle Status
if(isset($_GET['toggle_medicine']) && !empty($_GET['toggle_medicine'])) {
    $medicine_id = mysqli_real_escape_string($connect, $_GET['toggle_medicine']);
    $status_query = mysqli_query($connect, "SELECT status FROM medicines WHERE medicine_id = '$medicine_id'");
    $status_data = mysqli_fetch_assoc($status_query);
    $new_status = ($status_data['status'] == 'active') ? 'inactive' : 'active';
    mysqli_query($connect, "UPDATE medicines SET status = '$new_status' WHERE medicine_id = '$medicine_id'");
    $_SESSION['success'] = "Status updated successfully!";
    header("Location: dashboard-medicines.php");
    exit();
}

// Handle Bulk Delete
if(isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    if(!empty($selected_ids)) {
        $ids = implode("','", array_map(function($id) use ($connect) {
            return mysqli_real_escape_string($connect, $id);
        }, $selected_ids));
        mysqli_query($connect, "DELETE FROM medicines WHERE medicine_id IN ('$ids')");
        $_SESSION['success'] = count($selected_ids) . " medicines deleted successfully!";
        header("Location: dashboard-medicines.php");
        exit();
    }
}

// Handle Bulk Status Update
if(isset($_POST['bulk_status']) && isset($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    $new_status = mysqli_real_escape_string($connect, $_POST['bulk_status_action']);
    if(!empty($selected_ids) && !empty($new_status)) {
        $ids = implode("','", array_map(function($id) use ($connect) {
            return mysqli_real_escape_string($connect, $id);
        }, $selected_ids));
        mysqli_query($connect, "UPDATE medicines SET status = '$new_status' WHERE medicine_id IN ('$ids')");
        $_SESSION['success'] = count($selected_ids) . " medicines updated to " . ucfirst($new_status) . "!";
        header("Location: dashboard-medicines.php");
        exit();
    }
}

// Handle Export
if(isset($_GET['export'])) {
    $export_data = mysqli_query($connect, "SELECT * FROM medicines ORDER BY medicine_id DESC");
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="medicines_export.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Medicine Name', 'Generic Name', 'Category', 'Price', 'Stock', 'Status'));
    while($row = mysqli_fetch_assoc($export_data)) {
        fputcsv($output, array(
            $row['medicine_id'],
            $row['medicine_name'],
            $row['generic_name'] ?? '-',
            $row['category'] ?? '-',
            $row['price'] ?? 0,
            $row['stock'] ?? 0,
            $row['status'] ?? 'active'
        ));
    }
    fclose($output);
    exit();
}

$medicines = mysqli_query($connect, "SELECT * FROM medicines ORDER BY medicine_id DESC");
$total = mysqli_num_rows($medicines);

// Get counts for stats
$active_count = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines WHERE status = 'active'"));
$inactive_count = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines WHERE status = 'inactive'"));
$low_stock = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines WHERE stock < 10 AND stock > 0"));
$out_of_stock = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM medicines WHERE stock = 0"));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medicines - Docpedia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 15px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e8ecf1;
            text-align: center;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1.2;
        }
        .stat-card .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            font-weight: 500;
        }
        .stat-card .stat-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .stat-total .stat-icon { color: #667eea; }
        .stat-active .stat-icon { color: #2ecc71; }
        .stat-inactive .stat-icon { color: #e74c3c; }
        .stat-low .stat-icon { color: #f39c12; }
        .stat-out .stat-icon { color: #e74c3c; }

        /* ===== TOOLBAR ===== */
        .toolbar {
            background: white;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 20px;
            border: 1px solid #e8ecf1;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }
        .toolbar .search-box {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
        .toolbar .search-box input {
            width: 100%;
            padding: 8px 15px 8px 40px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s;
        }
        .toolbar .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .toolbar .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        .toolbar .bulk-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .toolbar .bulk-actions select,
        .toolbar .bulk-actions input[type="number"] {
            padding: 7px 12px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-size: 12px;
            background: white;
            font-family: inherit;
        }
        .toolbar .bulk-actions select:focus,
        .toolbar .bulk-actions input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            padding: 7px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
        .btn-info { background: #3498db; }
        .btn-info:hover { background: #2980b9; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #e67e22; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-secondary { background: #95a5a6; }
        .btn-secondary:hover { background: #7f8c8d; }
        .btn-sm { padding: 4px 12px; font-size: 11px; }
        .btn-xs { padding: 2px 10px; font-size: 10px; }

        /* ===== TABLE ===== */
        .table-wrapper {
            background: white;
            border-radius: 8px;
            overflow: auto;
            border: 1px solid #e8ecf1;
        }
        .table-wrapper table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 1100px;
        }
        .table-wrapper table thead {
            background: #1a1a2e;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table-wrapper table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table-wrapper table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        .table-wrapper table tbody tr:hover {
            background: #f8f9fa;
        }
        .table-wrapper table .checkbox-col {
            width: 35px;
            text-align: center;
        }
        .table-wrapper table .checkbox-col input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* ===== STATUS BADGE ===== */
        .badge-status {
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }

        /* ===== STOCK BADGE ===== */
        .stock-badge {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .stock-high { background: #d4edda; color: #155724; }
        .stock-medium { background: #fff3cd; color: #856404; }
        .stock-low { background: #f8d7da; color: #721c24; }

        /* ===== ACTION BUTTONS GROUP ===== */
        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: center;
        }
        .action-group .btn {
            padding: 4px 12px;
            font-size: 11px;
            border-radius: 15px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .toolbar .search-box {
                min-width: auto;
            }
            .toolbar .bulk-actions {
                flex-wrap: wrap;
            }
        }
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .stat-card .stat-number {
                font-size: 20px;
            }
            .action-group .btn {
                font-size: 10px;
                padding: 3px 8px;
            }
        }
    </style>
</head>
<body>

<div style="margin-top:120px;padding:20px;background:#f4f6f9;min-height:100vh;">
    <div class="container-fluid">
        
        <!-- ===== HEADER ===== -->
        <div style="background:white;padding:15px 20px;border-radius:8px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;border:1px solid #e8ecf1;">
            <div>
                <h3 style="margin:0;font-size:20px;color:#1a1a2e;">
                    <i class="fas fa-pills" style="color:#667eea;"></i> Manage Medicines
                    <span style="background:#667eea;color:white;padding:2px 12px;border-radius:30px;font-size:14px;margin-left:10px;"><?php echo $total; ?></span>
                </h3>
                <small style="color:#6b7280;"><i class="fas fa-arrow-left"></i> <a href="dashboard.php" style="color:#667eea;text-decoration:none;">Back to Dashboard</a></small>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="?export" class="btn btn-secondary" style="background:#95a5a6;">
                    <i class="fas fa-file-export"></i> Export
                </a>
                <a href="dashboard-add-medicine.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
        </div>
        
        <!-- ===== ALERT ===== -->
        <?php if(isset($_SESSION['success'])): ?>
            <div style="background:#d4edda;padding:12px 20px;border-radius:8px;margin-bottom:15px;color:#155724;border:1px solid #c3e6cb;display:flex;justify-content:space-between;align-items:center;">
                <span><i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                <span style="cursor:pointer;font-weight:bold;" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>
        
        <!-- ===== STATISTICS ===== -->
        <div class="stats-grid">
            <div class="stat-card stat-total">
                <div class="stat-icon"><i class="fas fa-pills"></i></div>
                <div class="stat-number"><?php echo $total; ?></div>
                <p class="stat-label">Total Medicines</p>
            </div>
            <div class="stat-card stat-active">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?php echo $active_count; ?></div>
                <p class="stat-label">Active</p>
            </div>
            <div class="stat-card stat-inactive">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number"><?php echo $inactive_count; ?></div>
                <p class="stat-label">Inactive</p>
            </div>
            <div class="stat-card stat-low">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number"><?php echo $low_stock; ?></div>
                <p class="stat-label">Low Stock (&lt;10)</p>
            </div>
            <div class="stat-card stat-out">
                <div class="stat-icon"><i class="fas fa-times"></i></div>
                <div class="stat-number"><?php echo $out_of_stock; ?></div>
                <p class="stat-label">Out of Stock</p>
            </div>
        </div>
        
        <!-- ===== TOOLBAR ===== -->
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name, generic, category..." onkeyup="searchTable()">
            </div>
            
            <form method="POST" id="bulkForm" style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;">
                <div class="bulk-actions">
                    <select name="bulk_status_action" id="bulkStatusAction">
                        <option value="">Bulk Action</option>
                        <option value="active">Activate</option>
                        <option value="inactive">Deactivate</option>
                    </select>
                    <button type="submit" name="bulk_status" class="btn btn-sm btn-info" onclick="return confirmBulk('status')">
                        <i class="fas fa-check"></i> Apply Status
                    </button>
                    <button type="submit" name="bulk_delete" class="btn btn-sm btn-danger" onclick="return confirmBulk('delete')">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
            </form>
        </div>
        
        <!-- ===== TABLE ===== -->
        <div class="table-wrapper">
            <form method="POST" id="tableForm">
                <table>
                    <thead>
                        <tr>
                            <th class="checkbox-col">
                                <input type="checkbox" id="selectAll" onclick="toggleAll()">
                            </th>
                            <th style="width:50px;">ID</th>
                            <th style="min-width:150px;">Medicine</th>
                            <th style="min-width:120px;">Generic</th>
                            <th style="min-width:120px;">Category</th>
                            <th style="width:100px;">Price</th>
                            <th style="width:80px;">Stock</th>
                            <th style="width:100px;">Status</th>
                            <th style="min-width:350px;text-align:center;">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($total > 0): 
                            while($row = mysqli_fetch_assoc($medicines)): 
                                $status = $row['status'] ?? 'active';
                                $stock = $row['stock'] ?? 0;
                                
                                // Stock class
                                $stock_class = 'stock-high';
                                if($stock == 0) $stock_class = 'stock-low';
                                elseif($stock < 10) $stock_class = 'stock-medium';
                                
                                $toggle_text = ($status == 'active') ? 'Deactivate' : 'Activate';
                                $toggle_icon = ($status == 'active') ? 'fa-pause' : 'fa-play';
                                $toggle_bg = ($status == 'active') ? '#e74c3c' : '#2ecc71';
                        ?>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" name="selected_ids[]" value="<?php echo $row['medicine_id']; ?>" class="row-checkbox">
                                </td>
                                <td style="font-weight:bold;"><?php echo $row['medicine_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['medicine_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['generic_name'] ?? '-'); ?></td>
                                <td>
                                    <span style="background:#e8ecf1;padding:2px 12px;border-radius:15px;font-size:11px;">
                                        <?php echo htmlspecialchars($row['category'] ?? 'General'); ?>
                                    </span>
                                </td>
                                <td style="font-weight:bold;color:#2ecc71;">Rs. <?php echo number_format($row['price'] ?? 0, 2); ?></td>
                                <td>
                                    <span class="stock-badge <?php echo $stock_class; ?>">
                                        <?php echo $stock; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status <?php echo ($status == 'active') ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <!-- VIEW -->
                                        <a href="dashboard-view-medicine.php?id=<?php echo $row['medicine_id']; ?>" 
                                           class="btn btn-sm btn-success" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <!-- EDIT -->
                                        <a href="dashboard-edit-medicine.php?id=<?php echo $row['medicine_id']; ?>" 
                                           class="btn btn-sm btn-info" title="Edit Medicine">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <!-- TOGGLE STATUS -->
                                        <a href="?toggle_medicine=<?php echo $row['medicine_id']; ?>" 
                                           onclick="return confirm('<?php echo $toggle_text; ?> this medicine?')"
                                           class="btn btn-sm" style="background:<?php echo $toggle_bg; ?>;color:white;" title="<?php echo $toggle_text; ?>">
                                            <i class="fas <?php echo $toggle_icon; ?>"></i> <?php echo $toggle_text; ?>
                                        </a>
                                        
                                        <!-- DELETE -->
                                        <a href="?delete_medicine=<?php echo $row['medicine_id']; ?>" 
                                           onclick="return confirm('Delete <?php echo htmlspecialchars($row['medicine_name']); ?> permanently?')"
                                           class="btn btn-sm btn-danger" title="Delete Permanently">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                        
                                        <!-- QUICK VIEW (Modal style) -->
                                        <button onclick="quickView(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
                                                class="btn btn-sm btn-secondary" style="background:#6c757d;" title="Quick View">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center;padding:50px 20px;">
                                    <i class="fas fa-pills" style="font-size:50px;color:#ddd;"></i>
                                    <h4 style="color:#1a1a2e;margin:10px 0 5px;">No Medicines Found</h4>
                                    <p style="color:#7f8c8d;">Click <a href="dashboard-add-medicine.php" style="color:#667eea;">Add New</a> to get started.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
        
        <!-- ===== FOOTER INFO ===== -->
        <div style="margin-top:15px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;color:#6b7280;font-size:12px;">
            <span><i class="fas fa-database"></i> Total: <?php echo $total; ?> records</span>
            <span>
                <i class="fas fa-circle" style="color:#2ecc71;font-size:8px;"></i> Active: <?php echo $active_count; ?> &nbsp;
                <i class="fas fa-circle" style="color:#e74c3c;font-size:8px;"></i> Inactive: <?php echo $inactive_count; ?>
            </span>
        </div>
        
    </div>
</div>

<!-- ===== QUICK VIEW MODAL ===== -->
<div id="quickViewModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;justify-content:center;align-items:center;">
    <div style="background:white;border-radius:12px;padding:30px;max-width:500px;width:90%;max-height:80vh;overflow:auto;position:relative;">
        <span onclick="document.getElementById('quickViewModal').style.display='none'" style="position:absolute;top:12px;right:18px;font-size:24px;cursor:pointer;color:#999;">&times;</span>
        <h4 style="margin:0 0 15px;color:#1a1a2e;"><i class="fas fa-pills" style="color:#667eea;"></i> Medicine Details</h4>
        <div id="quickViewContent"></div>
    </div>
</div>

<script>
// ===== SEARCH TABLE =====
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.table-wrapper tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

// ===== SELECT ALL =====
function toggleAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

// ===== CONFIRM BULK =====
function confirmBulk(action) {
    const selected = document.querySelectorAll('.row-checkbox:checked');
    if(selected.length === 0) {
        alert('Please select at least one medicine.');
        return false;
    }
    
    if(action === 'delete') {
        return confirm('Delete ' + selected.length + ' selected medicine(s) permanently?');
    } else {
        const status = document.getElementById('bulkStatusAction').value;
        if(!status) {
            alert('Please select a status action.');
            return false;
        }
        return confirm('Apply "' + status + '" to ' + selected.length + ' selected medicine(s)?');
    }
}

// ===== QUICK VIEW =====
function quickView(data) {
    const modal = document.getElementById('quickViewModal');
    const content = document.getElementById('quickViewContent');
    
    const statusColor = data.status === 'active' ? '#2ecc71' : '#e74c3c';
    
    content.innerHTML = `
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Medicine:</strong>
            <span style="font-weight:bold;font-size:16px;">${data.medicine_name}</span>
        </div>
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Generic:</strong>
            ${data.generic_name || '-'}
        </div>
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Category:</strong>
            ${data.category || 'General'}
        </div>
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Price:</strong>
            <span style="font-weight:bold;color:#2ecc71;">Rs. ${parseFloat(data.price || 0).toFixed(2)}</span>
        </div>
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Stock:</strong>
            <span style="font-weight:bold;">${data.stock || 0}</span>
        </div>
        <div style="margin-bottom:12px;">
            <strong style="display:inline-block;width:100px;color:#6b7280;">Status:</strong>
            <span class="badge-status ${data.status === 'active' ? 'badge-active' : 'badge-inactive'}">${data.status || 'active'}</span>
        </div>
        <div style="margin-top:20px;border-top:1px solid #eee;padding-top:15px;display:flex;gap:8px;flex-wrap:wrap;">
            <a href="dashboard-view-medicine.php?id=${data.medicine_id}" class="btn btn-sm btn-success">
                <i class="fas fa-eye"></i> Full View
            </a>
            <a href="dashboard-edit-medicine.php?id=${data.medicine_id}" class="btn btn-sm btn-info">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="document.getElementById('quickViewModal').style.display='none'" class="btn btn-sm btn-secondary">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    `;
    
    modal.style.display = 'flex';
}

// ===== CLOSE MODAL ON OVERLAY CLICK =====
document.getElementById('quickViewModal').addEventListener('click', function(e) {
    if(e.target === this) {
        this.style.display = 'none';
    }
});

// ===== AUTO CLOSE ALERTS =====
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert, [style*="background:#d4edda"]');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if(alert) alert.style.display = 'none';
        }, 5000);
    });
});
</script>

<?php include('dashboard-footer.php'); ?>