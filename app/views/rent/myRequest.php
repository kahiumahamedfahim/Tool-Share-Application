<?php 
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/myrentRequset.css">
<h2>My Rent Requests</h2>

<?php if (empty($requests)): ?>
    <p>You have not requested any tools yet.</p>
<?php endif; ?>

<?php foreach ($requests as $req): ?>

    <div class="rent-card">

        <!-- Tool Image -->
        <?php if (!empty($req['tool_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['tool_image']) ?>"
                alt="Tool Image"
                width="140"
            >
        <?php endif; ?>

        <p><strong>Tool:</strong> <?= htmlspecialchars($req['tool_name']) ?></p>

        <!-- Owner Info -->
        <p><strong>Owner:</strong> <?= htmlspecialchars($req['owner_name']) ?></p>

        <?php if (!empty($req['owner_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['owner_image']) ?>"
                alt="Owner Image"
                width="80"
            >
        <?php endif; ?>

        <!-- Rent Period -->
        <p>
            <strong>Rent Period:</strong>
            <?= htmlspecialchars($req['start_date']) ?>
            →
            <?= htmlspecialchars($req['end_date']) ?>
        </p>

        <!-- Status -->
        <p>
            <strong>Status:</strong>
            <?= htmlspecialchars($req['status']) ?>
        </p>

        <!-- Cancel option -->
        <?php if ($req['status'] === 'REQUESTED'): ?>
            <a href="?url=rent/cancel&id=<?= htmlspecialchars($req['rent_id']) ?>">
                Cancel Request
            </a>
        <?php endif; ?>
        <!-- Return Request (User) -->
<?php if ($req['status'] === 'ACCEPTED'): ?>
    <a href="?url=rent/requestReturn&id=<?= htmlspecialchars($req['rent_id']) ?>">
        Request Return
    </a>
<?php endif; ?>


        <hr>
    </div>

<?php endforeach; ?>

<?php 
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>