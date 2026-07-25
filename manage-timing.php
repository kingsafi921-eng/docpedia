<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

$message = '';
$message_type = '';

// Get all doctors
$doctors = mysqli_query($connect, "SELECT doc_id, doc_name FROM doctors ORDER BY doc_name");

// Get selected doctor
$selected_doctor = 0;
if(isset($_GET['doc_id']) && $_GET['doc_id'] > 0) {
    $selected_doctor = intval($_GET['doc_id']);
}

// SAVE TIMING - SIMPLE VERSION
if(isset($_POST['save_timing'])) {
    $doc_id = intval($_POST['doc_id']);
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $all_saved = true;
    
    foreach($days as $day) {
        $clinic_from = mysqli_real_escape_string($connect, $_POST['clinic_from_'.$day] ?? '');
        $clinic_to = mysqli_real_escape_string($connect, $_POST['clinic_to_'.$day] ?? '');
        $clinic_closed = isset($_POST['clinic_closed_'.$day]) ? 1 : 0;
        
        $hospital_from = mysqli_real_escape_string($connect, $_POST['hospital_from_'.$day] ?? '');
        $hospital_to = mysqli_real_escape_string($connect, $_POST['hospital_to_'.$day] ?? '');
        $hospital_closed = isset($_POST['hospital_closed_'.$day]) ? 1 : 0;
        
        // Check if exists
        $check = mysqli_query($connect, "SELECT id FROM doctor_timing WHERE doc_id = '$doc_id' AND day_name = '$day'");
        
        if(mysqli_num_rows($check) > 0) {
            $sql = "UPDATE doctor_timing SET 
                    clinic_from = '$clinic_from',
                    clinic_to = '$clinic_to',
                    clinic_closed = '$clinic_closed',
                    hospital_from = '$hospital_from',
                    hospital_to = '$hospital_to',
                    hospital_closed = '$hospital_closed'
                    WHERE doc_id = '$doc_id' AND day_name = '$day'";
        } else {
            $sql = "INSERT INTO doctor_timing 
                    (doc_id, day_name, clinic_from, clinic_to, clinic_closed, hospital_from, hospital_to, hospital_closed) 
                    VALUES 
                    ('$doc_id', '$day', '$clinic_from', '$clinic_to', '$clinic_closed', '$hospital_from', '$hospital_to', '$hospital_closed')";
        }
        
        if(!mysqli_query($connect, $sql)) {
            $all_saved = false;
            $message = "Error: " . mysqli_error($connect);
            $message_type = "danger";
            break;
        }
    }
    
    if($all_saved) {
        $message = "✅ Timing updated successfully!";
        $message_type = "success";
    }
}

// Get timing data
$timing_data = [];
$doctor_name = '';

if($selected_doctor > 0) {
    $doc_result = mysqli_query($connect, "SELECT doc_name FROM doctors WHERE doc_id = '$selected_doctor'");
    $doc_row = mysqli_fetch_assoc($doc_result);
    $doctor_name = $doc_row['doc_name'] ?? '';
    
    $timing_result = mysqli_query($connect, "SELECT * FROM doctor_timing WHERE doc_id = '$selected_doctor'");
    while($row = mysqli_fetch_assoc($timing_result)) {
        $timing_data[$row['day_name']] = $row;
    }
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctor Timing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .main-wrapper {
            margin-top: 100px;
            padding: 30px;
        }
        .card-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
        }
        .timing-table th {
            background: #667eea;
            color: white;
            text-align: center;
            padding: 10px;
            vertical-align: middle;
        }
        .timing-table td {
            padding: 8px;
            vertical-align: middle;
        }
        .day-name {
            font-weight: bold;
            text-align: center;
            background: #f8f9fa;
        }
        .time-input {
            width: 100%;
            padding: 6px 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 13px;
        }
        .time-input:focus {
            border-color: #667eea;
            outline: none;
        }
        .time-input:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }
        .status-badge {
            padding: 3px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }
        .status-badge.open {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.closed {
            background: #f8d7da;
            color: #721c24;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea, #5a67d8);
            border: none;
            border-radius: 10px;
            padding: 12px 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102,126,234,0.3);
            color: white;
        }
        .alert-fixed {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .checkbox-group label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .clinic-header {
            background: #28a745 !important;
        }
        .hospital-header {
            background: #6f42c1 !important;
        }
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 15px;
                margin-top: 80px;
            }
            .time-input {
                font-size: 11px;
                padding: 4px 6px;
            }
            .btn-save {
                padding: 10px 25px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Alert Message -->
<?php if($message): ?>
<div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show alert-fixed" role="alert">
    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
    <?php echo $message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<script>
setTimeout(function() {
    var alert = document.querySelector('.alert-fixed');
    if(alert) alert.style.display = 'none';
}, 5000);
</script>
<?php endif; ?>

<div class="main-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock" style="color: #667eea;"></i> Manage Doctor Timing</h2>
            <div>
                <a href="manage-timing.php" class="btn btn-primary me-2">
                    <i class="fas fa-sync"></i> Refresh
                </a>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Select Doctor -->
        <div class="card-box">
            <form method="GET" action="manage-timing.php">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-user-md" style="color: #667eea;"></i> Select Doctor
                        </label>
                        <select name="doc_id" class="form-control form-control-lg" required>
                            <option value="">-- Select a Doctor --</option>
                            <?php while($doc = mysqli_fetch_assoc($doctors)): ?>
                                <option value="<?php echo $doc['doc_id']; ?>" <?php echo ($selected_doctor == $doc['doc_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($doc['doc_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search"></i> Load Timing
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Timing Form -->
        <?php if($selected_doctor > 0): ?>
        <div class="card-box">
            <h5 class="mb-3">
                <i class="fas fa-edit" style="color: #667eea;"></i> 
                Edit Timing for: <span style="color: #667eea;"><?php echo htmlspecialchars($doctor_name); ?></span>
                <span class="badge bg-primary ms-2">ID: <?php echo $selected_doctor; ?></span>
            </h5>
            
            <form method="POST" action="manage-timing.php">
                <input type="hidden" name="doc_id" value="<?php echo $selected_doctor; ?>">
                <input type="hidden" name="save_timing" value="1">
                
                <div class="table-responsive">
                    <table class="table table-bordered timing-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 10%;">Day</th>
                                <th colspan="3" class="clinic-header">Clinic Time</th>
                                <th colspan="3" class="hospital-header">Hospital Time</th>
                            </tr>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($days as $day): 
                                $timing = isset($timing_data[$day]) ? $timing_data[$day] : null;
                                $clinic_from = $timing ? $timing['clinic_from'] : '';
                                $clinic_to = $timing ? $timing['clinic_to'] : '';
                                $clinic_closed = $timing ? $timing['clinic_closed'] : 0;
                                $hospital_from = $timing ? $timing['hospital_from'] : '';
                                $hospital_to = $timing ? $timing['hospital_to'] : '';
                                $hospital_closed = $timing ? $timing['hospital_closed'] : 0;
                            ?>
                            <tr>
                                <td class="day-name"><?php echo $day; ?></td>
                                
                                <td>
                                    <input type="text" name="clinic_from_<?php echo $day; ?>" 
                                           class="time-input clinic_from_<?php echo $day; ?>"
                                           value="<?php echo htmlspecialchars($clinic_from); ?>"
                                           placeholder="9:00 AM"
                                           id="clinic_from_<?php echo $day; ?>"
                                           <?php echo $clinic_closed ? 'disabled' : ''; ?>>
                                </td>
                                <td>
                                    <input type="text" name="clinic_to_<?php echo $day; ?>" 
                                           class="time-input clinic_to_<?php echo $day; ?>"
                                           value="<?php echo htmlspecialchars($clinic_to); ?>"
                                           placeholder="5:00 PM"
                                           id="clinic_to_<?php echo $day; ?>"
                                           <?php echo $clinic_closed ? 'disabled' : ''; ?>>
                                </td>
                                <td>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="clinic_closed_<?php echo $day; ?>" 
                                                   value="1" 
                                                   <?php echo $clinic_closed ? 'checked' : ''; ?>
                                                   onchange="toggleClinic('<?php echo $day; ?>')">
                                            <span class="status-badge <?php echo $clinic_closed ? 'closed' : 'open'; ?>" id="clinic_status_<?php echo $day; ?>">
                                                <?php echo $clinic_closed ? 'Closed' : 'Open'; ?>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                
                                <td>
                                    <input type="text" name="hospital_from_<?php echo $day; ?>" 
                                           class="time-input hospital_from_<?php echo $day; ?>"
                                           value="<?php echo htmlspecialchars($hospital_from); ?>"
                                           placeholder="8:00 AM"
                                           id="hospital_from_<?php echo $day; ?>"
                                           <?php echo $hospital_closed ? 'disabled' : ''; ?>>
                                </td>
                                <td>
                                    <input type="text" name="hospital_to_<?php echo $day; ?>" 
                                           class="time-input hospital_to_<?php echo $day; ?>"
                                           value="<?php echo htmlspecialchars($hospital_to); ?>"
                                           placeholder="6:00 PM"
                                           id="hospital_to_<?php echo $day; ?>"
                                           <?php echo $hospital_closed ? 'disabled' : ''; ?>>
                                </td>
                                <td>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="hospital_closed_<?php echo $day; ?>" 
                                                   value="1" 
                                                   <?php echo $hospital_closed ? 'checked' : ''; ?>
                                                   onchange="toggleHospital('<?php echo $day; ?>')">
                                            <span class="status-badge <?php echo $hospital_closed ? 'closed' : 'open'; ?>" id="hospital_status_<?php echo $day; ?>">
                                                <?php echo $hospital_closed ? 'Closed' : 'Open'; ?>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-save btn-lg">
                        <i class="fas fa-save me-2"></i> Update Timing
                    </button>
                    <a href="manage-timing.php" class="btn btn-secondary btn-lg ms-2">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="card-box text-center py-5">
            <i class="fas fa-info-circle" style="font-size: 60px; color: #667eea;"></i>
            <h3 class="mt-3">Select a Doctor</h3>
            <p class="text-muted">Please select a doctor from the dropdown above to manage their timing</p>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<script>
// Toggle Clinic Time
function toggleClinic(day) {
    var checkbox = document.querySelector('input[name="clinic_closed_' + day + '"]');
    var fromInput = document.getElementById('clinic_from_' + day);
    var toInput = document.getElementById('clinic_to_' + day);
    var status = document.getElementById('clinic_status_' + day);
    
    if(checkbox.checked) {
        fromInput.disabled = true;
        fromInput.style.background = '#f5f5f5';
        toInput.disabled = true;
        toInput.style.background = '#f5f5f5';
        status.className = 'status-badge closed';
        status.textContent = 'Closed';
    } else {
        fromInput.disabled = false;
        fromInput.style.background = 'white';
        toInput.disabled = false;
        toInput.style.background = 'white';
        status.className = 'status-badge open';
        status.textContent = 'Open';
    }
}

// Toggle Hospital Time
function toggleHospital(day) {
    var checkbox = document.querySelector('input[name="hospital_closed_' + day + '"]');
    var fromInput = document.getElementById('hospital_from_' + day);
    var toInput = document.getElementById('hospital_to_' + day);
    var status = document.getElementById('hospital_status_' + day);
    
    if(checkbox.checked) {
        fromInput.disabled = true;
        fromInput.style.background = '#f5f5f5';
        toInput.disabled = true;
        toInput.style.background = '#f5f5f5';
        status.className = 'status-badge closed';
        status.textContent = 'Closed';
    } else {
        fromInput.disabled = false;
        fromInput.style.background = 'white';
        toInput.disabled = false;
        toInput.style.background = 'white';
        status.className = 'status-badge open';
        status.textContent = 'Open';
    }
}

// Auto-load on select change
document.addEventListener('DOMContentLoaded', function() {
    var select = document.querySelector('select[name="doc_id"]');
    if(select) {
        select.addEventListener('change', function() {
            if(this.value) {
                this.form.submit();
            }
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include('dashboard-footer.php'); ?>