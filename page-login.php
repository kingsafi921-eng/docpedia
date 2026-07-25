<form action="login-act.php" method="post" enctype="multipart/form-data">
    <div class="form-group label-floating">
        <label class="control-label">Your Email or Username</label>
        <input class="form-control" placeholder="" type="text" name="uname" required>
    </div>
    <div class="form-group label-floating">
        <label class="control-label">Your Password</label>
        <input class="form-control" placeholder="" type="password" name="password" required>
    </div>
    <input type="submit" class="btn btn-md btn-primary full-width" name="login" value="Login">
</form>