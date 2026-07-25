<?php
session_start();
include("connect.php");

if(!isset($_SESSION['admin_id'])) {
    header('location:page-login.php');
    exit();
}

if(isset($_POST['add'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $about = mysqli_real_escape_string($connect, $_POST['about']);
    $website = mysqli_real_escape_string($connect, $_POST['website']);
    
    // Check if table exists
    $table_check = "SHOW TABLES LIKE 'labs'";
    $table_result = mysqli_query($connect, $table_check);
    
    if(mysqli_num_rows($table_result) == 0) {
        // Create labs table
        $create_table = "CREATE TABLE IF NOT EXISTS labs (
            lab_id INT AUTO_INCREMENT PRIMARY KEY,
            lab_name VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            phone VARCHAR(50),
            address TEXT,
            about TEXT,
            website VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($connect, $create_table);
    }
    
    $query = "INSERT INTO labs (lab_name, email, phone, address, about, website) 
              VALUES ('$name', '$email', '$number', '$address', '$about', '$website')";
    
    if(mysqli_query($connect, $query)) {
        header('location:add-lab.php?success=1');
    } else {
        header('location:add-lab.php?success=0');
    }
    exit();
} else {
    header('location:add-lab.php');
    exit();
}
?>