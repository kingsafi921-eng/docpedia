<?php
session_start();
include('connect.php');

$message = '';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Direct query without escaping for testing
    $sql = "SELECT * FROM admin WHERE admin_name = '$username'";
    $result = mysqli_query($connect, $sql);
    
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        if($password == $row['admin_pass']) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['admin_name'];
            $_SESSION['role'] = 'admin';
            $message = "<span style='color:green'>✓ SUCCESS! Redirecting...</span>";
            echo "<script>setTimeout(function(){ window.location.href = 'dashboard.php'; }, 1000);</script>";
        } else {
            $message = "<span style='color:red'>✗ Password mismatch! Database password: " . $row['admin_pass'] . "</span>";
        }
    } else {
        $message = "<span style='color:red'>✗ Username not found!</span>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Auth</title>
    <style>
        body { font-family: Arial; padding: 50px; }
        .container { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: blue; color: white; border: none; cursor: pointer; }
        .message { margin-bottom: 20px; padding: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Test Admin Authentication</h2>
        <div class="message"><?php echo $message; ?></div>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Test Login</button>
        </form>
    </div>
</body>
</html>