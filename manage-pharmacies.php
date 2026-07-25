<?php
$pharmacies = mysqli_query($connect, "SELECT * FROM pharmacies ORDER BY pharmacy_id DESC");
?>
<h3><i class="fas fa-hospital" style="color: #ffc107;"></i> Manage Pharmacies</h3>
<p class="text-muted">View and manage all registered pharmacies.</p>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pharmacy Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($pharmacies) > 0): ?>
            <?php while($pharmacy = mysqli_fetch_assoc($pharmacies)) { ?>
            <tr>
                <td><?php echo $pharmacy['pharmacy_id']; ?></td>
                <td><strong><?php echo $pharmacy['pharmacy_name']; ?></strong></td>
                <td><?php echo $pharmacy['address']; ?></td>
                <td><?php echo $pharmacy['phone']; ?></td>
                <td><?php echo $pharmacy['email']; ?></td>
                <td>
                    <a href="?page=pharmacies&delete_pharmacy=<?php echo $pharmacy['pharmacy_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
            <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">
                    <div class="py-3">
                        <i class="fas fa-hospital" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-2">No pharmacies found. <a href="?page=add-pharmacy">Add a pharmacy</a></p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>