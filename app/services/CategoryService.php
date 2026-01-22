<?php

require_once __DIR__ . '/../repositories/CategoryRepository.php';

class CategoryService
{
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
    }

   public function createCategory(string $name, array $currentUser): array
{
  
    if ($currentUser['role'] !== 'ADMIN') {
        return [
            'success' => false,
            'message' => 'Unauthorized action'
        ];
    }

  
    $name = strtoupper(trim($name));

    if ($name === '') {
        return [
            'success' => false,
            'message' => 'Category name is required'
        ];
    }

    
    if ($this->categoryRepo->findByName($name)) {
        return [
            'success' => false,
            'message' => 'Category already exists'
        ];
    }

    $id = uniqid('cat_', true);

    $created = $this->categoryRepo->create($id, $name);

    if (!$created) {
        return [
            'success' => false,
            'message' => 'Failed to create category'
        ];
    }

    return [
        'success' => true,
        'message' => 'Category created successfully'
    ];
}

  
    public function getAllCategories(array $currentUser): array
    {
        
        if ($currentUser['role'] !== 'ADMIN') {
            return [];
        }

        return $this->categoryRepo->getAll();
    }

    /* =========================
       Active Categories (Public)
       ========================= */
    public function getActiveCategories(): array
    {
        return $this->categoryRepo->getActive();
    }

   
    public function updateCategoryStatus(string $id, string $status, array $currentUser): array
    {
        // 🔒 Admin only
        if ($currentUser['role'] !== 'ADMIN') {
            return [
                'success' => false,
                'message' => 'Unauthorized action'
            ];
        }

        if (!in_array($status, ['ACTIVE', 'INACTIVE'])) {
            return [
                'success' => false,
                'message' => 'Invalid status'
            ];
        }

        $updated = $this->categoryRepo->updateStatus($id, $status);

        if (!$updated) {
            return [
                'success' => false,
                'message' => 'Category not found or unchanged'
            ];
        }

        return [
            'success' => true,
            'message' => 'Category status updated'
        ];
    }
  
public function deleteCategory(string $id, array $currentUser): array
{
    // 🔒 Admin only
    if ($currentUser['role'] !== 'ADMIN') {
        return [
            'success' => false,
            'message' => 'Unauthorized action'
        ];
    }

    if (empty($id)) {
        return [
            'success' => false,
            'message' => 'Category ID is required'
        ];
    }

    $deleted = $this->categoryRepo->delete($id);

    if (!$deleted) {
        return [
            'success' => false,
            'message' => 'Category not found or delete failed'
        ];
    }

    return [
        'success' => true,
        'message' => 'Category deleted successfully'
    ];
}

}
