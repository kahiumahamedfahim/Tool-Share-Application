<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/rent.css">

<h2>Incoming Rent Requests</h2>

<?php if (empty($requests)): ?>
    <p>No incoming rent requests.</p>
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

        <!-- Renter Info -->
        <p><strong>Renter:</strong> <?= htmlspecialchars($req['renter_name']) ?></p>

        <?php if (!empty($req['renter_image'])): ?>
            <img
                src="/tool_sharing_application/public/<?= htmlspecialchars($req['renter_image']) ?>"
                alt="Renter Image"
                width="60"
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
            <strong>Status:</strong> <?= htmlspecialchars($req['status']) ?>
        </p>

        <!-- Actions -->
        <?php if ($req['status'] === 'REQUESTED'): ?>
            <a href="?url=rent/accept&id=<?= htmlspecialchars($req['rent_id']) ?>">
                Accept
            </a>
            |
            <a href="?url=rent/reject&id=<?= htmlspecialchars($req['rent_id']) ?>">
                Reject
            </a>
        <?php endif; ?>
        <!-- Confirm Return (Owner) -->
<?php if ($req['status'] === 'RETURN_REQUESTED'): ?>
    <a href="?url=rent/confirmReturn&id=<?= htmlspecialchars($req['rent_id']) ?>">
        Confirm Return
    </a>
<?php endif; ?>


        <hr>
    </div>

<?php endforeach; ?>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>

<script src="/tool_sharing_application/app/assets/js/header.js"></script>
