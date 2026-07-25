<?php
session_start();
include('connect.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$message = '';
$message_type = '';

// Handle form submission
if(isset($_POST['add_speciality'])) {
    $speciality_name = mysqli_real_escape_string($connect, $_POST['speciality_name']);
    $speciality_description = mysqli_real_escape_string($connect, $_POST['speciality_description']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    $insert_query = "INSERT INTO speciality (speciality_name, speciality_description, status) 
                     VALUES ('$speciality_name', '$speciality_description', '$status')";
    
    if(mysqli_query($connect, $insert_query)) {
        $message = "Speciality added successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($connect);
        $message_type = "error";
    }
}

// Delete speciality
if(isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete']);
    $delete_query = "DELETE FROM speciality WHERE speciality_id = '$id'";
    mysqli_query($connect, $delete_query);
    header('Location: add-speciality.php');
    exit();
}

// Fetch all specialities
$specialities_query = "SELECT * FROM speciality ORDER BY speciality_id DESC";
$specialities_result = mysqli_query($connect, $specialities_query);

include('header.php');
?>

<div style="padding: 80px 0; background: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <h2 style="color: #333; margin-bottom: 30px;">
                        <i class="fas fa-plus-circle" style="color: #667eea;"></i> Add New Speciality
                    </h2>
                    
                    <?php if($message): ?>
                        <div style="padding: 15px; border-radius: 5px; margin-bottom: 20px; 
                                    background: <?php echo $message_type == 'success' ? '#d4edda' : '#f8d7da'; ?>; 
                                    color: <?php echo $message_type == 'success' ? '#155724' : '#721c24'; ?>; 
                                    border: 1px solid <?php echo $message_type == 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div style="margin-bottom: 20px;">
                                    <label style="font-weight: bold; color: #333; display: block; margin-bottom: 5px;">
                                        <i class="fas fa-tag" style="color: #667eea;"></i> Speciality Name *
                                    </label>
                                    <input type="text" name="speciality_name" placeholder="e.g., Cardiologist" required 
                                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="margin-bottom: 20px;">
                                    <label style="font-weight: bold; color: #333; display: block; margin-bottom: 5px;">
                                        <i class="fas fa-toggle-on" style="color: #667eea;"></i> Status
                                    </label>
                                    <select name="status" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="margin-bottom: 20px;">
                                    <label style="font-weight: bold; color: #333; display: block; margin-bottom: 5px;">
                                        <i class="fas fa-info-circle" style="color: #667eea;"></i> Description
                                    </label>
                                    <textarea name="speciality_description" rows="3" placeholder="Brief description about this speciality" 
                                              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="add_speciality" 
                                        style="background: #667eea; color: white; padding: 12px 40px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">
                                    <i class="fas fa-save"></i> Add Speciality
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Show all specialities -->
        <div class="row" style="margin-top: 40px;">
            <div class="col-lg-12">
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <h3 style="color: #333; margin-bottom: 20px;">
                        <i class="fas fa-list" style="color: #667eea;"></i> All Specialities
                    </h3>
                    
                    <?php if(mysqli_num_rows($specialities_result) > 0): ?>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">ID</th>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">Name</th>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">Description</th>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">Status</th>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">Created</th>
                                        <th style="background: #667eea; color: white; padding: 12px; text-align: left;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($specialities_result)): ?>
                                        <tr>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?php echo $row['speciality_id']; ?></td>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;"><strong><?php echo htmlspecialchars($row['speciality_name']); ?></strong></td>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars(substr($row['speciality_description'], 0, 50)); ?></td>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                                                <span style="background: <?php echo $row['status'] == 'active' ? '#28a745' : '#dc3545'; ?>; 
                                                             color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                                                <a href="?delete=<?php echo $row['speciality_id']; ?>" 
                                                   onclick="return confirm('Are you sure you want to delete this speciality?')" 
                                                   style="background: #dc3545; color: white; padding: 5px 12px; border: none; border-radius: 3px; text-decoration: none;">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-folder-open" style="font-size: 48px; color: #ddd;"></i>
                            <p style="margin-top: 10px;">No specialities found. Add your first speciality!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>