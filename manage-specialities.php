<?php
// Handle Add Speciality
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

$diseases = mysqli_query($connect, "SELECT * FROM disease ORDER BY disease_id DESC");
?>
<h3><i class="fas fa-stethoscope" style="color: #17a2b8;"></i> Manage Specialities</h3>
<p class="text-muted">Add and manage medical specialities.</p>

<?php if(isset($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show mt-3" role="alert">
        <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mt-3">
    <div class="card-header" style="background: #f8f9fa;">
        <i class="fas fa-plus-circle"></i> Add New Speciality
    </div>
    <div class="card-body">
        <form method="POST" action="?page=specialities">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="speciality_name" class="form-control" placeholder="Speciality Name" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="speciality_description" class="form-control" placeholder="Description (optional)">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_speciality" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Add
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Speciality Name</th>
                <th>Description</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($diseases) > 0): ?>
            <?php while($disease = mysqli_fetch_assoc($diseases)) { ?>
            <tr>
                <td><?php echo $disease['disease_id']; ?></td>
                <td><strong><?php echo $disease['disease_name']; ?></strong></td>
                <td><?php echo $disease['disease_description'] ?? 'N/A'; ?></td>
                <td><?php echo date('d M Y', strtotime($disease['created_at'] ?? 'now')); ?></td>
                <td>
                    <a href="?page=specialities&delete_speciality=<?php echo $disease['disease_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
            <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">
                    <div class="py-3">
                        <i class="fas fa-stethoscope" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-2">No specialities found. Add your first speciality above!</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>