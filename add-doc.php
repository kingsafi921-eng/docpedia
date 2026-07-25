<?php 
$title = 'Add Doctor';
// session_start() hata diya - already started in dashboard-header.php
include("connect.php");
include("function.php");

if(!isset($_SESSION['admin_id'])) {
    header('location:page-login.php');
    exit();
}

// Check if user is admin
if(isset($_SESSION['admin_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 1)) {
    $user2 = fetch($connect, $_SESSION['admin_id']);
    $user = fetch($connect, $_SESSION['admin_id']);
} else {
    header("location:index.php");
    exit();
}

// Handle form submission
if(isset($_POST['add'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $category = mysqli_real_escape_string($connect, $_POST['category']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $spec = mysqli_real_escape_string($connect, $_POST['spec']);
    $experience = mysqli_real_escape_string($connect, $_POST['Experience']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $qual = mysqli_real_escape_string($connect, $_POST['qual']);
    $days = mysqli_real_escape_string($connect, $_POST['days']);
    $time_from = mysqli_real_escape_string($connect, $_POST['time_from']);
    $time_to = mysqli_real_escape_string($connect, $_POST['time_to']);
    $time = $time_from . ' - ' . $time_to;
    
    // Handle image upload
    $image_name = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['img']['name']);
        $target_file = $target_dir . $image_name;
        if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            // Image uploaded successfully
        }
    }
    
    $insert = "INSERT INTO doctors (doc_name, category, hos_address, spec, exp, number, email, about, qual, days, time_from, time_to, time, img) 
               VALUES ('$name', '$category', '$address', '$spec', '$experience', '$number', '$email', '$about', '$qual', '$days', '$time_from', '$time_to', '$time', '$image_name')";
    
    if(mysqli_query($connect, $insert)) {
        header('location:add-doc.php?success=1');
    } else {
        header('location:add-doc.php?success=0');
    }
    exit();
}

include('dashboard-header.php');
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px;">
    <div class="container-fluid overflow-hidden">
        <div class="row margin-tb-90px margin-lr-10px sm-mrl-0px">
            <!-- Page Title -->
            <div id="page-title" class="padding-30px background-white full-width">
                <div class="container">
                    <ol class="breadcrumb opacity-5">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li class="active">Add Doctor</li>
                    </ol>
                    <h1 class="font-weight-300">Add Doctor</h1>
                </div>
            </div>
            <!-- // Page Title -->

            <div class="margin-tb-45px full-width">
                <div class="padding-30px background-white border-radius-20 box-shadow">
                    <h3><i class="far fa-list-alt margin-right-10px text-main-color"></i> Basic Informations </h3>
                    
                    <?php if(isset($_GET['success']) && $_GET['success'] == 0) { ?>
                        <div class="alert alert-danger" role="alert">Data Insert Failed!</div>
                    <?php } else if(isset($_GET['success']) && $_GET['success'] == 1) { ?>
                        <div class="alert alert-success" role="alert">Doctor Added Successfully!</div>
                    <?php } ?>
                    
                    <hr>
                    <form action="add-doc.php" method="post" enctype="multipart/form-data">
                        <div class="form-group margin-bottom-20px">
                            <label><i class="far fa-list-alt margin-right-10px"></i> Doctor Name *</label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Doctor Name" required>
                        </div>
                        
                        <div class="form-group margin-bottom-20px">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><i class="far fa-folder-open margin-right-10px"></i> Category</label>
                                    <select class="form-control form-control-sm" name="category">
                                        <option value="Doctors">Doctors</option>
                                        <option value="Clinics">Clinics</option>
                                        <option value="Labs">Labs</option>
                                        <option value="Pharmacies">Pharmacies</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="far fa-list-alt margin-right-10px"></i> Address *</label>
                                    <input type="text" class="form-control form-control-sm" name="address" placeholder="Hospital/Clinic Address" required>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $owner = mysqli_query($connect, 'SELECT disease_id, disease_name FROM disease ORDER BY disease_name');
                        ?>
                        <div class="form-group margin-bottom-20px">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><i class="far fa-flag margin-right-10px"></i> Specialized In *</label>
                                    <select class="form-control form-control-sm" name="spec" required>
                                        <option value=""> Select Speciality</option>
                                        <?php while($row = mysqli_fetch_array($owner)) { ?>
                                            <option value="<?php echo $row['disease_name']; ?>"><?php echo $row['disease_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="far fa-envelope-open margin-right-10px"></i> Experience</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Experience (e.g., 10 years)" name="Experience">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="fas fa-mobile-alt margin-right-10px"></i> Phone</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Phone Number" name="number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="far fa-envelope-open margin-right-10px"></i> Email</label>
                                    <input type="email" class="form-control form-control-sm" placeholder="Email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="fas fa-info margin-right-10px"></i> About</label>
                                    <textarea class="form-control" placeholder="About Doctor" name="about" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="fas fa-info margin-right-10px"></i> Qualification</label>
                                    <textarea class="form-control" placeholder="Qualifications (MBBS, FCPS, etc.)" name="qual" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Days -->
                        <div class="form-group margin-bottom-20px">
                            <label><i class="fas fa-calendar-alt margin-right-10px" style="color: #667eea;"></i> Available Days *</label>
                            <input type="text" class="form-control form-control-sm" name="days" placeholder="e.g., Mon-Fri" required>
                        </div>
                        
                        <!-- Available Time From - To -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="fas fa-clock margin-right-10px" style="color: #667eea;"></i> Available Time From *</label>
                                    <input type="text" class="form-control form-control-sm" name="time_from" placeholder="e.g., 9:00 AM" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group margin-bottom-20px">
                                    <label><i class="fas fa-clock margin-right-10px" style="color: #667eea;"></i> Available Time To *</label>
                                    <input type="text" class="form-control form-control-sm" name="time_to" placeholder="e.g., 5:00 PM" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group margin-bottom-20px">
                            <label><i class="fas fa-image margin-right-10px" style="color: #667eea;"></i> Upload Doctor Image</label>
                            <input type="file" class="form-control" name="img" accept="image/*">
                            <small class="text-muted">Recommended: Square image (e.g., 500x500 pixels)</small>
                        </div>
                        <br>
                        
                        <button type="submit" name="add" class="btn btn-lg btn-primary btn-block border-radius-15 padding-15px box-shadow" style="background: #667eea; border: none;">
                            <i class="fas fa-save"></i> Add Doctor
                        </button>
                        <a href="dashboard.php?page=doctors" class="btn btn-lg btn-secondary border-radius-15 padding-15px">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group label {
        font-weight: 600;
        color: #333;
    }
    .form-control-sm {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #ddd;
    }
    .form-control-sm:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: #667eea;
        border: none;
    }
    .btn-primary:hover {
        background: #5a67d8;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
</style>

<?php include("dashboard-footer.php"); ?>