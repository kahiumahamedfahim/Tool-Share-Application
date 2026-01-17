<?php

require_once __DIR__ . '/../services/CategoryService.php';

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    /* =====================================
       INDEX (List + Create Category)
       URL: ?url=category/index
       ===================================== */
    public function index()
    {
        // 🔒 Admin only
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header("Location: ?url=user/login");
            exit;
        }

        $error   = null;
        $success = null;

        // ➕ Create category
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';

            $result = $this->categoryService->createCategory(
                $name,
                $_SESSION['user']
            );

            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }

        // 📋 Get all categories
        $categories = $this->categoryService->getAllCategories($_SESSION['user']);

        // 👁 View
        require __DIR__ . '/../views/category/index.php';
    }

    /* =====================================
       UPDATE STATUS (ACTIVE / INACTIVE)
       URL: ?url=category/updateStatus&id=CAT_ID&status=ACTIVE|INACTIVE
       ===================================== */
    public function updateStatus()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header("Location: ?url=user/login");
            exit;
        }

        $id     = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;

        if (!$id || !$status) {
            header("Location: ?url=category/index");
            exit;
        }

        $this->categoryService->updateCategoryStatus(
            $id,
            $status,
            $_SESSION['user']
        );

        header("Location: ?url=category/index");
        exit;
    }

    public function delete()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header("Location: ?url=user/login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=category/index");
            exit;
        }

        $this->categoryService->deleteCategory(
            $id,
            $_SESSION['user']
        );

        header("Location: ?url=category/index");
        exit;
    }
}
