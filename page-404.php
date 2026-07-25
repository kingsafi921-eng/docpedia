<?php
$title = "404 - Page Not Found";
include('header.php');
?>

<div class="container" style="padding: 100px 0; text-align: center;">
    <div style="font-size: 100px; color: #667eea;">404</div>
    <h1 style="font-size: 48px; margin: 20px 0;">Page Not Found</h1>
    <p style="font-size: 18px; color: #666; max-width: 500px; margin: 0 auto 30px;">
        Oops! The page you are looking for does not exist or has been moved.
    </p>
    <a href="index.php" class="btn-main" style="
        background: #667eea;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    ">
        <i class="fas fa-home"></i> Back to Home
    </a>
</div>

<?php include('footer.php'); ?>