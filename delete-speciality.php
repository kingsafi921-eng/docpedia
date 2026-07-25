<?php
session_start();
include('connect.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    
    $delete_query = "DELETE FROM speciality WHERE speciality_id = '$id'";
    
    if(mysqli_query($connect, $delete_query)) {
        header('Location: add-speciality.php?message=deleted');
        exit();
    } else {
        echo "Error deleting speciality: " . mysqli_error($connect);
    }
} else {
    header('Location: add-speciality.php');
    exit();
}
?>