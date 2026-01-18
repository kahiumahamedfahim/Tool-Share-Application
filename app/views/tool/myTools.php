<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Page specific CSS -->
 <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/myTools.css">

<h2>My Uploaded Tools</h2>

<?php if (empty($tools)): ?>
    <p>You have not uploaded any tools yet.</p>
<?php else: ?>

<div class="tools-grid">

<?php foreach ($tools as $tool): ?>

    <div class="tool-card">

        <!-- Tool Image -->
        <?php if (!empty($tool['image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($tool['image']) ?>"
                alt="Tool Image"
                class="tool-image"
            >
        <?php else: ?>
            <div class="no-image">No Image</div>
        <?php endif; ?>

        <!-- Tool Info -->
        <div class="tool-info">
            <h3><?= htmlspecialchars($tool['name']) ?></h3>

            <p><strong>Price / Day:</strong>
                <?= htmlspecialchars($tool['price_per_day']) ?>
            </p>

            <p><strong>Quantity:</strong>
                <?= htmlspecialchars($tool['quantity']) ?>
            </p>

            <p><strong>Location:</strong>
                <?= htmlspecialchars($tool['location']) ?>
            </p>

            <p><strong>Status:</strong>
                <?= htmlspecialchars($tool['status']) ?>
            </p>

            <?php if ($tool['is_locked']): ?>
                <p class="locked-msg">
                    🔒 This tool is currently rented.
                    Update / Delete disabled until return.
                </p>
            <?php endif; ?>

            <!-- Actions -->
            <div class="actions">
                <?php if (!$tool['is_locked']): ?>
                    <a href="?url=tool/edit&id=<?= htmlspecialchars($tool['id']) ?>">
                        Edit
                    </a>
                    |
                    <a href="?url=tool/delete&id=<?= htmlspecialchars($tool['id']) ?>"
                       onclick="return confirm('Are you sure you want to delete this tool?');">
                        Delete
                    </a>
                <?php else: ?>
                    <span class="disabled-btn">Edit</span> |
                    <span class="disabled-btn">Delete</span>
                <?php endif; ?>
            </div>
        </div>

    </div>

<?php endforeach; ?>

</div> <!-- /.tools-grid -->

<?php endif; ?>

<script src="/tool_sharing_application/app/assets/js/header.js"></script>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
