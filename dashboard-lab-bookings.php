<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: page-login.php');
    exit();
}
include('connect.php');
include('dashboard-header.php');

// Update status
if(isset($_GET['status']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $status = mysqli_real_escape_string($connect, $_GET['status']);
    mysqli_query($connect, "UPDATE lab_bookings SET status = '$status' WHERE booking_id = '$id'");
    header('Location: dashboard-lab-bookings.php?msg=Status updated to ' . ucfirst($status));
    exit();
}

// Delete booking
if(isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete']);
    mysqli_query($connect, "DELETE FROM lab_bookings WHERE booking_id = '$id'");
    header('Location: dashboard-lab-bookings.php?msg=Booking deleted successfully');
    exit();
}

$bookings = mysqli_query($connect, "
    SELECT b.*, l.lab_name, l.address 
    FROM lab_bookings b 
    LEFT JOIN labs l ON b.lab_id = l.lab_id 
    ORDER BY b.created_at DESC
");

$total = mysqli_num_rows($bookings);
$pending = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM lab_bookings WHERE status = 'pending'"));
$confirmed = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM lab_bookings WHERE status = 'confirmed'"));
$completed = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM lab_bookings WHERE status = 'completed'"));
$cancelled = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM lab_bookings WHERE status = 'cancelled'"));

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px; background: #f4f6f9; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-flask" style="color: #667eea;"></i> Lab Test Bookings</h2>
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>

                <?php if($msg): ?>
                    <div class="alert alert-success"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-2"><div class="card text-center"><div class="card-body"><h3><?php echo $total; ?></h3><p>Total</p></div></div></div>
                    <div class="col-md-2"><div class="card text-center bg-warning"><div class="card-body"><h3><?php echo $pending; ?></h3><p>Pending</p></div></div></div>
                    <div class="col-md-2"><div class="card text-center bg-success text-white"><div class="card-body"><h3><?php echo $confirmed; ?></h3><p>Confirmed</p></div></div></div>
                    <div class="col-md-2"><div class="card text-center bg-info text-white"><div class="card-body"><h3><?php echo $completed; ?></h3><p>Completed</p></div></div></div>
                    <div class="col-md-2"><div class="card text-center bg-danger text-white"><div class="card-body"><h3><?php echo $cancelled; ?></h3><p>Cancelled</p></div></div></div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Test</th>
                                        <th>Lab</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($total > 0): ?>
                                    <?php $i=1; while($b = mysqli_fetch_assoc($bookings)): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <strong><?php echo $b['patient_name']; ?></strong><br>
                                            <small><?php echo $b['patient_email']; ?></small>
                                        </td>
                                        <td><?php echo $b['test_name']; ?></td>
                                        <td><?php echo $b['lab_name']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($b['test_date'])); ?></td>
                                        <td><?php echo $b['test_time']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $b['status'] == 'pending' ? 'warning' : ($b['status'] == 'confirmed' ? 'success' : ($b['status'] == 'completed' ? 'info' : 'danger')); ?>">
                                                <?php echo ucfirst($b['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($b['status'] == 'pending'): ?>
                                                <a href="?status=confirmed&id=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-success">Confirm</a>
                                                <a href="?status=cancelled&id=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-danger">Cancel</a>
                                            <?php elseif($b['status'] == 'confirmed'): ?>
                                                <a href="?status=completed&id=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-info">Complete</a>
                                            <?php endif; ?>
                                            <a href="?delete=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr><td colspan="8" class="text-center">No lab bookings found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>