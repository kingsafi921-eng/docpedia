<?php
include('connect.php');

echo "<h2>Admin Login Debugger</h2>";

// Check if table exists
$table_check = mysqli_query($connect, "SHOW TABLES LIKE 'admin'");
if(mysqli_num_rows($table_check) == 0) {
    echo "❌ Admin table doesn't exist!<br>";
    echo "Creating admin table...<br>";
    
    $create = "CREATE TABLE admin (
        admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        admin_name VARCHAR(100) NOT NULL,
        admin_pass VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'admin'
    )";
    
    if(mysqli_query($connect, $create)) {
        echo "✓ Admin table created!<br>";
    }
}

// Insert fresh admin
mysqli_query($connect, "DELETE FROM admin WHERE admin_name = 'admin'");

$insert = "INSERT INTO admin (admin_name, admin_pass, role) VALUES ('admin', 'admin123', 'admin')";
if(mysqli_query($connect, $insert)) {
    echo "✓ Admin user created!<br>";
} else {
    echo "❌ Error: " . mysqli_error($connect) . "<br>";
}

// Show current admin
$result = mysqli_query($connect, "SELECT * FROM admin");
echo "<h3>Current Admin in Database:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Role</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['admin_id'] . "</td>";
    echo "<td>" . $row['admin_name'] . "</td>";
    echo "<td>" . $row['admin_pass'] . "</td>";
    echo "<td>" . $row['role'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test login
echo "<h3>Testing Login:</h3>";
$test_user = 'admin';
$test_pass = 'admin123';

$test_sql = "SELECT * FROM admin WHERE admin_name = '$test_user' AND admin_pass = '$test_pass'";
$test_result = mysqli_query($connect, $test_sql);

if(mysqli_num_rows($test_result) == 1) {
    echo "<span style='color:green; font-weight:bold;'>✓ SUCCESS! Login will work with:</span><br>";
    echo "Username: admin<br>";
    echo "Password: admin123";
} else {
    echo "<span style='color:red; font-weight:bold;'>✗ Still not working!</span><br>";
    
    // Check what's actually in the database
    $check = mysqli_query($connect, "SELECT * FROM admin WHERE admin_name = 'admin'");
    if($check_row = mysqli_fetch_assoc($check)) {
        echo "Password in DB: '" . $check_row['admin_pass'] . "'<br>";
        echo "Password entered: 'admin123'<br>";
        
        if($check_row['admin_pass'] == 'admin123') {
            echo "Passwords match but query failed. Check for spaces or special characters.";
        } else {
            echo "Passwords don't match. Updating now...";
            mysqli_query($connect, "UPDATE admin SET admin_pass = 'admin123' WHERE admin_name = 'admin'");
            echo " Password updated!";
        }
    }
}

echo "<br><br><a href='admin_login.php'>Go to Admin Login</a>";
?>