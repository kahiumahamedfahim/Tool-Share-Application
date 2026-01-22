<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css">
<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">
<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/rent.css">


<div class="rent-page">

    <h2 class="page-title">Incoming Rent Requests</h2>

    <?php if (empty($requests)): ?>
        <p class="no-requests">No incoming rent requests.</p>
    <?php endif; ?>

    <div class="rent-grid">

        <?php foreach ($requests as $req): ?>

            <div class="rent-card">

                <!-- Tool Section -->
                <div class="rent-tool">
                    <img
                        src="/final/tool-share-application/public/<?= htmlspecialchars($req['tool_image'] ?? 'uploads/default-tool.png') ?>"
                        alt="Tool Image"
                        class="tool-image"
                        width="150"
    height="150"
    style="
        width:100px;
        height:80px;
        object-fit:cover;
   
        border:1px solid #dee2e6;
        background:#e9ecef;
        flex-shrink:0;
    "
                    >

                    <h3 class="tool-name">
                        <?= htmlspecialchars($req['tool_name']) ?>
                    </h3>
                </div>

                <!-- Renter Section -->
                <div class="rent-user">
                   <img
    src="/final/tool-share-application/public/<?= htmlspecialchars($req['renter_image'] ?? 'uploads/default-user.png') ?>"
    alt="Renter Image"
    width="48"
    height="48"
    style="
        width:80px;
        height:80px;
        object-fit:cover;
        border-radius:50%;
        border:1px solid #dee2e6;
        background:#e9ecef;
        flex-shrink:0;
    "
>

                    <p class="user-name">
                        <strong>Renter:</strong>
                        <?= htmlspecialchars($req['renter_name']) ?>
                    </p>
                </div>

                <!-- Rent Period -->
                <p class="rent-period">
                    <strong>Period:</strong>
                    <?= htmlspecialchars($req['start_date']) ?>
                    →
                    <?= htmlspecialchars($req['end_date']) ?>
                </p>

                <!-- Status -->
                <span class="status-badge <?= strtolower($req['status']) ?>">
                    <?= htmlspecialchars($req['status']) ?>
                </span>
                <br> <br>

                <!-- Actions -->
               <div class="rent-actions">

<?php if (strtoupper($req['status']) === 'REQUESTED'): ?>
    <a class="btn btn-accept"
       href="?url=rent/accept&id=<?= htmlspecialchars($req['rent_id']) ?>">
        Accept
    </a>

    <a class="btn btn-reject"
       href="?url=rent/reject&id=<?= htmlspecialchars($req['rent_id']) ?>">
        Reject
    </a>
<?php endif; ?>

<?php if (strtoupper($req['status']) === 'RETURN_REQUESTED'): ?>
    <a class="btn btn-accept"
       href="?url=rent/confirmReturn&id=<?= htmlspecialchars($req['rent_id']) ?>">
        Confirm Return
    </a>
<?php endif; ?>

</div>


            </div>
            <br><br>
<hr>
        <?php endforeach; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script src="/final/tool-share-application/app/assets/js/header.js"></script>
