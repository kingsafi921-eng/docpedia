<?php 
include("connect.php"); 
include("header.php");

$hid = isset($_GET['hid']) ? intval($_GET['hid']) : 0;

if($hid == 0) {
    header('Location: doctor.php');
    exit();
}

// Get doctor details
$info = mysqli_query($connect, "SELECT * FROM doctors WHERE doc_id = '$hid'");
$doctor = mysqli_fetch_assoc($info);

if(!$doctor) {
    header('Location: doctor.php');
    exit();
}

$title = $doctor['doc_name'];
?>

<style>
    .doctor-profile {
        padding-top: 120px;
    }
    .doctor-image-container {
        width: 100%;
        height: 450px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
        position: relative;
    }
    .doctor-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
    }
    .profile-image-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        background: #f0f0f0;
    }
    .profile-image-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid rgba(255,255,255,0.3);
        font-size: 60px;
        color: rgba(255,255,255,0.6);
    }
    .info-box {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }
    .info-box h3 {
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 12px;
        margin-bottom: 15px;
    }
    .info-box h3 i {
        color: #667eea;
    }
    .badge-open {
        background: #28a745;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-closed {
        background: #dc3545;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    .action-btn {
        border-radius: 10px;
        padding: 12px;
        font-weight: bold;
        border: none;
        width: 100%;
        margin-bottom: 10px;
        transition: all 0.3s;
        cursor: pointer;
        display: block;
        text-align: center;
        text-decoration: none;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    .btn-book {
        background: #667eea;
        color: white;
    }
    .btn-book:hover {
        background: #5a67d8;
        color: white;
    }
    .btn-call {
        background: #28a745;
        color: white;
    }
    .btn-call:hover {
        background: #218838;
        color: white;
    }
    .btn-save {
        background: #dc3545;
        color: white;
    }
    .btn-save:hover {
        background: #c82333;
        color: white;
    }
    .sidebar-card {
        background: #667eea;
        border-radius: 15px;
        padding: 25px;
        color: white;
        margin-bottom: 25px;
    }
    .sidebar-card h3 {
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 12px;
    }
    .sidebar-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar-card ul li {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
    }
    .sidebar-card ul li:last-child {
        border-bottom: none;
    }
    .rating-stars {
        color: #ffc107;
        font-size: 16px;
    }
    .spec-tag {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        margin: 3px;
    }
    .doctor-name-title {
        font-size: 28px;
        font-weight: 600;
        color: #333;
    }
    .doctor-spec-title {
        color: #667eea;
        font-weight: 500;
        font-size: 16px;
    }
    .timing-day {
        font-weight: normal;
    }
    .timing-time {
        font-weight: normal;
    }
    .closed-text {
        color: #ff6b6b;
        font-weight: bold;
    }
    .doctor-header {
        background: white;
        padding: 20px 25px;
        border-radius: 0 0 15px 15px;
    }
    .doctor-header .row {
        display: flex;
        align-items: center;
    }
    
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
        padding: 5px 0;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 30px;
        color: #e2e8f0;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #f6ad55;
    }
    .star-rating label:hover {
        transform: scale(1.2);
    }
    .star-rating label i {
        transition: all 0.2s ease;
    }
    .review-stars {
        color: #f6ad55;
        font-size: 16px;
        letter-spacing: 1px;
    }
    .review-stars i {
        margin-right: 2px;
    }
    
    .comment-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        border-left: 3px solid #667eea;
    }
    .comment-box img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    .comment-box .review-header {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .comment-box .review-header .reviewer-name {
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }
    .comment-box .review-header .review-date {
        font-size: 12px;
        color: #a0aec0;
        margin-left: auto;
    }
    .comment-box .review-text {
        color: #4a5568;
        margin-top: 8px;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .rating-summary {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 15px 20px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .rating-summary .avg-rating {
        text-align: center;
    }
    .rating-summary .avg-rating .number {
        font-size: 36px;
        font-weight: 700;
        color: #1a1a2e;
    }
    .rating-summary .avg-rating .stars {
        font-size: 16px;
        color: #f6ad55;
    }
    .rating-summary .avg-rating .count {
        font-size: 13px;
        color: #a0aec0;
    }
    .rating-summary .distribution {
        flex: 1;
    }
    .rating-summary .distribution .bar-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 3px;
    }
    .rating-summary .distribution .bar-item .label {
        font-size: 12px;
        color: #4a5568;
        width: 30px;
    }
    .rating-summary .distribution .bar-item .bar-track {
        flex: 1;
        height: 6px;
        background: #edf2f7;
        border-radius: 10px;
        overflow: hidden;
    }
    .rating-summary .distribution .bar-item .bar-track .bar-fill {
        height: 100%;
        background: #f6ad55;
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    .rating-summary .distribution .bar-item .percent {
        font-size: 11px;
        color: #a0aec0;
        width: 35px;
        text-align: right;
    }
    
    @media (max-width: 768px) {
        .doctor-image-container {
            height: 250px;
        }
        .profile-image {
            width: 100px;
            height: 100px;
        }
        .doctor-name-title {
            font-size: 22px;
        }
        .doctor-header .row {
            flex-direction: column;
            text-align: center;
        }
        .doctor-header .text-right {
            text-align: center !important;
            margin-top: 10px;
        }
        .rating-summary {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .comment-box .review-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .comment-box .review-header .review-date {
            margin-left: 0;
        }
    }
</style>

<div class="doctor-profile">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb" style="background: transparent; padding: 0;">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="doctor.php">Doctors</a></li>
                    <li class="active"><?php echo $doctor['doc_name']; ?></li>
                </ol>
            </div>
        </div>

        <!-- Success/Error Message -->
        <?php if(isset($_GET['review']) && $_GET['review'] == 'success'): ?>
            <div style="background: #c6f6d5; color: #276749; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #48bb78;">
                <i class="fas fa-check-circle"></i> Thank you! Your review has been submitted successfully.
            </div>
        <?php elseif(isset($_GET['review']) && $_GET['review'] == 'error'): ?>
            <div style="background: #fed7d7; color: #9b2c2c; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #fc8181;">
                <i class="fas fa-exclamation-circle"></i> Failed to submit review. Please try again.
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Column - Doctor Details -->
            <div class="col-lg-8">
                <!-- Doctor Image with Profile Photo -->
                <div class="info-box" style="padding: 0; overflow: hidden;">
                    <div class="doctor-image-container">
                        <?php 
                        $image_found = false;
                        $image_path = '';
                        
                        if(!empty($doctor['img']) && file_exists("images/uploads/".$doctor['img'])) {
                            $image_found = true;
                            $image_path = "images/uploads/".$doctor['img'];
                        }
                        else if(!empty($doctor['doc_image']) && file_exists($doctor['doc_image'])) {
                            $image_found = true;
                            $image_path = $doctor['doc_image'];
                        }
                        else if(file_exists("images/uploads/doctor_".$doctor['doc_id'].".jpg")) {
                            $image_found = true;
                            $image_path = "images/uploads/doctor_".$doctor['doc_id'].".jpg";
                        }
                        else if(file_exists("uploads/doctors/doctor_".$doctor['doc_id'].".jpg")) {
                            $image_found = true;
                            $image_path = "uploads/doctors/doctor_".$doctor['doc_id'].".jpg";
                        }
                        else {
                            $files = glob("uploads/doctors/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                            foreach($files as $file) {
                                if(strpos($file, (string)$doctor['doc_id']) !== false) {
                                    $image_found = true;
                                    $image_path = $file;
                                    break;
                                }
                            }
                        }
                        
                        if($image_found && !empty($image_path)) {
                            ?>
                            <img src="<?php echo $image_path; ?>" alt="<?php echo $doctor['doc_name']; ?>">
                            <?php
                        } else {
                            ?>
                            <div style="text-align: center; color: rgba(255,255,255,0.8);">
                                <i class="fas fa-user-md" style="font-size: 80px; display: block; margin-bottom: 15px;"></i>
                                <span style="font-size: 18px;"><?php echo $doctor['doc_name']; ?></span>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    
                    <div class="doctor-header">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="profile-image-wrapper" style="float: left; margin-right: 20px;">
                                    <?php if($image_found && !empty($image_path)): ?>
                                        <img src="<?php echo $image_path; ?>" alt="<?php echo $doctor['doc_name']; ?>" class="profile-image">
                                    <?php else: ?>
                                        <div class="profile-image-placeholder">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div style="padding-top: 10px;">
                                    <h2 class="doctor-name-title"><?php echo $doctor['doc_name']; ?></h2>
                                    <p class="doctor-spec-title">
                                        <i class="fas fa-stethoscope"></i> <?php echo isset($doctor['spec']) ? $doctor['spec'] : 'General'; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-5 text-right">
                                <?php
                                // Get average rating
                                $avg_query = mysqli_query($connect, "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM comments WHERE doc_id = '$hid'");
                                if($avg_query) {
                                    $avg_data = mysqli_fetch_assoc($avg_query);
                                    $avg_rating = $avg_data ? round($avg_data['avg_rating'], 1) : 0;
                                    $total_reviews = $avg_data ? $avg_data['total'] : 0;
                                } else {
                                    $avg_rating = 0;
                                    $total_reviews = 0;
                                }
                                ?>
                                <div class="rating-stars">
                                    <?php if($total_reviews > 0): ?>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= floor($avg_rating)): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif($i - 0.5 <= $avg_rating): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                </div>
                                <span style="color: #666;">(<?php echo $avg_rating; ?>/5 - <?php echo $total_reviews; ?> reviews)</span>
                                <br>
                                <span class="badge-open" style="display: inline-block; margin-top: 5px;">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i> Open Now!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Doctor -->
                <div class="info-box">
                    <h3><i class="fas fa-info-circle"></i> About Doctor</h3>
                    <p style="color: #666; line-height: 1.8;">
                        <?php echo !empty($doctor['about']) ? nl2br($doctor['about']) : 'No description available.'; ?>
                    </p>
                </div>

                <!-- Speciality & Address -->
                <div class="info-box">
                    <h3><i class="fas fa-tags"></i> Speciality & Address</h3>
                    <p><strong>Speciality:</strong> <span class="spec-tag"><?php echo isset($doctor['spec']) ? $doctor['spec'] : 'General'; ?></span></p>
                    <p><strong>Address:</strong> <i class="fas fa-map-marker-alt" style="color: #667eea;"></i> <?php echo isset($doctor['hos_address']) ? $doctor['hos_address'] : 'Address not available'; ?></p>
                    <?php if(!empty($doctor['exp'])): ?>
                        <p><strong>Experience:</strong> <?php echo $doctor['exp']; ?> years</p>
                    <?php endif; ?>
                    <?php if(!empty($doctor['fee'])): ?>
                        <p><strong>Consultation Fee:</strong> Rs. <?php echo number_format($doctor['fee'], 2); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Qualification -->
                <?php if(!empty($doctor['qualification'])): ?>
                <div class="info-box">
                    <h3><i class="fas fa-graduation-cap"></i> Qualification</h3>
                    <p style="color: #666;"><?php echo nl2br($doctor['qualification']); ?></p>
                </div>
                <?php endif; ?>

                <!-- Reviews with Stars -->
                <div class="info-box">
                    <h3><i class="fas fa-star" style="color: #ffc107;"></i> Reviews & Rating</h3>
                    
                    <?php
                    // Get all reviews
                    $comments = mysqli_query($connect, "SELECT * FROM comments WHERE doc_id = '$hid' ORDER BY comment_id DESC");
                    $total_comments = $comments ? mysqli_num_rows($comments) : 0;
                    ?>
                    
                    <?php if($total_comments > 0): ?>
                        <!-- Rating Summary -->
                        <div class="rating-summary">
                            <div class="avg-rating">
                                <div class="number"><?php echo $avg_rating; ?></div>
                                <div class="stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= floor($avg_rating)): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif($i - 0.5 <= $avg_rating): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="count"><?php echo $total_comments; ?> reviews</div>
                            </div>
                            
                            <div class="distribution">
                                <?php
                                // Rating distribution
                                $dist_query = mysqli_query($connect, "SELECT rating, COUNT(*) as count FROM comments WHERE doc_id = '$hid' GROUP BY rating ORDER BY rating DESC");
                                if($dist_query) {
                                    while($dist = mysqli_fetch_assoc($dist_query)):
                                        $percent = round(($dist['count'] / $total_comments) * 100);
                                ?>
                                        <div class="bar-item">
                                            <span class="label"><?php echo $dist['rating']; ?>★</span>
                                            <div class="bar-track">
                                                <div class="bar-fill" style="width: <?php echo $percent; ?>%;"></div>
                                            </div>
                                            <span class="percent"><?php echo $percent; ?>%</span>
                                        </div>
                                <?php 
                                    endwhile;
                                }
                                ?>
                            </div>
                        </div>
                        
                        <!-- Individual Reviews -->
                        <?php 
                        if($comments) {
                            mysqli_data_seek($comments, 0);
                            while($comment = mysqli_fetch_assoc($comments)):
                        ?>
                            <div class="comment-box">
                                <div class="review-header">
                                    <img src="assets/img/testimonial-1.png" alt="<?php echo isset($comment['name']) ? $comment['name'] : 'User'; ?>">
                                    <div>
                                        <h6 class="reviewer-name"><?php echo isset($comment['name']) ? $comment['name'] : 'Anonymous'; ?></h6>
                                        <div class="review-stars">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $comment['rating']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <span class="review-date"><?php echo isset($comment['created_at']) ? date('d M Y', strtotime($comment['created_at'])) : date('d M Y'); ?></span>
                                </div>
                                <p class="review-text"><?php echo isset($comment['comment']) ? $comment['comment'] : ''; ?></p>
                            </div>
                        <?php 
                            endwhile;
                        }
                        ?>
                    <?php else: ?>
                        <p style="color: #999; text-align: center; padding: 20px;">No reviews yet. Be the first to review!</p>
                    <?php endif; ?>
                </div>

                <!-- Add Review with Stars -->
                <div class="info-box">
                    <h3><i class="fas fa-pen"></i> Add Review</h3>
                    <form action="comment-action.php" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>
                            <input type="hidden" name="doc" value="<?php echo $doctor['doc_id']; ?>">
                            
                            <div class="form-group col-md-12">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Your Rating</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" checked>
                                    <label for="star5" title="5 Stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 Stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 Stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 Stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 Star"><i class="fas fa-star"></i></label>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>Your Review</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Write your review here..." required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" name="com" class="btn btn-primary" style="background: #667eea; border: none; border-radius: 10px; padding: 12px 30px; width: 100%;">
                                    <i class="fas fa-paper-plane"></i> Submit Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Clinic Time -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-clock"></i> Clinic Time</h3>
                    <ul>
                        <?php
                        // ====== FIXED: Check if doctor_timing table exists ======
                        $table_check = mysqli_query($connect, "SHOW TABLES LIKE 'doctor_timing'");
                        if(mysqli_num_rows($table_check) > 0) {
                            // Table exists, query it
                            $timing_query = mysqli_query($connect, "SELECT * FROM doctor_timing WHERE doc_id = '$hid' ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')");
                            
                            if($timing_query && mysqli_num_rows($timing_query) > 0) {
                                while($timing = mysqli_fetch_assoc($timing_query)) {
                                    $day_name = isset($timing['day_of_week']) ? $timing['day_of_week'] : (isset($timing['day_name']) ? $timing['day_name'] : '');
                                    
                                    if(isset($timing['is_clinic_closed']) && $timing['is_clinic_closed'] == 1) {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="closed-text">Closed</span></li>';
                                    } elseif(!empty($timing['clinic_from']) && !empty($timing['clinic_to'])) {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="timing-time">'.$timing['clinic_from'].' - '.$timing['clinic_to'].'</span></li>';
                                    } else {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="closed-text">Closed</span></li>';
                                    }
                                }
                            } else {
                                // No timing data found, show defaults
                                $default_clinic = [
                                    'Monday' => '5:00 PM - 8:00 PM',
                                    'Tuesday' => '5:00 PM - 8:00 PM',
                                    'Wednesday' => '5:00 PM - 8:00 PM',
                                    'Thursday' => 'Closed',
                                    'Friday' => '5:00 PM - 7:00 PM',
                                    'Saturday' => 'Closed',
                                    'Sunday' => 'Closed'
                                ];
                                foreach($default_clinic as $day => $time) {
                                    if($time == 'Closed') {
                                        echo '<li><span class="timing-day">'.$day.'</span> <span class="closed-text">Closed</span></li>';
                                    } else {
                                        echo '<li><span class="timing-day">'.$day.'</span> <span class="timing-time">'.$time.'</span></li>';
                                    }
                                }
                            }
                        } else {
                            // Table doesn't exist, show default times
                            $default_clinic = [
                                'Monday' => '5:00 PM - 8:00 PM',
                                'Tuesday' => '5:00 PM - 8:00 PM',
                                'Wednesday' => '5:00 PM - 8:00 PM',
                                'Thursday' => 'Closed',
                                'Friday' => '5:00 PM - 7:00 PM',
                                'Saturday' => 'Closed',
                                'Sunday' => 'Closed'
                            ];
                            foreach($default_clinic as $day => $time) {
                                if($time == 'Closed') {
                                    echo '<li><span class="timing-day">'.$day.'</span> <span class="closed-text">Closed</span></li>';
                                } else {
                                    echo '<li><span class="timing-day">'.$day.'</span> <span class="timing-time">'.$time.'</span></li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- Hospital Time -->
                <div class="sidebar-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3><i class="fas fa-hospital"></i> Hospital Time</h3>
                    <ul>
                        <?php
                        // ====== FIXED: Check if doctor_timing table exists ======
                        $table_check = mysqli_query($connect, "SHOW TABLES LIKE 'doctor_timing'");
                        if(mysqli_num_rows($table_check) > 0) {
                            $timing_query = mysqli_query($connect, "SELECT * FROM doctor_timing WHERE doc_id = '$hid' ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')");
                            
                            if($timing_query && mysqli_num_rows($timing_query) > 0) {
                                while($timing = mysqli_fetch_assoc($timing_query)) {
                                    $day_name = isset($timing['day_of_week']) ? $timing['day_of_week'] : (isset($timing['day_name']) ? $timing['day_name'] : '');
                                    
                                    if(isset($timing['is_hospital_closed']) && $timing['is_hospital_closed'] == 1) {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="closed-text">Closed</span></li>';
                                    } elseif(!empty($timing['hospital_from']) && !empty($timing['hospital_to'])) {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="timing-time">'.$timing['hospital_from'].' - '.$timing['hospital_to'].'</span></li>';
                                    } else {
                                        echo '<li><span class="timing-day">'.$day_name.'</span> <span class="closed-text">Closed</span></li>';
                                    }
                                }
                            } else {
                                // Default hospital times
                                $default_hospital = [
                                    'Monday' => '8:00 AM - 4:00 PM',
                                    'Tuesday' => '8:00 AM - 4:00 PM',
                                    'Wednesday' => '8:00 AM - 4:00 PM',
                                    'Thursday' => '8:00 AM - 4:00 PM',
                                    'Friday' => '8:00 AM - 2:00 PM',
                                    'Saturday' => '8:00 AM - 2:00 PM',
                                    'Sunday' => 'Closed'
                                ];
                                foreach($default_hospital as $day => $time) {
                                    if($time == 'Closed') {
                                        echo '<li><span class="timing-day">'.$day.'</span> <span class="closed-text">Closed</span></li>';
                                    } else {
                                        echo '<li><span class="timing-day">'.$day.'</span> <span class="timing-time">'.$time.'</span></li>';
                                    }
                                }
                            }
                        } else {
                            // Table doesn't exist, show defaults
                            $default_hospital = [
                                'Monday' => '8:00 AM - 4:00 PM',
                                'Tuesday' => '8:00 AM - 4:00 PM',
                                'Wednesday' => '8:00 AM - 4:00 PM',
                                'Thursday' => '8:00 AM - 4:00 PM',
                                'Friday' => '8:00 AM - 2:00 PM',
                                'Saturday' => '8:00 AM - 2:00 PM',
                                'Sunday' => 'Closed'
                            ];
                            foreach($default_hospital as $day => $time) {
                                if($time == 'Closed') {
                                    echo '<li><span class="timing-day">'.$day.'</span> <span class="closed-text">Closed</span></li>';
                                } else {
                                    echo '<li><span class="timing-day">'.$day.'</span> <span class="timing-time">'.$time.'</span></li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="info-box">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    <a href="book-appointment.php?doc_id=<?php echo $doctor['doc_id']; ?>" class="action-btn btn-book">
                        <i class="fas fa-calendar-check"></i> Book Appointment
                    </a>
                    <a href="tel:<?php echo isset($doctor['phone']) ? $doctor['phone'] : (isset($doctor['number']) ? $doctor['number'] : '+1234567890'); ?>" class="action-btn btn-call">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                    <button class="action-btn btn-save" onclick="alert('Saved to favorites!')">
                        <i class="fas fa-heart"></i> Save to Favorites
                    </button>
                </div>

                <!-- Location -->
                <div class="info-box">
                    <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                    <p style="color: #666;">
                        <i class="fas fa-map-pin" style="color: #667eea;"></i> 
                        <?php echo isset($doctor['hos_address']) ? $doctor['hos_address'] : 'Address not available'; ?>
                    </p>
                    <div style="background: #f0f0f0; height: 150px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #999;">
                        <i class="fas fa-map" style="font-size: 30px; margin-right: 10px;"></i>
                        Map View (Coming Soon)
                    </div>
                </div>

                <!-- Categories -->
                <div class="row">
                    <div class="col-6 margin-bottom-15px">
                        <a href="doctor.php" class="d-block border-radius-15 hvr-float" style="text-decoration: none;">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center">
                                <i class="fas fa-user-md" style="font-size: 24px;"></i>
                                <div style="font-size: 12px; margin-top: 5px;">Doctors</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 margin-bottom-15px">
                        <a href="laboratories.php" class="d-block border-radius-15 hvr-float" style="text-decoration: none;">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center">
                                <i class="fas fa-flask" style="font-size: 24px;"></i>
                                <div style="font-size: 12px; margin-top: 5px;">Labs</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 margin-bottom-15px">
                        <a href="pharmacies.php" class="d-block border-radius-15 hvr-float" style="text-decoration: none;">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center">
                                <i class="fas fa-hospital" style="font-size: 24px;"></i>
                                <div style="font-size: 12px; margin-top: 5px;">Pharmacies</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 margin-bottom-15px">
                        <a href="clinics.php" class="d-block border-radius-15 hvr-float" style="text-decoration: none;">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center">
                                <i class="fas fa-clinic-medical" style="font-size: 24px;"></i>
                                <div style="font-size: 12px; margin-top: 5px;">Clinics</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>