<?php

require_once __DIR__ . '/../services/ToolService.php';

class ToolController
{
    private ToolService $toolService;

    public function __construct()
    {
        $this->toolService = new ToolService();
    }

    /* =========================
       Create Tool
       URL: ?url=tool/create
       ========================= */
    public function create()
    {
        // 🔒 Login required
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

                // Ensure directory exists
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

                /* =========================
                   Call Service
                   ========================= */
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

        /* =========================
           Load View
           ========================= */
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


}
