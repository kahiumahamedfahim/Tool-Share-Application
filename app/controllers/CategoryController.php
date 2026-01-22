<?php

require_once __DIR__ . '/../services/CategoryService.php';

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

  
    public function index()
    {
      
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header("Location: ?url=user/login");
            exit;
        }

        $error   = null;
        $success = null;

      
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

    
        $categories = $this->categoryService->getAllCategories($_SESSION['user']);

        // 👁 View
        require __DIR__ . '/../views/category/index.php';
    }


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
