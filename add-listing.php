<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'add-listing';
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px; background: #f4f6f9; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Title -->
                <div class="mb-4">
                    <h2>
                        <i class="fas fa-plus-circle" style="color: #667eea;"></i> 
                        <?php 
                        $page_titles = [
                            'add-listing' => 'Add Listing',
                            'add-doctor' => 'Add Doctor',
                            'add-lab' => 'Add Lab',
                            'add-speciality' => 'Add Speciality',
                            'add-pharmacy' => 'Add Pharmacy'
                        ];
                        echo isset($page_titles[$page]) ? $page_titles[$page] : 'Add Listing';
                        ?>
                    </h2>
                </div>

                <?php if($page == 'add-listing'): ?>
                
                <!-- Add Listing Options -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                            <div class="card-header" style="background: white; border-bottom: 1px solid #eee; border-radius: 15px 15px 0 0; padding: 20px 25px;">
                                <h5 style="margin: 0; color: #333;">
                                    <i class="fas fa-list" style="color: #667eea;"></i> Select What You Want to Add
                                </h5>
                            </div>
                            <div class="card-body" style="padding: 30px;">
                                <div class="row">
                                    <!-- Add Doctor -->
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <a href="?page=add-doctor" class="text-decoration-none">
                                            <div class="p-4 text-center" style="background: #f8f9fa; border-radius: 15px; border: 2px solid transparent; transition: all 0.3s;" 
                                                 onmouseover="this.style.background='#e9ecef'; this.style.borderColor='#667eea'; this.style.transform='translateY(-5px)'" 
                                                 onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                                                <i class="fas fa-user-md" style="font-size: 50px; color: #667eea;"></i>
                                                <h5 style="margin-top: 15px; color: #333; font-weight: bold;">Add Doctor</h5>
                                                <p style="color: #666; font-size: 14px;">Add a new doctor to the directory</p>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <!-- Add Lab -->
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <a href="?page=add-lab" class="text-decoration-none">
                                            <div class="p-4 text-center" style="background: #f8f9fa; border-radius: 15px; border: 2px solid transparent; transition: all 0.3s;" 
                                                 onmouseover="this.style.background='#e9ecef'; this.style.borderColor='#28a745'; this.style.transform='translateY(-5px)'" 
                                                 onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                                                <i class="fas fa-flask" style="font-size: 50px; color: #28a745;"></i>
                                                <h5 style="margin-top: 15px; color: #333; font-weight: bold;">Add Lab</h5>
                                                <p style="color: #666; font-size: 14px;">Add a new laboratory to the directory</p>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <!-- Add Speciality -->
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <a href="?page=add-speciality" class="text-decoration-none">
                                            <div class="p-4 text-center" style="background: #f8f9fa; border-radius: 15px; border: 2px solid transparent; transition: all 0.3s;" 
                                                 onmouseover="this.style.background='#e9ecef'; this.style.borderColor='#17a2b8'; this.style.transform='translateY(-5px)'" 
                                                 onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                                                <i class="fas fa-stethoscope" style="font-size: 50px; color: #17a2b8;"></i>
                                                <h5 style="margin-top: 15px; color: #333; font-weight: bold;">Add Speciality</h5>
                                                <p style="color: #666; font-size: 14px;">Add a new medical speciality</p>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <!-- Add Pharmacy -->
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <a href="?page=add-pharmacy" class="text-decoration-none">
                                            <div class="p-4 text-center" style="background: #f8f9fa; border-radius: 15px; border: 2px solid transparent; transition: all 0.3s;" 
                                                 onmouseover="this.style.background='#e9ecef'; this.style.borderColor='#ffc107'; this.style.transform='translateY(-5px)'" 
                                                 onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                                                <i class="fas fa-hospital" style="font-size: 50px; color: #ffc107;"></i>
                                                <h5 style="margin-top: 15px; color: #333; font-weight: bold;">Add Pharmacy</h5>
                                                <p style="color: #666; font-size: 14px;">Add a new pharmacy to the directory</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                
                <!-- Dynamic Forms for Each Option -->
                <?php
                switch($page) {
                    // ============ ADD DOCTOR FORM ============
                    case 'add-doctor':
                        $specialities_list = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_name");
                        
                        // Handle Add Doctor
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
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3><i class="fas fa-user-md" style="color: #667eea;"></i> Add New Doctor</h3>
                            <a href="add-listing.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Options
                            </a>
                        </div>
                        <p class="text-muted">Fill in the details to add a new doctor.</p>
                        
                        <?php if(isset($msg)): ?>
                            <div class="alert alert-<?php echo $msg_type; ?> mt-3"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST" action="?page=add-doctor" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Doctor Name *</label>
                                                <input type="text" name="doc_name" class="form-control" placeholder="Enter doctor name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Specialization *</label>
                                                <select name="spec" class="form-control" required>
                                                    <option value="">Select Specialization</option>
                                                    <?php while($row = mysqli_fetch_assoc($specialities_list)): ?>
                                                        <option value="<?php echo $row['disease_name']; ?>"><?php echo $row['disease_name']; ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Category</label>
                                                <select name="category" class="form-control">
                                                    <option value="Doctors">Doctors</option>
                                                    <option value="Clinics">Clinics</option>
                                                    <option value="Labs">Labs</option>
                                                    <option value="Pharmacies">Pharmacies</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Experience (Years) *</label>
                                                <input type="number" name="exp" class="form-control" placeholder="e.g., 5" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Consultation Fee *</label>
                                                <input type="number" name="fee" class="form-control" placeholder="e.g., 1000" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Hospital/Clinic Address *</label>
                                                <input type="text" name="hos_address" class="form-control" placeholder="Enter hospital address" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Available Days *</label>
                                                <input type="text" name="days" class="form-control" placeholder="e.g., Mon-Fri" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Available Time *</label>
                                                <input type="text" name="time" class="form-control" placeholder="e.g., 9:00 AM - 5:00 PM" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Qualification</label>
                                                <input type="text" name="qualification" class="form-control" placeholder="e.g., MBBS, FCPS">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>About Doctor</label>
                                                <textarea name="about" class="form-control" rows="3" placeholder="Enter doctor description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Doctor Image</label>
                                                <input type="file" name="doc_image" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="add_doctor" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Add Doctor
                                            </button>
                                            <a href="add-listing.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        break;
                    
                    // ============ ADD LAB FORM ============
                    case 'add-lab':
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
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3><i class="fas fa-flask" style="color: #28a745;"></i> Add New Lab</h3>
                            <a href="add-listing.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Options
                            </a>
                        </div>
                        <p class="text-muted">Fill in the details to add a new laboratory.</p>
                        
                        <?php if(isset($msg)): ?>
                            <div class="alert alert-<?php echo $msg_type; ?> mt-3"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST" action="?page=add-lab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Lab Name *</label>
                                                <input type="text" name="lab_name" class="form-control" placeholder="Enter lab name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Enter address">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>About</label>
                                                <textarea name="about" class="form-control" rows="3" placeholder="Enter lab description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Website</label>
                                                <input type="text" name="website" class="form-control" placeholder="Enter website URL">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="add_lab" class="btn btn-success">
                                                <i class="fas fa-save"></i> Add Lab
                                            </button>
                                            <a href="add-listing.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        break;
                    
                    // ============ ADD SPECIALITY FORM ============
                    case 'add-speciality':
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
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3><i class="fas fa-stethoscope" style="color: #17a2b8;"></i> Add New Speciality</h3>
                            <a href="add-listing.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Options
                            </a>
                        </div>
                        <p class="text-muted">Add a new medical speciality.</p>
                        
                        <?php if(isset($msg)): ?>
                            <div class="alert alert-<?php echo $msg_type; ?> mt-3"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST" action="?page=add-speciality">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Speciality Name *</label>
                                                <input type="text" name="speciality_name" class="form-control" placeholder="Enter speciality name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Description</label>
                                                <input type="text" name="speciality_description" class="form-control" placeholder="Description (optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="add_speciality" class="btn btn-info">
                                                <i class="fas fa-save"></i> Add Speciality
                                            </button>
                                            <a href="add-listing.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        break;
                    
                    // ============ ADD PHARMACY FORM ============
                    case 'add-pharmacy':
                        if(isset($_POST['add_pharmacy'])) {
                            $pharmacy_name = mysqli_real_escape_string($connect, $_POST['pharmacy_name']);
                            $email = mysqli_real_escape_string($connect, $_POST['email']);
                            $phone = mysqli_real_escape_string($connect, $_POST['phone']);
                            $address = mysqli_real_escape_string($connect, $_POST['address']);
                            $about = mysqli_real_escape_string($connect, $_POST['about']);
                            $website = mysqli_real_escape_string($connect, $_POST['website']);
                            
                            $insert = "INSERT INTO pharmacies (pharmacy_name, email, phone, address, about, website) 
                                       VALUES ('$pharmacy_name', '$email', '$phone', '$address', '$about', '$website')";
                            
                            if(mysqli_query($connect, $insert)) {
                                $msg = "Pharmacy added successfully!";
                                $msg_type = "success";
                            } else {
                                $msg = "Error: " . mysqli_error($connect);
                                $msg_type = "danger";
                            }
                        }
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3><i class="fas fa-hospital" style="color: #ffc107;"></i> Add New Pharmacy</h3>
                            <a href="add-listing.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Options
                            </a>
                        </div>
                        <p class="text-muted">Fill in the details to add a new pharmacy.</p>
                        
                        <?php if(isset($msg)): ?>
                            <div class="alert alert-<?php echo $msg_type; ?> mt-3"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST" action="?page=add-pharmacy">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Pharmacy Name *</label>
                                                <input type="text" name="pharmacy_name" class="form-control" placeholder="Enter pharmacy name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Enter address">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>About</label>
                                                <textarea name="about" class="form-control" rows="3" placeholder="Enter pharmacy description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Website</label>
                                                <input type="text" name="website" class="form-control" placeholder="Enter website URL">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="add_pharmacy" class="btn btn-warning" style="color: #333;">
                                                <i class="fas fa-save"></i> Add Pharmacy
                                            </button>
                                            <a href="add-listing.php" class="btn btn-secondary">Cancel</a>
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

            </div>
        </div>
    </div>
</div>

<style>
    .content-wrapper {
        background: #f4f6f9;
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .btn-block {
        border-radius: 10px;
        padding: 15px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-block:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .table th {
        background: #f8f9fa;
    }
</style>

<?php include('dashboard-footer.php'); ?>