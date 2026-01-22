<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<link rel="stylesheet" href="/final/tool-share-application/app/assets/css/rentalLog.css">
 <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css">
 <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">

<h2>My Rental History</h2>

<table class="rent-table">
    <thead>
        <tr>
            <th>Log ID</th>
            <th>Tool</th>
            <th>Owner</th>
            <th>Rent Period</th>
            <th>Return Date</th>
            <th>Amount</th>
            <th>Invoice</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logs)): ?>
            <tr>
                <td colspan="7">No rental history found</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['id']) ?></td>
                <td><?= htmlspecialchars($log['tool_name']) ?></td>
                <td><?= htmlspecialchars($log['owner_name']) ?></td>
                <td>
                    <?= $log['rent_start_date'] ?> → <?= $log['rent_end_date'] ?>
                </td>
                <td><?= $log['return_confirmed_date'] ?></td>
                <td>৳ <?= $log['total_amount'] ?></td>
                <td>
                    <a class="btn-primary"
                       href="?url=rentalLog/downloadOne&id=<?= $log['id'] ?>">
                        Download
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>

<script src="/final/tool-share-application/app/assets/js/header.js"></script>
