<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css">

<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">

<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/allrentallLog.csa">

<h2>All Rental Logs</h2>

<!-- Search Form -->
<form method="get">
    <input type="hidden" name="url" value="rentalLog/search">

    <input type="text" name="log_id" placeholder="Log ID">

    <input type="date" name="from_date">
    <input type="date" name="to_date">

    <button type="submit">Search</button>
</form>

<br>

<div class="table-actions">
    <a href="?url=rentalLog/downloadAllPdf" class="btn btn-download">
        📄 Download All Rental Logs (PDF)
    </a>
</div>
<?php if (!empty($_GET['log_id']) || !empty($_GET['from_date'])): ?>
    <a href="?url=rentalLog/downloadSearchPdf
        &log_id=<?= urlencode($_GET['log_id'] ?? '') ?>
        &from_date=<?= urlencode($_GET['from_date'] ?? '') ?>
        &to_date=<?= urlencode($_GET['to_date'] ?? '') ?>"
        class="btn btn-secondary">
        Download Search PDF
    </a>
<?php endif; ?>

<br><br>

<?php if (empty($logs)): ?>
    <p>No rental logs found.</p>
<?php else: ?>
<table border="1" width="100%" cellpadding="8">
    <thead>
        <tr>
            <th>Log ID</th>
            <th>Tool</th>
            <th>Owner</th>
            <th>Renter</th>
            <th>Rent Period</th>
            <th>Return Date</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['id']) ?></td>
            <td><?= htmlspecialchars($log['tool_name']) ?></td>
            <td><?= htmlspecialchars($log['owner_name']) ?></td>
            <td><?= htmlspecialchars($log['renter_name']) ?></td>
            <td>
                <?= htmlspecialchars($log['rent_start_date']) ?>
                →
                <?= htmlspecialchars($log['rent_end_date']) ?>
            </td>
            <td><?= htmlspecialchars($log['return_confirmed_date']) ?></td>
            <td><?= htmlspecialchars($log['total_amount']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<script src="/final/tool-share-application/app/assets/js/header.js"></script>
