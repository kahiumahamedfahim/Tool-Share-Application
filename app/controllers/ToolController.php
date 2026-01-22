<?php

require_once __DIR__ . '/../services/ToolService.php';

class ToolController
{
    private ToolService $toolService;

    public function __construct()
    {
        $this->toolService = new ToolService();
    }

       public function create()
    {
       
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=user/login");
            exit;
        }

        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_FILES['images']['name'][0])) {
                $error = 'At least one tool image is required';
            } else {

                $imagePaths = [];
                $uploadDir  = __DIR__ . '/../../public/uploads/tools/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {

                    if ($_FILES['images']['error'][$index] !== 0) {
                        continue;
                    }

                    $ext = strtolower(
                        pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION)
                    );

                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif'];

                    if (!in_array($ext, $allowed)) {
                        continue;
                    }

                    $fileName = uniqid('tool_', true) . '.' . $ext;
                    $target   = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $target)) {
                        $imagePaths[] = 'uploads/tools/' . $fileName;
                    }
                }

                if (empty($imagePaths)) {
                    $error = 'Image upload failed';
                } else {

                    $data = [
                        'category_id'   => $_POST['category_id'] ?? '',
                        'name'          => $_POST['name'] ?? '',
                        'description'   => $_POST['description'] ?? '',
                        'price_per_day' => $_POST['price_per_day'] ?? '',
                        'quantity'      => $_POST['quantity'] ?? '',
                        'location'      => $_POST['location'] ?? '',
                        'images'        => $imagePaths
                    ];

                    $result = $this->toolService->createTool(
                        $data,
                        $_SESSION['user']
                    );

                    if ($result['success']) {
                        $success = $result['message'];
                    } else {
                        $error = $result['message'];
                    }
                }
            }
        }

      
        require __DIR__ . '/../views/tool/create.php';
    }
 public function details()
{
    $toolId = isset($_GET['id']) ? trim($_GET['id']) : null;

    if (empty($toolId)) {
        echo "Invalid tool ID";
        exit;
    }

    $currentUser = $_SESSION['user'] ?? null;

    $result = $this->toolService->getToolDetails($toolId, $currentUser);

    if (!$result['success']) {
        echo $result['message'];
        exit;
    }

    $tool = $result['data'];

    require_once __DIR__ . '/../views/tool/tooldetails.php';
}

public function myTools()
{
    $currentUser = $_SESSION['user'] ?? null;

    if (!$currentUser) {
        header("Location: ?url=user/login");
        exit;
    }

    $tools = $this->toolService
        ->getMyToolsWithLockStatus($currentUser);

    require __DIR__ . '/../views/tool/myTools.php';
}

public function edit()
{
    $currentUser = $_SESSION['user'] ?? null;
    $toolId = $_GET['id'] ?? null;

    if (!$currentUser || !$toolId) {
        die('Unauthorized');
    }

    $tool = $this->toolService
        ->getToolForEditWithImages($toolId, $currentUser);

    if (!$tool) {
        die('Cannot edit this tool');
    }

    require __DIR__ . '/../views/tool/edit.php';
}

public function delete()
{
    $currentUser = $_SESSION['user'] ?? null;
    $toolId = $_GET['id'] ?? '';

    if (!$currentUser || !$toolId) {
        die('Unauthorized');
    }

   
    if (!$this->toolService->canDeleteTool($toolId, $currentUser)) {
        die('This tool cannot be deleted while it is rented.');
    }

    $this->toolService->deleteTool($toolId, $currentUser);

    header("Location: ?url=tool/myTools");
    exit;
}

public function update()
{
    $currentUser = $_SESSION['user'] ?? null;
    $toolId = $_GET['id'] ?? null;

    if (!$currentUser || !$toolId) {
        die('Unauthorized access');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die('Invalid request method');
    }

    // ❌ rented tool cannot be updated
    if (!$this->toolService->canUpdateTool($toolId, $currentUser)) {
        die('This tool cannot be updated while it is rented.');
    }

    // 🔹 Update basic tool info
    $data = [
        'name'          => $_POST['name'] ?? '',
        'price_per_day' => $_POST['price_per_day'] ?? '',
        'quantity'      => $_POST['quantity'] ?? '',
        'location'      => $_POST['location'] ?? '',
        'description'   => $_POST['description'] ?? '',
    ];

    $result = $this->toolService->updateTool($toolId, $data, $currentUser);

    if (!$result['success']) {
        echo "<script>alert('{$result['message']}');history.back();</script>";
        exit;
    }

    /* =========================
       🔥 IMAGE UPDATE PART
       ========================= */

    if (!empty($_FILES['images']['name'][0])) {

        $uploadDir = __DIR__ . '/../../public/uploads/tools/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 1️⃣ delete old images (filesystem)
        $oldImages = $this->toolService->getImagesByToolId($toolId);

        foreach ($oldImages as $img) {
            $oldPath = __DIR__ . '/../../public/' . $img['image_path'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // 2️⃣ delete old image records (DB)
        $this->toolService->deleteImagesByToolId($toolId);

        // 3️⃣ upload new images
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {

            if ($_FILES['images']['error'][$index] !== 0) {
                continue;
            }

            $ext = pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION);
            $fileName = uniqid('tool_', true) . '.' . $ext;

            move_uploaded_file(
                $tmpName,
                $uploadDir . $fileName
            );

            // save relative path only
            $this->toolService->addToolImage(
                $toolId,
                'uploads/tools/' . $fileName
            );
        }
    }

    // ✅ Redirect after everything
    header("Location: ?url=tool/myTools");
    exit;
}



}
