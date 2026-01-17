<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/allusersview.css">
     <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
</head>
<body>

<div class="page-container">
    <h2>All Users</h2>

    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>

        <table class="table-users">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>

                        <td>
                            <?php if ($user['status'] === 'ACTIVE'): ?>
                                <a href="?url=admin/changeStatus&id=<?= $user['id'] ?>&status=BLOCKED"
                                   class="btn-block"
                                   onclick="return confirm('Block this user?');">
                                    Block
                                </a>
                            <?php else: ?>
                                <a href="?url=admin/changeStatus&id=<?= $user['id'] ?>&status=ACTIVE"
                                   class="btn-unblock"
                                   onclick="return confirm('Unblock this user?');">
                                    Unblock
                                </a>
                            <?php endif; ?>
                        </td>
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
