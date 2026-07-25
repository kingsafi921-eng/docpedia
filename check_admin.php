<?php
include('connect.php');

echo "<h2>Checking Admin Table</h2>";

// Check if admin table exists
$table_check = mysqli_query($connect, "SHOW TABLES LIKE 'admin'");
if(mysqli_num_rows($table_check) > 0) {
    echo "✓ Admin table exists<br>";
    
    // Get admin users
    $result = mysqli_query($connect, "SELECT * FROM admin");
    if(mysqli_num_rows($result) > 0) {
        echo "✓ Admin users found:<br>";
        echo "<table border='1' cellpadding='5'>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>Admin ID: " . $row['admin_id'] . "</td>";
            echo "<td>Username: " . $row['admin_name'] . "</td>";
            echo "<td>Password: " . $row['admin_pass'] . "</td>";
            echo "<td>Role: " . ($row['role'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "✗ No admin users found!<br>";
        echo "Run this SQL: INSERT INTO admin (admin_name, admin_pass, role) VALUES ('admin', 'admin123', 'admin');";
    }
} else {
    echo "✗ Admin table does not exist!<br>";
    echo "Run this SQL to create it:<br>";
    echo "<pre>
    CREATE TABLE admin (
        admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        admin_name VARCHAR(100) NOT NULL,
        admin_pass VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'admin'
    );
    INSERT INTO admin (admin_name, admin_pass, role) VALUES ('admin', 'admin123', 'admin');
    </pre>";
}
?>