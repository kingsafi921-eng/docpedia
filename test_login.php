<?php
include('connect.php');

echo "<h2>Testing Database Connection and Tables</h2>";

// Check users table
$check_users = mysqli_query($connect, "SHOW TABLES LIKE 'users'");
if(mysqli_num_rows($check_users) > 0) {
    echo "✓ Users table exists<br>";
    
    // Show users table structure
    $columns = mysqli_query($connect, "DESCRIBE users");
    echo "<h3>Users table columns:</h3>";
    echo "<ul>";
    while($col = mysqli_fetch_assoc($columns)) {
        echo "<li>" . $col['Field'] . " - " . $col['Type'] . "</li>";
    }
    echo "</ul>";
    
    // Show users in table
    $users = mysqli_query($connect, "SELECT * FROM users");
    echo "<h3>Current users in database:</h3>";
    if(mysqli_num_rows($users) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>user_id</th><th>username</th><th>email</th><th>password</th></tr>";
        while($user = mysqli_fetch_assoc($users)) {
            echo "<tr>";
            echo "<td>" . $user['user_id'] . "</td>";
            echo "<td>" . ($user['username'] ?? 'N/A') . "</td>";
            echo "<td>" . ($user['email'] ?? 'N/A') . "</td>";
            echo "<td>" . ($user['password'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No users found!<br>";
    }
} else {
    echo "✗ Users table does NOT exist!<br>";
}

// Check admin table
$check_admin = mysqli_query($connect, "SHOW TABLES LIKE 'admin'");
if(mysqli_num_rows($check_admin) > 0) {
    echo "<br>✓ Admin table exists<br>";
    $admin_cols = mysqli_query($connect, "DESCRIBE admin");
    echo "<h3>Admin table columns:</h3>";
    echo "<ul>";
    while($col = mysqli_fetch_assoc($admin_cols)) {
        echo "<li>" . $col['Field'] . " - " . $col['Type'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<br>✗ Admin table does NOT exist<br>";
}
?>