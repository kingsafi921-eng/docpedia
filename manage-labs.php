<?php
$labs = mysqli_query($connect, "SELECT * FROM labs ORDER BY lab_id DESC");
?>
<h3><i class="fas fa-flask" style="color: #28a745;"></i> Manage Labs</h3>
<p class="text-muted">View and manage all registered laboratories.</p>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lab Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($labs) > 0): ?>
            <?php while($lab = mysqli_fetch_assoc($labs)) { ?>
            <tr>
                <td><?php echo $lab['lab_id']; ?></td>
                <td><strong><?php echo $lab['lab_name']; ?></strong></td>
                <td><?php echo $lab['address']; ?></td>
                <td><?php echo $lab['phone']; ?></td>
                <td><?php echo $lab['email']; ?></td>
                <td>
                    <a href="?page=labs&delete_lab=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
            <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">
                    <div class="py-3">
                        <i class="fas fa-flask" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-2">No labs found. <a href="?page=add-lab">Add a lab</a></p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>