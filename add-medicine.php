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
        $target_dir = "images/uploads/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $img_name = time() . '_' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img_name);
    }
    
    $query = "INSERT INTO medicines (medicine_name, generic_name, category, manufacturer, price, stock, strength, dosage_form, description, side_effects, precautions, how_to_use, ingredients, status, img) 
              VALUES ('$medicine_name', '$generic_name', '$category', '$manufacturer', '$price', '$stock', '$strength', '$dosage_form', '$description', '$side_effects', '$precautions', '$how_to_use', '$ingredients', '$status', '$img_name')";
    
    if(mysqli_query($connect, $query)) {
        $message = "Medicine added successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($connect);
        $message_type = "danger";
    }
}
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px; background: #f4f6f9; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-plus-circle"></i> Add New Medicine</h4>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Medicine Name *</label>
                                        <input type="text" name="medicine_name" class="form-control" placeholder="Enter medicine name" required style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Generic Name</label>
                                        <input type="text" name="generic_name" class="form-control" placeholder="Enter generic name" style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Category</label>
                                        <select name="category" class="form-control" style="border-radius: 10px; padding: 12px;">
                                            <option value="Pain Relief">Pain Relief</option>
                                            <option value="Antibiotics">Antibiotics</option>
                                            <option value="Blood Pressure">Blood Pressure</option>
                                            <option value="Diabetes">Diabetes</option>
                                            <option value="Gastrointestinal">Gastrointestinal</option>
                                            <option value="Respiratory">Respiratory</option>
                                            <option value="Antihistamine">Antihistamine</option>
                                            <option value="Antidepressant">Antidepressant</option>
                                            <option value="Cholesterol">Cholesterol</option>
                                            <option value="Anxiety">Anxiety</option>
                                            <option value="Vitamin">Vitamin</option>
                                            <option value="Antifungal">Antifungal</option>
                                            <option value="Antiviral">Antiviral</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Manufacturer</label>
                                        <input type="text" name="manufacturer" class="form-control" placeholder="Enter manufacturer name" style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Price (Rs.) *</label>
                                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Stock *</label>
                                        <input type="number" name="stock" class="form-control" placeholder="0" required style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-control" style="border-radius: 10px; padding: 12px;">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Strength</label>
                                        <input type="text" name="strength" class="form-control" placeholder="e.g., 500mg" style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dosage Form</label>
                                        <select name="dosage_form" class="form-control" style="border-radius: 10px; padding: 12px;">
                                            <option value="Tablet">Tablet</option>
                                            <option value="Capsule">Capsule</option>
                                            <option value="Syrup">Syrup</option>
                                            <option value="Injection">Injection</option>
                                            <option value="Inhaler">Inhaler</option>
                                            <option value="Cream">Cream</option>
                                            <option value="Ointment">Ointment</option>
                                            <option value="Drops">Drops</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Enter medicine description" style="border-radius: 10px; padding: 12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Side Effects</label>
                                        <textarea name="side_effects" class="form-control" rows="2" placeholder="Enter side effects" style="border-radius: 10px; padding: 12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Precautions</label>
                                        <textarea name="precautions" class="form-control" rows="2" placeholder="Enter precautions" style="border-radius: 10px; padding: 12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">How to Use</label>
                                        <textarea name="how_to_use" class="form-control" rows="2" placeholder="Enter how to use instructions" style="border-radius: 10px; padding: 12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ingredients</label>
                                        <textarea name="ingredients" class="form-control" rows="2" placeholder="Enter ingredients" style="border-radius: 10px; padding: 12px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Image</label>
                                        <input type="file" name="img" class="form-control" accept="image/*" style="border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="add_medicine" class="btn btn-primary" style="border-radius: 10px; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600; color: #fff;">
                                        <i class="fas fa-save"></i> Add Medicine
                                    </button>
                                    <a href="dashboard-medicines.php" class="btn btn-secondary" style="border-radius: 10px; padding: 12px 30px;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>