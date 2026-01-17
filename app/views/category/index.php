<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/category.css">
</head>
<body>

<h2>Manage Categories</h2>

<!-- =========================
     Success / Error Message
     ========================= -->
<?php if (!empty($success)): ?>
    <p><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<hr>

<!-- =========================
     Create Category
     ========================= -->
<h3>Add New Category</h3>

<form method="POST">
    <label>Category Name</label><br>
    <input type="text" name="name" required>
    <br><br>
    <button type="submit">Add Category</button>
</form>

<hr>

<h3>All Categories</h3>

<?php if (empty($categories)): ?>
    <p>No categories found.</p>
<?php else: ?>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td><?= htmlspecialchars($category['status']) ?></td>
                <td>
                    <?php if ($category['status'] === 'ACTIVE'): ?>
                        <a href="?url=category/updateStatus&id=<?= $category['id'] ?>&status=INACTIVE">
                            Deactivate
                        </a>
                    <?php else: ?>
                        <a href="?url=category/updateStatus&id=<?= $category['id'] ?>&status=ACTIVE">
                            Activate
                        </a>
                    <?php endif; ?>

                    |
                    <a href="?url=category/delete&id=<?= $category['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this category?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
</body>
</html>
