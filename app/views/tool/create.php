<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Tool</title>
</head>
  <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/toolcreate.css">
<body>

<h2>Create Tool</h2>

<!-- =========================
     Success / Error message
     ========================= -->
<?php if (!empty($success)): ?>
    <p><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- =========================
     Tool Create Form
     ========================= -->
<form method="POST" enctype="multipart/form-data">

   

    <!-- Tool Name -->
    <label>Tool Name</label><br>
    <input type="text" name="name" required>

    <br><br>

    <!-- Description -->
    <label>Description</label><br>
    <textarea name="description" rows="4" required></textarea>

    <br><br>
     <!-- Category -->
    <label>Category</label><br>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>

        <?php
        // Active categories needed
        require_once __DIR__ . '/../../repositories/CategoryRepository.php';
        $catRepo = new CategoryRepository();
        $categories = $catRepo->getActive();

        foreach ($categories as $cat):
        ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <!-- Price per day -->
    <label>Price Per Day</label><br>
    <input type="number" name="price_per_day" step="0.01" required>

    <br><br>

    <!-- Quantity -->
    <label>Quantity</label><br>
    <input type="number" name="quantity" min="1" required>

    <br><br>

    <!-- Location -->
    <label>Location</label><br>
    <input type="text" name="location" required>

    <br><br>

    <!-- Images -->
    <label>Tool Images</label><br>
    <input type="file" name="images[]" multiple required>

    <br><br>

    <button type="submit">Create Tool</button>

</form>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>

</body>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
</html>
