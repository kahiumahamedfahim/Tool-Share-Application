<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/header.css">
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/footer.css">
    
    
    <link rel="stylesheet" href="/final/Tool-Share-Application/app/assets/css/profile.css">
</head>
<body>

<div class="profile-container">

    <div class="profile-image">
        <img src="<?= htmlspecialchars(string: $profileImage) ?>"
             width="<?= $imageSize['width'] ?>"
             height="<?= $imageSize['height'] ?>"
             alt="Profile Picture">
    </div>

    <h2>My Profile</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($user['status']) ?></p>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
<!--user 
    <?php if ($user['role'] === 'USER'): ?>
        <hr>
        <h3>User Information</h3>
        <p><strong>NID Number:</strong> <?= htmlspecialchars($user['nid_number'] ?? '-') ?></p>
    <?php endif; ?>

    <!-- =========================
         VENDOR ONLY
         ========================= -->
    <?php if ($user['role'] === 'VENDOR'): ?>
        <hr>
        <h3>Vendor Information</h3>
        <p><strong>Shop Number:</strong> <?= htmlspecialchars($user['shop_number'] ?? '-') ?></p>
        <p><strong>Business Card No:</strong> <?= htmlspecialchars($user['business_card_no'] ?? '-') ?></p>
    <?php endif; ?>

  
    <?php if ($user['role'] === 'ADMIN'): ?>
        <hr>
        <h3>Admin Information</h3>
        <p>You are an administrator.</p>
    <?php endif; ?>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/final/tool-share-application/app/assets/js/header.js"></script>
</body>
</html>
