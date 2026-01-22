<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/changepassword.css">
</head>
<body>

<div class="change-password-container">

    <h2>Change Password</h2>

    <?php if (!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <div>
            <label>Current Password</label>
            <input type="password" name="old_password" required>
        </div>

        <div>
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div>
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit">Change Password</button>

    </form>

</div>

<script src="/final/tool-share-application/app/assets/js/header.js"></script>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
</body>
</html>
