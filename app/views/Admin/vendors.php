<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Vendors</title>

    <!-- Vendor page CSS -->
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/vendors.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
</head>
<body>

<div class="page-container">
    <h2>All Vendors</h2>

    <?php if (empty($vendors)): ?>
        <p>No vendors found.</p>
    <?php else: ?>

        <table class="table-vendors">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Shop Number</th>
                    <th>Status</th>
                    <th>Joined</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?= htmlspecialchars($vendor['name']) ?></td>
                        <td><?= htmlspecialchars($vendor['email']) ?></td>
                        <td><?= htmlspecialchars($vendor['phone']) ?></td>
                        <td><?= htmlspecialchars($vendor['shop_number'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($vendor['status']) ?></td>
                        <td><?= htmlspecialchars($vendor['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
</body>
</html>
