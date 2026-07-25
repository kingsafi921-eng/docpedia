<?php 
session_start();
include("connect.php");
include("function.php");

if(!isset($_SESSION['admin_id'])) {
    header('location:page-login.php');
    exit();
}

if(!(isset($_SESSION['admin_id']) && $_SESSION['role'] == 1)) {
    header("location:index.php");
    exit();
}

$user2 = fetch($connect, $_SESSION['admin_id']);
$user = fetch($connect, $_SESSION['admin_id']);

// Handle Delete
if(isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($connect, $_GET['delete']);
    $type = mysqli_real_escape_string($connect, $_GET['type']);
    
    if($type == 'doctor') {
        mysqli_query($connect, "DELETE FROM doctors WHERE doc_id = '$id'");
    } elseif($type == 'lab') {
        mysqli_query($connect, "DELETE FROM labs WHERE lab_id = '$id'");
    } elseif($type == 'pharmacy') {
        mysqli_query($connect, "DELETE FROM pharmacies WHERE pharmacy_id = '$id'");
    }
    header('Location: dashboard-add-listings.php');
    exit();
}

// Get all listings
$doctors = mysqli_query($connect, "SELECT doc_id as id, doc_name as name, spec as type, 'doctor' as source, doc_image as image FROM doctors");
$labs = mysqli_query($connect, "SELECT lab_id as id, lab_name as name, 'lab' as type, 'lab' as source, '' as image FROM labs");
$pharmacies = mysqli_query($connect, "SELECT pharmacy_id as id, pharmacy_name as name, 'pharmacy' as type, 'pharmacy' as source, '' as image FROM pharmacies");

$all_listings = [];
while($row = mysqli_fetch_assoc($doctors)) { $all_listings[] = $row; }
while($row = mysqli_fetch_assoc($labs)) { $all_listings[] = $row; }
while($row = mysqli_fetch_assoc($pharmacies)) { $all_listings[] = $row; }

usort($all_listings, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

include('dashboard-header.php');
?>

<div class="content-wrapper" style="margin-top: 100px; padding: 30px; background: #f4f6f9; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-list" style="color: #667eea;"></i> My Listings</h2>
                    <div>
                        <a href="add-listing.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Add New
                        </a>
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: white; border-bottom: 1px solid #eee; border-radius: 15px 15px 0 0; padding: 20px 25px;">
                        <h5 style="margin: 0; color: #333;">
                            <i class="fas fa-list" style="color: #667eea;"></i> All Listings
                            <span class="badge bg-primary" style="float: right;"><?php echo count($all_listings); ?></span>
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if(count($all_listings) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach($all_listings as $listing): ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <?php if(!empty($listing['image']) && file_exists($listing['image'])): ?>
                                                        <img src="<?php echo $listing['image']; ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                                    <?php else: ?>
                                                        <img src="images/uploads/no.png" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo $listing['name']; ?></strong></td>
                                                <td>
                                                    <?php 
                                                    $badge_color = 'primary';
                                                    $icon = 'fa-tag';
                                                    if($listing['source'] == 'doctor') {
                                                        $badge_color = 'primary';
                                                        $icon = 'fa-user-md';
                                                    } elseif($listing['source'] == 'lab') {
                                                        $badge_color = 'success';
                                                        $icon = 'fa-flask';
                                                    } elseif($listing['source'] == 'pharmacy') {
                                                        $badge_color = 'warning';
                                                        $icon = 'fa-hospital';
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?php echo $badge_color; ?>">
                                                        <i class="fas <?php echo $icon; ?>"></i> 
                                                        <?php echo ucfirst($listing['source']); ?>
                                                    </span>
                                                </td>
                                                <td><span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span></td>
                                                <td>
                                                    <?php if($listing['source'] == 'doctor'): ?>
                                                        <a href="?page=edit-doctor&edit_doctor=<?php echo $listing['id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <a href="dashboard-add-listings.php?delete=<?php echo $listing['id']; ?>&type=doctor" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="fas fa-trash"></i></a>
                                                        <a href="single.php?hid=<?php echo $listing['id']; ?>" class="btn btn-sm btn-info" title="View" target="_blank"><i class="fas fa-eye"></i></a>
                                                    <?php elseif($listing['source'] == 'lab'): ?>
                                                        <a href="?page=edit-lab&edit_lab=<?php echo $listing['id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <a href="dashboard-add-listings.php?delete=<?php echo $listing['id']; ?>&type=lab" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="fas fa-trash"></i></a>
                                                        <a href="single-lab.php?hid=<?php echo $listing['id']; ?>" class="btn btn-sm btn-info" title="View" target="_blank"><i class="fas fa-eye"></i></a>
                                                    <?php elseif($listing['source'] == 'pharmacy'): ?>
                                                        <a href="?page=edit-pharmacy&edit_pharmacy=<?php echo $listing['id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <a href="dashboard-add-listings.php?delete=<?php echo $listing['id']; ?>&type=pharmacy" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="fas fa-trash"></i></a>
                                                        <a href="single-pharmacy.php?hid=<?php echo $listing['id']; ?>" class="btn btn-sm btn-info" title="View" target="_blank"><i class="fas fa-eye"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 50px 20px;">
                                <i class="fas fa-folder-open" style="font-size: 64px; color: #ddd;"></i>
                                <h4 style="color: #666; margin-top: 20px;">No Listings Found</h4>
                                <p style="color: #999;">You haven't added any listings yet.</p>
                                <a href="add-listing.php" class="btn btn-primary" style="background: #667eea; border: none; border-radius: 10px; padding: 10px 30px; margin-top: 10px;">
                                    <i class="fas fa-plus-circle"></i> Add Your First Listing
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('dashboard-footer.php'); ?>