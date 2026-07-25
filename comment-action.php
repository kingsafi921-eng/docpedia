<?php
// No session needed here
include("connect.php");

if(isset($_POST['com'])) {
    // Get and sanitize inputs
    $name = mysqli_real_escape_string($connect, trim($_POST['name']));
    $email = mysqli_real_escape_string($connect, trim($_POST['email']));
    $doc_id = intval($_POST['doc']);
    $comment = mysqli_real_escape_string($connect, trim($_POST['comment']));
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    
    // Validate inputs
    if(empty($name) || empty($email) || empty($comment) || $doc_id == 0) {
        header('Location: single.php?hid='.$doc_id.'&review=error');
        exit();
    }
    
    // Validate rating
    if($rating < 1) $rating = 1;
    if($rating > 5) $rating = 5;
    
    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: single.php?hid='.$doc_id.'&review=error');
        exit();
    }
    
    // Check if rating column exists
    $check_column = mysqli_query($connect, "SHOW COLUMNS FROM comments LIKE 'rating'");
    if(mysqli_num_rows($check_column) == 0) {
        mysqli_query($connect, "ALTER TABLE comments ADD COLUMN rating INT DEFAULT 5");
    }
    
    // Check if created_at column exists
    $check_column = mysqli_query($connect, "SHOW COLUMNS FROM comments LIKE 'created_at'");
    if(mysqli_num_rows($check_column) == 0) {
        mysqli_query($connect, "ALTER TABLE comments ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    
    // Insert comment
    $query = "INSERT INTO comments (doc_id, name, email, comment, rating, created_at) 
              VALUES ('$doc_id', '$name', '$email', '$comment', '$rating', NOW())";
    
    if(mysqli_query($connect, $query)) {
        header('Location: single.php?hid='.$doc_id.'&review=success');
    } else {
        header('Location: single.php?hid='.$doc_id.'&review=error');
    }
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>