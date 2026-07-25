<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pharmacy_name = mysqli_real_escape_string($connect, $_POST['pharmacy_name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $website = mysqli_real_escape_string($connect, $_POST['website']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    // Image upload
    $img_name = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $img_name = time() . '_' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img_name);
    }
    
    $query = "INSERT INTO pharmacies (pharmacy_name, email, phone, address, about, website, status, img) 
              VALUES ('$pharmacy_name', '$email', '$phone', '$address', '$about', '$website', '$status', '$img_name')";
    
    if(mysqli_query($connect, $query)) {
        $_SESSION['success'] = "Pharmacy added successfully!";
        header("Location: dashboard-pharmacies.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($connect);
    }
}
?>

<style>
    .add-pharmacy-page {
        margin-top: 100px;
        padding: 30px;
        background: #f4f6f9;
        min-height: 100vh;
    }
    .add-pharmacy-page .form-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .add-pharmacy-page .form-card h4 {
        margin-top: 0;
        color: #1a1a2e;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 15px;
    }
    .add-pharmacy-page .form-group {
        margin-bottom: 18px;
    }
    .add-pharmacy-page .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a1a2e;
        margin-bottom: 5px;
    }
    .add-pharmacy-page .form-group .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        outline: none;
    }
    .add-pharmacy-page .form-group .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    .add-pharmacy-page .form-group textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }
    .add-pharmacy-page .form-group .required {
        color: #e74c3c;
    }
    .add-pharmacy-page .btn-submit {
        background: #2ecc71;
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
    .add-pharmacy-page .btn-submit:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }
    .add-pharmacy-page .btn-cancel {
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
    .add-pharmacy-page .btn-cancel:hover {
        background: #d2d8e0;
    }
    .add-pharmacy-page .alert-box {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .add-pharmacy-page .alert-box.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .add-pharmacy-page .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    @media (max-width: 768px) {
        .add-pharmacy-page .form-card {
            padding: 20px;
        }
        .add-pharmacy-page .form-actions {
            flex-direction: column;
        }
        .add-pharmacy-page .form-actions .btn-submit,
        .add-pharmacy-page .form-actions .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="add-pharmacy-page">
    <div class="container-fluid">
        <div class="form-card">
            <h4><i class="fas fa-plus-circle" style="color:#2ecc71;"></i> Add New Pharmacy</h4>
            
            <?php if($error): ?>
                <div class="alert-box error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pharmacy Name <span class="required">*</span></label>
                            <input type="text" name="pharmacy_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>About</label>
                            <textarea name="about" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control" placeholder="www.example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="img" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-actions">
                            <button type="submit" name="add_pharmacy" class="btn-submit">
                                <i class="fas fa-save"></i> Save Pharmacy
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