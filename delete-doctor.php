<?php
session_start();
include('connect.php');

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    mysqli_query($connect, "DELETE FROM doctors WHERE doc_id = '$id'");
    header('Location: dashboard.php?page=doctors&deleted=1');
} else {
    header('Location: dashboard.php');
}
?>