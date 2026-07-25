<?php
include('connect.php');

echo "<h2>Checking Admin Credentials</h2>";

// Show all admins
$result = mysqli_query($connect, "SELECT * FROM admin");
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Role</th></tr>";

while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['admin_id'] . "</td>";
    echo "<td>" . $row['admin_name'] . "</td>";
    echo "<td>" . $row['admin_pass'] . "</td>";
    echo "<td>" . ($row['role'] ?? 'N/A') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test login with admin/admin123
echo "<h3>Testing Login:</h3>";
$test_user = 'admin';
$test_pass = 'admin123';

$test_query = "SELECT * FROM admin WHERE admin_name = '$test_user' AND admin_pass = '$test_pass'";
echo "Query: " . $test_query . "<br>";

$test_result = mysqli_query($connect, $test_query);
if(mysqli_num_rows($test_result) == 1) {
    echo "<span style='color:green'>✓ Login successful with admin/admin123!</span>";
} else {
    echo "<span style='color:red'>✗ Login failed with admin/admin123!</span><br>";
    
    // Try to find just the username
    $user_check = mysqli_query($connect, "SELECT * FROM admin WHERE admin_name = 'admin'");
    if(mysqli_num_rows($user_check) > 0) {
        $user_row = mysqli_fetch_assoc($user_check);
        echo "Admin exists with password: " . $user_row['admin_pass'] . "<br>";
        echo "Try logging in with password: " . $user_row['admin_pass'];
    }
}

echo "<h3>Fix: Run this SQL to reset admin password:</h3>";
echo "<pre>UPDATE admin SET admin_pass = 'admin123' WHERE admin_name = 'admin';</pre>";
?>