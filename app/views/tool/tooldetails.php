<?php 
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tool Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/tooldetails.css">
</head>

<body>

<div class="tool-details">

    <h2>Tool Details</h2>

    <!-- Tool Name -->
    <h3><?= htmlspecialchars($tool['name']) ?></h3>

    <hr>

    <!-- Tool Images -->
    <h4>Tool Images</h4>
    <div class="tool-images">
        <?php if (!empty($tool['images'])): ?>
            <?php foreach ($tool['images'] as $img): ?>
                <img
                    src="/tool_sharing_application/public/<?= htmlspecialchars($img) ?>"
                    alt="Tool Image"
                >
            <?php endforeach; ?>
        <?php else: ?>
            <p>No images available</p>
        <?php endif; ?>
    </div>

    <hr>

    <!-- Tool Information -->
    <h4>Tool Information</h4>
    <p><strong>Price per day:</strong> <?= htmlspecialchars($tool['price_per_day']) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($tool['location']) ?></p>
    <p><strong>Quantity:</strong> <?= htmlspecialchars($tool['quantity']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($tool['status']) ?></p>

    <hr>

    <!-- Owner Information -->
    <h4>Owner Information</h4>
    <div class="owner-info">

        <?php if (!empty($tool['owner_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($tool['owner_image']) ?>"
                class="owner-image"
                alt="Owner Image"
            >
        <?php endif; ?>

        <p><strong>Name:</strong> <?= htmlspecialchars($tool['owner_name']) ?></p>

        <?php if (!empty($tool['owner_email'])): ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($tool['owner_email']) ?></p>
        <?php endif; ?>

        <?php if (!empty($tool['owner_phone'])): ?>
            <p><strong>Phone:</strong> <?= htmlspecialchars($tool['owner_phone']) ?></p>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user'])): ?>
            <p><em>Login to see owner contact details</em></p>
        <?php endif; ?>

    </div>

    <hr>

    <!-- Description -->
    <h4>Description</h4>
    <p><?= nl2br(htmlspecialchars($tool['description'])) ?></p>

    <hr>

    <!-- Action -->
    <h4>Action</h4>

    <?php if (!isset($_SESSION['user'])): ?>
        <a href="?url=user/login">Login to Rent</a>

    <?php elseif ($_SESSION['user']['role'] === 'ADMIN'): ?>
        <p>Rent not allowed</p>

    <?php elseif ($tool['quantity'] <= 0): ?>
        <p>Out of stock</p>

    <?php else: ?>
        <a href="?url=rent/request&tool_id=<?= htmlspecialchars($tool['id']) ?>" class="rent-btn">
            Rent Now
        </a>
    <?php endif; ?>

    <hr>

    <a href="?url=user/ViewForAllUser">← Back to tools</a>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>

<script src="/tool_sharing_application/app/assets/js/header.js"></script>

</body>
</html>
