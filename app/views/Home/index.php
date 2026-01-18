<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Tools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/header.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/tool_sharing_application/app/assets/css/toolhomeindex.css">

</head>

<body>

<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<h2> Tools</h2>

<?php if (empty($tools)): ?>
    <p>No tools available right now.</p>
<?php else: ?>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Image</th>
                <th>Tool Name</th>
                <th>Price / Day</th>
                <th>Description</th>
                <th>Location</th>
                <th>Owner</th> 
                <th>Details</th>
             
                
            </tr>
        </thead>

        <tbody>
            <?php foreach ($tools as $tool): ?>
                <tr>
                    <td>
                        <?php if (!empty($tool['image'])): ?>
                            <img
                                src="/tool_sharing_application/public/<?= htmlspecialchars($tool['image']) ?>"
                                alt="Tool Image"
                                width="80"
                            >
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($tool['name']) ?></td>
                    <td><?= htmlspecialchars($tool['price_per_day']) ?></td>
                    <td>
                     <p class="description">
        <?= htmlspecialchars(
            mb_strlen($tool['description']) > 80
                ? mb_substr($tool['description'], 0, 80) . '...'
                : $tool['description']
        ) ?>
    </p>
                        </td>
                    
                    <td><?= htmlspecialchars($tool['location']) ?></td>
                    <td><?= htmlspecialchars($tool['owner_name']) ?></td>
                    <td>
                      <a href="?url=tool/details&id=<?= $tool['id'] ?>">
    View Details
</a>


                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
sc
</body>
<script src="/tool_sharing_application/app/assets/js/header.js"></script>
</html>
