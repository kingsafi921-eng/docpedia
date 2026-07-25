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

// Handle Add/Edit Doctor
if(isset($_POST['save_doctor'])) {
    $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
    $doc_name = mysqli_real_escape_string($connect, $_POST['doc_name']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $hos_address = mysqli_real_escape_string($connect, $_POST['hos_address']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $experience = mysqli_real_escape_string($connect, $_POST['experience']);
    $qualification = mysqli_real_escape_string($connect, $_POST['qualification']);
    
    // Handle photo upload
    $photo_name = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_name = $_FILES['photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES['photo']['size'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        
        if(in_array($file_ext, $allowed)) {
            if($file_size <= 5000000) {
                $new_name = time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $upload_path = "images/uploads/" . $new_name;
                
                if(!is_dir("images/uploads/")) {
                    mkdir("images/uploads/", 0777, true);
                }
                
                if(move_uploaded_file($file_tmp, $upload_path)) {
                    $photo_name = $new_name;
                    $message = "Photo uploaded successfully!";
                    $message_type = "success";
                } else {
                    $message = "Failed to upload photo.";
                    $message_type = "danger";
                }
            } else {
                $message = "Photo size must be less than 5MB.";
                $message_type = "danger";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF, WEBP files are allowed.";
            $message_type = "danger";
        }
    }
    
    if(empty($message) || $message_type == "success") {
        if($doc_id > 0) {
            // UPDATE
            $sql = "UPDATE doctors SET 
                    doc_name = '$doc_name',
                    spec = '$spec',
                    hos_address = '$hos_address',
                    about = '$about',
                    email = '$email',
                    phone = '$phone',
                    experience = '$experience',
                    qualification = '$qualification'";
            
            if($photo_name) {
                // Get old photo to delete
                $old_query = mysqli_query($connect, "SELECT img FROM doctors WHERE doc_id = '$doc_id'");
                $old_row = mysqli_fetch_assoc($old_query);
                if($old_row['img'] && file_exists("images/uploads/".$old_row['img'])) {
                    unlink("images/uploads/".$old_row['img']);
                }
                $sql .= ", img = '$photo_name'";
            }
            
            $sql .= " WHERE doc_id = '$doc_id'";
            
            if(mysqli_query($connect, $sql)) {
                $message = "Doctor updated successfully!";
                $message_type = "success";
            } else {
                $message = "Error: " . mysqli_error($connect);
                $message_type = "danger";
            }
        } else {
            // INSERT
            $sql = "INSERT INTO doctors 
                    (doc_name, spec, hos_address, about, email, phone, experience, qualification, img) 
                    VALUES 
                    ('$doc_name', '$spec', '$hos_address', '$about', '$email', '$phone', '$experience', '$qualification', '$photo_name')";
            
            if(mysqli_query($connect, $sql)) {
                $message = "Doctor added successfully!";
                $message_type = "success";
            } else {
                $message = "Error: " . mysqli_error($connect);
                $message_type = "danger";
            }
        }
    }
}

// Handle Delete
if(isset($_GET['delete'])) {
    $doc_id = intval($_GET['delete']);
    
    $query = mysqli_query($connect, "SELECT img FROM doctors WHERE doc_id = '$doc_id'");
    $row = mysqli_fetch_assoc($query);
    if($row['img'] && file_exists("images/uploads/".$row['img'])) {
        unlink("images/uploads/".$row['img']);
    }
    
    if(mysqli_query($connect, "DELETE FROM doctors WHERE doc_id = '$doc_id'")) {
        $message = "Doctor deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting doctor.";
        $message_type = "danger";
    }
}

// Get doctor data for edit
$edit_doctor = null;
if(isset($_GET['edit'])) {
    $doc_id = intval($_GET['edit']);
    $result = mysqli_query($connect, "SELECT * FROM doctors WHERE doc_id = '$doc_id'");
    $edit_doctor = mysqli_fetch_assoc($result);
}

// Get all doctors
$doctors = mysqli_query($connect, "SELECT * FROM doctors ORDER BY doc_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-wrapper {
            margin-top: 100px;
            padding: 30px;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .card-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
        }
        .doctor-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        .doctor-photo-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
        }
        .photo-upload-box {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            background: #fafafa;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .photo-upload-box:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        .photo-upload-box i {
            font-size: 50px;
            color: #667eea;
        }
        .photo-upload-box .upload-hint {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .photo-upload-box .upload-text {
            color: #888;
            margin-top: 10px;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea, #5a67d8);
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
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
        .btn-edit {
            background: #ffc107;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            font-size: 13px;
            transition: all 0.3s;
        }
        .btn-edit:hover {
            background: #e0a800;
            color: #333;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            font-size: 13px;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background: #c82333;
            color: white;
        }
        .form-section-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-section-title i {
            color: #667eea;
            margin-right: 10px;
        }
        .current-photo-label {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 15px;
                margin-top: 80px;
            }
            .doctor-photo-large {
                width: 100px;
                height: 100px;
            }
            .photo-upload-box {
                min-height: 150px;
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
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2><i class="fas fa-user-md" style="color: #667eea;"></i> <?php echo $edit_doctor ? 'Edit Doctor' : 'Manage Doctors'; ?></h2>
                <p class="text-muted"><?php echo $edit_doctor ? 'Update doctor information and photo' : 'Add, edit or delete doctors'; ?></p>
            </div>
            <div>
                <a href="manage-doctors.php" class="btn btn-primary me-2">
                    <i class="fas fa-sync"></i> Refresh
                </a>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Add/Edit Doctor Form -->
        <div class="card-box">
            <h5 class="form-section-title">
                <i class="fas fa-<?php echo $edit_doctor ? 'edit' : 'plus'; ?>"></i> 
                <?php echo $edit_doctor ? 'Edit Doctor' : 'Add New Doctor'; ?>
            </h5>
            
            <form method="POST" action="manage-doctors.php" enctype="multipart/form-data">
                <?php if($edit_doctor): ?>
                    <input type="hidden" name="doc_id" value="<?php echo $edit_doctor['doc_id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <!-- LEFT: Photo Upload Section -->
                    <div class="col-md-4">
                        <div class="text-center">
                            <label class="fw-bold mb-2">Doctor Photo</label>
                            
                            <!-- Photo Upload Box -->
                            <div class="photo-upload-box" onclick="document.getElementById('photoInput').click()">
                                <?php if($edit_doctor && !empty($edit_doctor['img']) && file_exists("images/uploads/".$edit_doctor['img'])): ?>
                                    <img src="images/uploads/<?php echo $edit_doctor['img']; ?>" class="doctor-photo-large" id="photoPreview">
                                    <div class="upload-hint mt-2">
                                        <i class="fas fa-camera"></i> Click to change photo
                                    </div>
                                <?php else: ?>
                                    <i class="fas fa-user-md"></i>
                                    <div class="upload-text">Click to upload photo</div>
                                    <div class="upload-hint">JPG, PNG, GIF, WEBP (Max 5MB)</div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="photo" id="photoInput" accept="image/*" style="display: none;" onchange="previewPhoto(this)">
                            <div id="photoName" class="text-muted mt-2" style="font-size: 12px;"></div>
                            
                            <?php if($edit_doctor && !empty($edit_doctor['img'])): ?>
                                <div class="current-photo-label">
                                    <i class="fas fa-info-circle"></i> Current: <?php echo $edit_doctor['img']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!$edit_doctor): ?>
                                <div class="current-photo-label">
                                    <i class="fas fa-info-circle"></i> Upload a photo for the doctor
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- RIGHT: Doctor Details -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Doctor Name *</label>
                                <input type="text" name="doc_name" class="form-control" required 
                                       placeholder="Enter doctor name"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['doc_name']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Specialization *</label>
                                <input type="text" name="spec" class="form-control" required 
                                       placeholder="e.g., Cardiology"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['spec']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="e.g., 0310-1111111"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['phone']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Experience (Years)</label>
                                <input type="number" name="experience" class="form-control" 
                                       placeholder="e.g., 8"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['experience']) : ''; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold">Hospital Address</label>
                                <input type="text" name="hos_address" class="form-control" 
                                       placeholder="e.g., Diabetes & Hormone Center, Lahore"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['hos_address']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="doctor@example.com"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['email']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Speciality ID</label>
                                <input type="text" name="qualification" class="form-control" 
                                       placeholder="e.g., Psychiatry"
                                       value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['qualification']) : ''; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold">About</label>
                                <textarea name="about" class="form-control" rows="3" 
                                          placeholder="Write about the doctor"><?php echo $edit_doctor ? htmlspecialchars($edit_doctor['about']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="text-center mt-4">
                    <button type="submit" name="save_doctor" class="btn btn-save">
                        <i class="fas fa-save me-2"></i> <?php echo $edit_doctor ? 'Update Doctor' : 'Add Doctor'; ?>
                    </button>
                    <?php if($edit_doctor): ?>
                        <a href="manage-doctors.php" class="btn btn-secondary ms-2" style="border-radius:10px;padding:12px 40px;font-weight:600;">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Doctors List -->
        <?php if(!$edit_doctor): ?>
        <div class="card-box">
            <h5 class="mb-3">
                <i class="fas fa-list" style="color: #667eea;"></i> All Doctors
                <span class="badge bg-primary ms-2"><?php echo mysqli_num_rows($doctors); ?></span>
            </h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Hospital</th>
                            <th>Experience</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($doctors) > 0): ?>
                        <?php while($doctor = mysqli_fetch_assoc($doctors)): ?>
                        <tr>
                            <td><?php echo $doctor['doc_id']; ?></td>
                            <td>
                                <?php if(!empty($doctor['img']) && file_exists("images/uploads/".$doctor['img'])): ?>
                                    <img src="images/uploads/<?php echo $doctor['img']; ?>" class="doctor-photo" alt="Doctor">
                                <?php else: ?>
                                    <img src="assets/img/default-avatar.png" class="doctor-photo" alt="Default">
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($doctor['doc_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($doctor['spec']); ?></td>
                            <td><?php echo htmlspecialchars($doctor['hos_address']); ?></td>
                            <td><?php echo $doctor['experience'] ? $doctor['experience'] . ' yrs' : 'N/A'; ?></td>
                            <td>
                                <a href="manage-doctors.php?edit=<?php echo $doctor['doc_id']; ?>" class="btn btn-edit btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="manage-doctors.php?delete=<?php echo $doctor['doc_id']; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-user-md" style="font-size: 40px; color: #ddd;"></i>
                                <p class="mt-2">No doctors found. Add your first doctor!</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<script>
// Photo preview
function previewPhoto(input) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('photoPreview');
            if(!preview) {
                // Create preview if doesn't exist
                var box = document.querySelector('.photo-upload-box');
                box.innerHTML = '';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'doctor-photo-large';
                img.id = 'photoPreview';
                box.appendChild(img);
                var hint = document.createElement('div');
                hint.className = 'upload-hint mt-2';
                hint.innerHTML = '<i class="fas fa-camera"></i> Click to change photo';
                box.appendChild(hint);
            } else {
                preview.src = e.target.result;
            }
            document.getElementById('photoName').textContent = '📷 ' + input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include('dashboard-footer.php'); ?>