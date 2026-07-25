<?php
$title = 'Doctors';
include('header.php');
include('connect.php');

// Get all doctors
$doctors = mysqli_query($connect, "SELECT * FROM doctors ORDER BY doc_id DESC");
?>

<style>
    .doctor-list-section {
        padding: 120px 0 50px 0;
        background: #f8f9fa;
    }
    .doctor-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
    }
    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .doctor-card .card-image {
        width: 100%;
        height: 250px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .doctor-card .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
    }
    .doctor-card .card-image .no-image {
        font-size: 50px;
        color: #ccc;
        text-align: center;
    }
    .doctor-card .card-image .no-image i {
        display: block;
        font-size: 60px;
        margin-bottom: 10px;
        color: #667eea;
        opacity: 0.5;
    }
    .doctor-card .card-image .no-image span {
        display: block;
        font-size: 14px;
        color: #999;
    }
    .doctor-card .card-body {
        padding: 20px;
        text-align: center;
    }
    .doctor-card .card-body h4 {
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 18px;
    }
    .doctor-card .card-body .speciality {
        color: #667eea;
        font-weight: 500;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .doctor-card .card-body .details {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .doctor-card .card-body .details i {
        color: #667eea;
        width: 20px;
    }
    .doctor-card .card-body .fee {
        color: #28a745;
        font-weight: bold;
        font-size: 16px;
    }
    .doctor-card .card-body .rating {
        color: #ffc107;
        margin-bottom: 10px;
    }
    .doctor-card .card-body .btn-view {
        background: linear-gradient(135deg, #667eea, #5a67d8);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 25px;
        transition: all 0.3s;
        display: inline-block;
        text-decoration: none;
        font-weight: 600;
    }
    .doctor-card .card-body .btn-view:hover {
        transform: scale(1.05);
        color: white;
        box-shadow: 0 5px 20px rgba(102,126,234,0.3);
    }
    .badge-open {
        background: #28a745;
        color: white;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        display: inline-block;
        font-weight: 600;
    }
    .badge-open i {
        font-size: 8px;
        margin-right: 5px;
    }
    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }
    .section-title h2 {
        font-size: 32px;
        font-weight: 700;
        color: #333;
    }
    .section-title p {
        color: #666;
        font-size: 16px;
    }
    .section-title .underline {
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #667eea, #5a67d8);
        margin: 10px auto;
        border-radius: 10px;
    }
    .doctor-card .card-image .image-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .doctor-card .card-image .image-overlay i {
        margin-right: 5px;
    }
    @media (max-width: 768px) {
        .doctor-card .card-image {
            height: 200px;
        }
        .section-title h2 {
            font-size: 24px;
        }
        .doctor-list-section {
            padding: 100px 0 30px 0;
        }
    }
    @media (max-width: 576px) {
        .doctor-card .card-image {
            height: 180px;
        }
        .doctor-card .card-body h4 {
            font-size: 16px;
        }
    }
</style>

<div class="doctor-list-section">
    <div class="container">
        <!-- Section Title -->
        <div class="section-title">
            <h2><i class="fas fa-user-md" style="color: #667eea;"></i> Our Doctors</h2>
            <div class="underline"></div>
            <p>Meet our team of experienced and qualified medical professionals</p>
        </div>

        <div class="row">
            <?php if(mysqli_num_rows($doctors) > 0): ?>
                <?php while($doctor = mysqli_fetch_assoc($doctors)): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="doctor-card">
                            <!-- Card Image -->
                            <div class="card-image">
                                <?php 
                                $image_found = false;
                                $image_path = '';
                                
                                // Check for image in different locations
                                if(!empty($doctor['img'])) {
                                    // Check in images/uploads/
                                    if(file_exists("images/uploads/".$doctor['img'])) {
                                        $image_found = true;
                                        $image_path = "images/uploads/".$doctor['img'];
                                    }
                                    // Check in uploads/doctors/
                                    else if(file_exists("uploads/doctors/".$doctor['img'])) {
                                        $image_found = true;
                                        $image_path = "uploads/doctors/".$doctor['img'];
                                    }
                                }
                                
                                // Check for doctor_ ID based images
                                if(!$image_found) {
                                    if(file_exists("images/uploads/doctor_".$doctor['doc_id'].".jpg")) {
                                        $image_found = true;
                                        $image_path = "images/uploads/doctor_".$doctor['doc_id'].".jpg";
                                    }
                                    else if(file_exists("uploads/doctors/doctor_".$doctor['doc_id'].".jpg")) {
                                        $image_found = true;
                                        $image_path = "uploads/doctors/doctor_".$doctor['doc_id'].".jpg";
                                    }
                                    else if(file_exists("images/uploads/doctor_".$doctor['doc_id'].".png")) {
                                        $image_found = true;
                                        $image_path = "images/uploads/doctor_".$doctor['doc_id'].".png";
                                    }
                                    else if(file_exists("uploads/doctors/doctor_".$doctor['doc_id'].".png")) {
                                        $image_found = true;
                                        $image_path = "uploads/doctors/doctor_".$doctor['doc_id'].".png";
                                    }
                                }
                                
                                if($image_found) {
                                    ?>
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo $doctor['doc_name']; ?>" loading="lazy">
                                    <div class="image-overlay">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="no-image">
                                        <i class="fas fa-user-md"></i>
                                        <span>No Image</span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            
                            <!-- Card Body -->
                            <div class="card-body">
                                <h4>Dr. <?php echo htmlspecialchars($doctor['doc_name']); ?></h4>
                                <div class="speciality">
                                    <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($doctor['spec']); ?>
                                </div>
                                
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span style="color: #666; font-size: 13px;">(4.5)</span>
                                </div>
                                
                                <div class="details">
                                    <i class="fas fa-hospital"></i> <?php echo substr(htmlspecialchars($doctor['hos_address']), 0, 25); ?>...
                                </div>
                                
                                <?php if(!empty($doctor['exp'])): ?>
                                    <div class="details">
                                        <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($doctor['exp']); ?> years
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($doctor['fee'])): ?>
                                    <div class="fee">
                                        Rs. <?php echo htmlspecialchars($doctor['fee']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="margin-top: 10px;">
                                    <span class="badge-open"><i class="fas fa-circle"></i> Open Now!</span>
                                </div>
                                
                                <div style="margin-top: 15px;">
                                    <a href="single.php?hid=<?php echo $doctor['doc_id']; ?>" class="btn-view">
                                        <i class="fas fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div style="text-align: center; padding: 50px 20px;">
                        <i class="fas fa-user-md" style="font-size: 64px; color: #ddd;"></i>
                        <h4 style="color: #666; margin-top: 20px;">No Doctors Found</h4>
                        <p style="color: #999;">No doctors are currently listed in the directory.</p>
                        <a href="add-doc.php" class="btn btn-primary" style="border-radius: 10px; padding: 10px 30px; background: #667eea; border: none;">
                            <i class="fas fa-plus"></i> Add Doctor
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination (Optional) -->
        <?php if(mysqli_num_rows($doctors) > 12): ?>
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<?php include('footer.php'); ?>