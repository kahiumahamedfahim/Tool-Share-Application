<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/adminRent.css">

<h2>All Rent Requests (Admin)</h2>

<?php if (empty($requests)): ?>
    <p>No rent requests found.</p>
<?php endif; ?>

<?php foreach ($requests as $req): ?>

    <div class="rent-card">

        <!-- Tool Info -->
        <h4>Tool</h4>

        <?php if (!empty($req['tool_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['tool_image']) ?>"
                alt="Tool Image"
                width="140"
            >
        <?php endif; ?>

        <p><strong>Name:</strong> <?= htmlspecialchars($req['tool_name']) ?></p>

        <hr>

        <!-- Owner Info -->
        <h4>Owner</h4>

        <?php if (!empty($req['owner_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['owner_image']) ?>"
                alt="Owner Image"
                width="70"
            >
        <?php endif; ?>

        <p><strong>Name:</strong> <?= htmlspecialchars($req['owner_name']) ?></p>

        <hr>

        <!-- Renter Info -->
        <h4>Renter</h4>

        <?php if (!empty($req['renter_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['renter_image']) ?>"
                alt="Renter Image"
                width="70"
            >
        <?php endif; ?>

        <p><strong>Name:</strong> <?= htmlspecialchars($req['renter_name']) ?></p>

        <hr>

        <!-- Rent Info -->
        <h4>Rent Details</h4>

        <p>
            <strong>Period:</strong>
            <?= htmlspecialchars($req['start_date']) ?>
            →
            <?= htmlspecialchars($req['end_date']) ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?= htmlspecialchars($req['status']) ?>
        </p>

        <p>
            <strong>Requested At:</strong>
            <?= htmlspecialchars($req['created_at']) ?>
        </p>

    </div>

    <hr>

<?php endforeach; ?>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
