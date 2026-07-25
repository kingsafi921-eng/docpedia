<?php
session_start();
include('connect.php');

// If already logged in
if(isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$debug_output = '';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Start debug output
    $debug_output .= "=== DEBUG INFORMATION ===\n";
    $debug_output .= "Username entered: " . $username . "\n";
    $debug_output .= "Password entered: " . $password . "\n\n";
    
    // Check if admin table exists
    $table_check = "SHOW TABLES LIKE 'admin'";
    $table_result = mysqli_query($connect, $table_check);
    if(mysqli_num_rows($table_result) == 0) {
        $debug_output .= "ERROR: 'admin' table does not exist!\n\n";
        $error = "Admin table not found! Please create the admin table.";
    } else {
        $debug_output .= "Admin table exists.\n\n";
        
        // Check how many admins exist
        $count_query = "SELECT COUNT(*) as total FROM admin";
        $count_result = mysqli_query($connect, $count_query);
        $count_row = mysqli_fetch_assoc($count_result);
        $debug_output .= "Total admins in database: " . $count_row['total'] . "\n\n";
        
        if($count_row['total'] == 0) {
            $debug_output .= "WARNING: No admin users found! Please add an admin.\n";
            $debug_output .= "Run this SQL: INSERT INTO admin (admin_name, admin_pass) VALUES ('admin', 'admin123');\n\n";
            $error = "No admin users found! Please contact administrator.";
        } else {
            // Show all admins in database (for debugging)
            $show_query = "SELECT * FROM admin";
            $show_result = mysqli_query($connect, $show_query);
            $debug_output .= "Admin records in database:\n";
            while($row = mysqli_fetch_assoc($show_result)) {
                $debug_output .= "ID: " . $row['admin_id'] . ", Name: " . $row['admin_name'] . ", Pass: " . $row['admin_pass'] . "\n";
            }
            $debug_output .= "\n";
            
            // Try different query variations
            
            // Variation 1: Exact match
            $sql1 = "SELECT * FROM admin WHERE admin_name = '$username' AND admin_pass = '$password'";
            $debug_output .= "Variation 1 (exact match): " . $sql1 . "\n";
            $result1 = mysqli_query($connect, $sql1);
            if($result1) {
                $debug_output .= "Result: " . mysqli_num_rows($result1) . " rows found\n\n";
                if(mysqli_num_rows($result1) == 1) {
                    $row = mysqli_fetch_assoc($result1);
                    $_SESSION['admin_id'] = $row['admin_id'];
                    $_SESSION['username'] = $row['admin_name'];
                    $_SESSION['role'] = 'admin';
                    
                    $debug_output .= "✓ LOGIN SUCCESSFUL! Redirecting to dashboard...\n";
                    echo "<pre>" . $debug_output . "</pre>";
                    header('Location: dashboard.php');
                    exit();
                }
            }
            
            // Variation 2: MD5 password
            $sql2 = "SELECT * FROM admin WHERE admin_name = '$username' AND admin_pass = MD5('$password')";
            $debug_output .= "Variation 2 (MD5 password): " . $sql2 . "\n";
            $result2 = mysqli_query($connect, $sql2);
            if($result2) {
                $debug_output .= "Result: " . mysqli_num_rows($result2) . " rows found\n\n";
                if(mysqli_num_rows($result2) == 1) {
                    $row = mysqli_fetch_assoc($result2);
                    $_SESSION['admin_id'] = $row['admin_id'];
                    $_SESSION['username'] = $row['admin_name'];
                    $_SESSION['role'] = 'admin';
                    
                    $debug_output .= "✓ LOGIN SUCCESSFUL (MD5)! Redirecting to dashboard...\n";
                    echo "<pre>" . $debug_output . "</pre>";
                    header('Location: dashboard.php');
                    exit();
                }
            }
            
            // Variation 3: Check if username exists first
            $sql3 = "SELECT * FROM admin WHERE admin_name = '$username'";
            $debug_output .= "Variation 3 (check if username exists): " . $sql3 . "\n";
            $result3 = mysqli_query($connect, $sql3);
            if($result3) {
                $debug_output .= "Result: " . mysqli_num_rows($result3) . " rows found\n";
                if(mysqli_num_rows($result3) == 1) {
                    $row = mysqli_fetch_assoc($result3);
                    $debug_output .= "Username exists! Stored password: " . $row['admin_pass'] . "\n";
                    $debug_output .= "Password entered: " . $password . "\n";
                    $debug_output .= "Password match: " . ($password == $row['admin_pass'] ? "YES ✓" : "NO ✗") . "\n\n";
                } else {
                    $debug_output .= "Username not found!\n\n";
                }
            }
            
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            background: #667eea;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 450px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        h2 { 
            text-align: center; 
            margin-bottom: 30px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background: #5a67d8; }
        .error {
            background: #fee;
            color: #c00;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background: #efe;
            color: #0a0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .debug-box {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow: auto;
            max-height: 400px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .debug-box strong {
            color: #569cd6;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .sql-help {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .sql-help code {
            background: #e8ecf1;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 Admin Login</h2>
        
        <?php if($error): ?>
            <div class='error'>❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        
        <div class="sql-help">
            <strong>💡 Quick Fix:</strong><br>
            If you don't have an admin user, run this in phpMyAdmin:
            <br><br>
            <code>INSERT INTO admin (admin_name, admin_pass) VALUES ('admin', 'admin123');</code>
        </div>
        
        <a href="index.php" class="back-link">← Back to Home</a>
        
        <?php if($debug_output): ?>
        <div class="debug-box">
            <strong>🔍 Debug Output:</strong><br>
            <?php echo htmlspecialchars($debug_output); ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>