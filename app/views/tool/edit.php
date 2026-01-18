<?php
require_once __DIR__ . '/../layouts/header.php';
?>
 <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
<link rel="stylesheet" href="/tool_sharing_application/app/assets/css/myTools.css">

<h2>Edit Tool</h2>

<div class="tool-card" style="max-width:600px;margin:20px auto;">

    <form method="POST"
          action="?url=tool/update&id=<?= htmlspecialchars($tool['id']) ?>"
          enctype="multipart/form-data">

        <div class="tool-info">

            <!-- =========================
                 Current Images
                 ========================= -->
            <h4>Current Images</h4>

            <?php if (!empty($tool['images'])): ?>
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
                    <?php foreach ($tool['images'] as $img): ?>
                        <img
                            src="/tool_sharing_application/public/<?= htmlspecialchars($img['image_path']) ?>"
                            alt="Tool Image"
                            style="width:120px;height:90px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;"
                        >
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No images uploaded</p>
            <?php endif; ?>

            <!-- =========================
                 Upload New Images
                 ========================= -->
            <label>Change Images (optional)</label><br>
            <input type="file" name="images[]" multiple accept="image/*">
            <p style="font-size:13px;color:#6c757d;">
                If you upload new images, old images will be replaced.
            </p>

            <hr>

            <!-- =========================
                 Tool Fields
                 ========================= -->
            <label>Tool Name</label><br>
            <input type="text"
                   name="name"
                   value="<?= htmlspecialchars($tool['name']) ?>"
                   required><br><br>

            <label>Price per Day</label><br>
            <input type="number"
                   name="price_per_day"
                   value="<?= htmlspecialchars($tool['price_per_day']) ?>"
                   min="1"
                   required><br><br>

            <label>Quantity</label><br>
            <input type="number"
                   name="quantity"
                   value="<?= htmlspecialchars($tool['quantity']) ?>"
                   min="1"
                   required><br><br>

            <label>Location</label><br>
            <input type="text"
                   name="location"
                   value="<?= htmlspecialchars($tool['location']) ?>"
                   required><br><br>

            <label>Description</label><br>
            <textarea name="description"
                      rows="4"
                      required><?= htmlspecialchars($tool['description']) ?></textarea><br><br>

            <button type="submit">Update Tool</button>

            <br><br>
            <a href="?url=tool/myTools">← Back to My Tools</a>

        </div>

    </form>

</div>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
