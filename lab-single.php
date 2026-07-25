<?php
$title = 'Lab Details - Doctorpedia';
include('header.php');
include('connect.php');

$lab_id = isset($_GET['hid']) ? intval($_GET['hid']) : 0;

if($lab_id > 0) {
    $query = "SELECT * FROM labs WHERE lab_id = '$lab_id'";
    $result = mysqli_query($connect, $query);
    $lab = mysqli_fetch_assoc($result);
}

if(!$lab) {
    header('Location: laboratories.php');
    exit();
}
?>

<style>
    .lab-detail-section {
        padding: 100px 0 50px 0;
        background: #f4f6f9;
        min-height: 100vh;
    }
    .lab-detail-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 40px 45px;
        margin-bottom: 30px;
        max-width: 750px;
        margin-left: auto;
        margin-right: auto;
    }
    .lab-detail-card .lab-icon {
        font-size: 70px;
        color: #667eea;
        text-align: center;
        margin-bottom: 15px;
    }
    .lab-detail-card .lab-icon img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #667eea;
    }
    .lab-detail-card h2 {
        color: #333;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        font-size: 26px;
    }
    .lab-detail-card h2 span {
        font-size: 13px !important;
        display: block;
        color: #28a745;
        margin-top: 5px;
    }
    .detail-row {
        display: flex;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        width: 130px;
        color: #555;
        font-size: 15px;
        flex-shrink: 0;
        padding-top: 2px;
    }
    .detail-label i {
        color: #667eea;
        width: 22px;
        margin-right: 5px;
    }
    .detail-value {
        flex: 1;
        color: #333;
        font-size: 15px;
        word-break: break-word;
    }
    .btn-back {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
        font-size: 14px;
    }
    .btn-back:hover {
        background: #5a67d8;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    .btn-book-test {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 35px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
    }
    .btn-book-test:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(40,167,69,0.4);
        background: linear-gradient(135deg, #218838, #1aa179);
    }
    .btn-book-test i {
        margin-right: 8px;
    }
    .about-text {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        line-height: 1.8;
        color: #555;
        font-size: 14px;
    }
    .lab-status {
        display: inline-block;
        padding: 4px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        background: #d4edda;
        color: #155724;
    }
    .lab-status i {
        color: #28a745;
    }
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        padding-top: 5px;
    }
    .back-wrapper {
        max-width: 750px;
        margin: 0 auto;
        padding: 0 15px;
    }
    .detail-value a {
        color: #667eea;
        text-decoration: none;
    }
    .detail-value a:hover {
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .lab-detail-section {
            padding: 90px 0 40px 0;
        }
        .lab-detail-card {
            padding: 25px 20px;
            margin: 0 10px;
        }
        .detail-row {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px 0;
        }
        .detail-label {
            width: 100%;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .detail-value {
            width: 100%;
            font-size: 14px;
        }
        .lab-detail-card .lab-icon img {
            width: 100px;
            height: 100px;
        }
        .lab-detail-card h2 {
            font-size: 22px;
        }
        .action-buttons {
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .btn-book-test {
            width: 100%;
            text-align: center;
            padding: 14px 20px;
        }
        .btn-back {
            display: block;
            text-align: center;
            margin: 0 10px 20px 10px;
        }
        .back-wrapper {
            padding: 0 10px;
        }
        .lab-detail-card .lab-icon {
            font-size: 50px;
        }
        .about-text {
            font-size: 13px;
            padding: 12px 15px;
        }
    }
    @media (min-width: 769px) and (max-width: 992px) {
        .lab-detail-card {
            padding: 35px 35px;
            max-width: 680px;
        }
        .detail-label {
            width: 120px;
            font-size: 14px;
        }
        .detail-value {
            font-size: 14px;
        }
    }
</style>

<section class="lab-detail-section">
    <div class="container">
        <div class="back-wrapper">
            <a href="laboratories.php" class="btn-back mb-4">
                <i class="fas fa-arrow-left"></i> Back to Laboratories
            </a>
        </div>

        <div class="lab-detail-card">
            <div class="lab-icon">
                <?php if(!empty($lab['img']) && file_exists("images/uploads/".$lab['img'])): ?>
                    <img src="images/uploads/<?php echo $lab['img']; ?>" alt="<?php echo $lab['lab_name']; ?>">
                <?php else: ?>
                    <i class="fas fa-flask"></i>
                <?php endif; ?>
            </div>
            
            <h2>
                <?php echo htmlspecialchars($lab['lab_name']); ?>
                <span>
                    <i class="fas fa-check-circle"></i> Verified Laboratory
                </span>
            </h2>
            
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-envelope"></i> Email</span>
                <span class="detail-value"><?php echo htmlspecialchars($lab['email'] ?? 'N/A'); ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-phone"></i> Phone</span>
                <span class="detail-value">
                    <a href="tel:<?php echo $lab['phone']; ?>">
                        <?php echo htmlspecialchars($lab['phone'] ?? 'N/A'); ?>
                    </a>
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Address</span>
                <span class="detail-value"><?php echo htmlspecialchars($lab['address'] ?? 'N/A'); ?></span>
            </div>
            
            <?php if(!empty($lab['website'])): ?>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-globe"></i> Website</span>
                    <span class="detail-value">
                        <a href="http://<?php echo $lab['website']; ?>" target="_blank">
                            <?php echo htmlspecialchars($lab['website']); ?>
                            <i class="fas fa-external-link-alt" style="font-size: 12px; margin-left: 4px;"></i>
                        </a>
                    </span>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($lab['about'])): ?>
                <div class="detail-row" style="border-bottom: none; display: block; padding-bottom: 5px;">
                    <span class="detail-label" style="width: 100%; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> About</span>
                    <div class="about-text">
                        <?php echo nl2br(htmlspecialchars($lab['about'])); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="detail-row" style="border-bottom: none; display: block; padding-top: 25px; margin-top: 5px;">
                <div class="action-buttons">
                    <a href="book-lab-test.php?lab_id=<?php echo $lab['lab_id']; ?>" class="btn-book-test">
                        <i class="fas fa-calendar-check"></i> Book Lab Test
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>