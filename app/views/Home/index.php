<?php
require_once __DIR__ . '/../layouts/header.php';
?>

 <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css"> 
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/toolhomeindex.css">

<h2>Tools</h2>

<?php if (empty($tools)): ?>
    <p class="no-tools">No tools available right now.</p>
<?php else: ?>

<div class="tools-grid">

<?php foreach ($tools as $tool): ?>

    <div class="tool-card">

        <!-- Image -->
        <?php if (!empty($tool['image'])): ?>
            <img
                src="/final/Tool-Share-Application/public/<?= htmlspecialchars($tool['image']) ?>"
                alt="Tool Image"
            >
        <?php else: ?>
            <img
                src="/final/Tool-Share-Application/public/uploads/no-image.png"
                alt="No Image"
            >
        <?php endif; ?>

        <!-- Content -->
        <div class="tool-body">
            <h3><?= htmlspecialchars($tool['name']) ?></h3>

            <div class="price">
                ৳ <?= htmlspecialchars($tool['price_per_day']) ?> / day
            </div>

            <p class="description">
                <?= htmlspecialchars(
                    mb_strlen($tool['description']) > 80
                        ? mb_substr($tool['description'], 0, 80) . '...'
                        : $tool['description']
                ) ?>
            </p>

            <div class="location">
                📍 <?= htmlspecialchars($tool['location']) ?>
            </div>

            <div class="owner">
                👤 <?= htmlspecialchars($tool['owner_name']) ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="tool-footer">
            <a href="?url=tool/details&id=<?= htmlspecialchars($tool['id']) ?>">
                View Details
            </a>
        </div>

    </div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>

<script src="/final/tool-share-application/app/assets/js/header.js"></script>
