<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

$id = isset($_GET['id']) ? mysqli_real_escape_string($connect, $_GET['id']) : 0;
$query = "SELECT * FROM pharmacies WHERE pharmacy_id = '$id'";
$result = mysqli_query($connect, $query);
$pharmacy = mysqli_fetch_assoc($result);

if(!$pharmacy) {
    $_SESSION['error'] = "Pharmacy not found!";
    header("Location: dashboard-pharmacies.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pharmacy_name = mysqli_real_escape_string($connect, $_POST['pharmacy_name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $website = mysqli_real_escape_string($connect, $_POST['website']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    // Image upload
    $img_name = $pharmacy['img'];
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // Delete old image
        if(!empty($pharmacy['img']) && file_exists($target_dir . $pharmacy['img'])) {
            unlink($target_dir . $pharmacy['img']);
        }
        $img_name = time() . '_' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img_name);
    }
    
    $update_query = "UPDATE pharmacies SET 
                      pharmacy_name = '$pharmacy_name',
                      email = '$email',
                      phone = '$phone',
                      address = '$address',
                      about = '$about',
                      website = '$website',
                      status = '$status',
                      img = '$img_name'
                    WHERE pharmacy_id = '$id'";
    
    if(mysqli_query($connect, $update_query)) {
        $_SESSION['success'] = "Pharmacy updated successfully!";
        header("Location: dashboard-pharmacies.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($connect);
    }
}
?>

<style>
    .edit-pharmacy-page {
        margin-top: 100px;
        padding: 30px;
        background: #f4f6f9;
        min-height: 100vh;
    }
    .edit-pharmacy-page .form-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .edit-pharmacy-page .form-card h4 {
        margin-top: 0;
        color: #1a1a2e;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 15px;
    }
    .edit-pharmacy-page .form-group {
        margin-bottom: 18px;
    }
    .edit-pharmacy-page .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a1a2e;
        margin-bottom: 5px;
    }
    .edit-pharmacy-page .form-group .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        outline: none;
    }
    .edit-pharmacy-page .form-group .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    .edit-pharmacy-page .form-group textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }
    .edit-pharmacy-page .form-group .required {
        color: #e74c3c;
    }
    .edit-pharmacy-page .btn-update {
        background: #3498db;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .edit-pharmacy-page .btn-update:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }
    .edit-pharmacy-page .btn-cancel {
        background: #e8ecf1;
        color: #4a5568;
        padding: 12px 30px;
        border: none;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .edit-pharmacy-page .btn-cancel:hover {
        background: #d2d8e0;
    }
    .edit-pharmacy-page .alert-box {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .edit-pharmacy-page .alert-box.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .edit-pharmacy-page .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .edit-pharmacy-page .current-img {
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
        border: 2px solid #e8ecf1;
        margin-top: 5px;
    }
    @media (max-width: 768px) {
        .edit-pharmacy-page .form-card {
            padding: 20px;
        }
        .edit-pharmacy-page .form-actions {
            flex-direction: column;
        }
        .edit-pharmacy-page .form-actions .btn-update,
        .edit-pharmacy-page .form-actions .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-pharmacy-page">
    <div class="container-fluid">
        <div class="form-card">
            <h4><i class="fas fa-edit" style="color:#3498db;"></i> Edit Pharmacy</h4>
            
            <?php if($error): ?>
                <div class="alert-box error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pharmacy Name <span class="required">*</span></label>
                            <input type="text" name="pharmacy_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($pharmacy['pharmacy_name']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" <?php echo ($pharmacy['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($pharmacy['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($pharmacy['email'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($pharmacy['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" 
                                   value="<?php echo htmlspecialchars($pharmacy['address'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>About</label>
                            <textarea name="about" class="form-control" rows="3"><?php echo htmlspecialchars($pharmacy['about'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control" 
                                   value="<?php echo htmlspecialchars($pharmacy['website'] ?? ''); ?>" placeholder="www.example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="img" class="form-control" accept="image/*">
                            <?php if(!empty($pharmacy['img']) && file_exists("images/uploads/".$pharmacy['img'])): ?>
                                <br>
                                <img src="images/uploads/<?php echo $pharmacy['img']; ?>" class="current-img">
                                <small style="display:block;color:#7f8c8d;font-size:12px;">Current image</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-actions">
                            <button type="submit" class="btn-update">
                                <i class="fas fa-save"></i> Update Pharmacy
                            </button>
                            <a href="dashboard-pharmacies.php" class="btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>