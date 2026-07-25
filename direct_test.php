<?php
include('connect.php');

$username = 'admin';
$password = 'admin123';

$query = "SELECT * FROM admin WHERE admin_name = '$username'";
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Admin found in database!<br>";
    echo "Username: " . $row['admin_name'] . "<br>";
    echo "Password in DB: " . $row['admin_pass'] . "<br>";
    echo "Password entered: " . $password . "<br>";
    
    if($password == $row['admin_pass']) {
        echo "<span style='color:green'>✓ PASSWORDS MATCH! Login should work.</span>";
    } else {
        echo "<span style='color:red'>✗ PASSWORDS DO NOT MATCH!</span><br>";
        echo "Run this SQL to fix: UPDATE admin SET admin_pass = 'admin123' WHERE admin_name = 'admin';";
    }
} else {
    echo "Admin user not found!<br>";
    echo "Run: INSERT INTO admin (admin_name, admin_pass) VALUES ('admin', 'admin123');";
}
?>