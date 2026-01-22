<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/login.css">
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/header.css">
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/footer.css">
    
    

</head>
<body>

<div class="container">
    <h2>Login</h2>

    <form action="?url=user/login"
          method="POST">

        <!-- Email or Phone -->
        <label>Email or Phone</label>
        <input type="text" name="login_id" placeholder="Enter email or phone" required>

      <label>Password</label>

<div class="password-wrapper">
    <input type="password"
           id="password"
           name="password"
           placeholder="Enter password"
           required>

    <span class="toggle-password" id="togglePassword">
        👁️
    </span>
</div>

        <!-- Forgot password -->
        <div style="margin-top:10px; font-size:14px;">
            <a href="/tool_sharing_application/public/?url=user/forgotPassword">
                Forgot Password?
            </a>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<script src="/final/Tool-Share-Application/app/assets/js/login.js"></script>
</body>
</html>
