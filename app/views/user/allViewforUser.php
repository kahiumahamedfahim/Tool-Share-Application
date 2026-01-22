<?php 
require_once __DIR__ . '/../layouts/header.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
     <title>Dashboard</title>

 <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/header.css"> 
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/footer.css">
    <link rel="stylesheet" href="/final/tool-share-application/app/assets/css/allviewforuser.css">
</head>
<body>
    <?php require_once __DIR__ . '/../layouts/header.php'; ?>
      <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>

        <div>
            <h2>This is Admin View</h2>
        </div>

    <?php elseif ($_SESSION['user']['role'] === 'USER'): ?>

        <div>
            <h2>This is User View</h2>
        </div>

    <?php elseif ($_SESSION['user']['role'] === 'VENDOR'): ?>

        <div>
            <h2>This is Vendor View</h2>
        </div>

    <?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script src="/tool_sharing_application/app/assets/js/header.js"></script>


</body>
</html>