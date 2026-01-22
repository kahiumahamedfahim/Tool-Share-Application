<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/header.css">
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/update_profile.css">
</head>
<body>

<div class="profile-container">

    <h2>Update Profile</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div>
            <label>Profile Picture</label><br>
            <input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.avif">
        </div>

        <hr>


        <div>
            <label>Name</label><br>
            <input type="text"
                   name="name"
                   value="<?= htmlspecialchars($user['name']) ?>"
                   required>
        </div>

        <div>
            <label>Phone</label><br>
            <input type="text"
                   name="phone"
                   value="<?= htmlspecialchars($user['phone']) ?>"
                   required>
        </div>

        <div>
            <label>Email (cannot change)</label><br>
            <input type="email"
                   value="<?= htmlspecialchars($user['email']) ?>"
                   disabled>
        </div>

        
        <?php if ($user['role'] === 'USER'): ?>
            <div>
                <label>NID Number</label><br>
                <input type="text"
                       value="<?= htmlspecialchars($user['nid_number']) ?>"
                       disabled>
            </div>
        <?php endif; ?>

    
        <?php if ($user['role'] === 'VENDOR'): ?>
            <div>
                <label>Shop Number</label><br>
                <input type="text"
                       name="shop_number"
                       value="<?= htmlspecialchars($user['shop_number']) ?>"
                       required>
            </div>

            <div>
                <label>Business Card Number</label><br>
                <input type="text"
                       name="business_card_no"
                       value="<?= htmlspecialchars($user['business_card_no']) ?>"
                       required>
            </div>
        <?php endif; ?>

      
        <?php if ($user['role'] === 'ADMIN'): ?>
            <p><em>Admin account (limited editable fields)</em></p>
        <?php endif; ?>

        <div style="margin-top: 15px;">
            <button type="submit">Update Profile</button>
        </div>

    </form>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/final/Tool-Share-Application/app/assets/js/header.js"></script>

</body>
</html>
