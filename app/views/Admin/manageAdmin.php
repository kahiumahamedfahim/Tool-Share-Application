<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admin</title>
         <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/allvendorsview.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/manageadmin.css">
</head>
<body>

<div class="page-container">

    <h2>Manage Admin</h2>

    <!-- =========================
         CREATE ADMIN FORM
         ========================= -->
    <h3>Create New Admin</h3>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="?url=admin/manageAdmins">

        <div>
            <label>Name</label><br>
            <input type="text" name="name" required>
        </div>

        <div>
            <label>Email</label><br>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Phone</label><br>
            <input type="text" name="phone" required>
        </div>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <div style="margin-top: 10px;">
            <button type="submit">Create Admin</button>
        </div>

    </form>

    <hr style="margin: 30px 0;">

  
    <h3>Admin List</h3>

    <?php if (empty($admins)): ?>
        <p>No admin found.</p>
    <?php else: ?>

        <table border="1" cellpadding="8" cellspacing="0" width="100%">
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
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['name']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td><?= htmlspecialchars($admin['phone']) ?></td>
                        <td><?= htmlspecialchars($admin['status']) ?></td>
                        <td><?= htmlspecialchars($admin['created_at']) ?></td>
                        <td>
                <?php if ($admin['id'] !== $_SESSION['user']['id']): ?>
                    <a href="?url=admin/deleteAdmin&id=<?= $admin['id'] ?>"
                    class="btn-delete"
                    title="Delete Admin"
                    onclick="return confirm('Are you sure you want to delete this admin?');">
                        🗑 Delete
                    </a>
                <?php else: ?>
                    <span class="self-admin">—</span>
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

</body>
<script src="/final/tool-share-application/app/assets/js/header.js"></script>
</html>
