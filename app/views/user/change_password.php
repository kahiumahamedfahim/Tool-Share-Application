<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/changepassword.css">
</head>
<body>

<h2>Change Password</h2>

<?php if (!empty($error)): ?>
    <p>
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form method="POST">

    <div>
        <label>Current Password</label><br>
        <input type="password" name="old_password" required>
    </div>

    <br>

    <div>
        <label>New Password</label><br>
        <input type="password" name="new_password" required>
    </div>

    <br>

    <div>
        <label>Confirm New Password</label><br>
        <input type="password" name="confirm_password" required>
    </div>

    <br>

    <button type="submit">Change Password</button>

</form>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
</body>
</html>
