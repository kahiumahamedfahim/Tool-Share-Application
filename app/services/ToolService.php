<?php

require_once __DIR__ . '/../repositories/ToolRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/ToolImageRepository.php';


class ToolService
{
    private ToolRepository $toolRepo;
    private CategoryRepository $categoryRepo;
    private ToolImageRepository $toolImageRepo;

     /* =========================
        Constructor
        ========================= */

    public function __construct()
    {
        $this->toolRepo     = new ToolRepository();
        $this->categoryRepo = new CategoryRepository();
         $this->toolImageRepo = new ToolImageRepository();

    }

    /* =========================
       Create Tool
       ========================= */
    public function createTool(array $data, array $currentUser): array
    {
        // 🔒 Auth check
        if (empty($currentUser)) {
            return [
                'success' => false,
                'message' => 'Unauthorized'
            ];
        }

        // 🔹 Required fields
        $required = [
            'category_id',
            'name',
            'description',
            'price_per_day',
            'quantity',
            'location'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
                ];
            }
        }

        // 🔹 Price validation
        if ($data['price_per_day'] <= 0) {
            return [
                'success' => false,
                'message' => 'Price per day must be greater than zero'
            ];
        }

        // 🔹 Category must be ACTIVE
        $activeCategories = $this->categoryRepo->getActive();
        $categoryIds = array_column($activeCategories, 'id');

        if (!in_array($data['category_id'], $categoryIds)) {
            return [
                'success' => false,
                'message' => 'Invalid or inactive category'
            ];
        }

        // 🔹 Quantity rule by role
        if ($currentUser['role'] === 'USER' && $data['quantity'] != 1) {
            return [
                'success' => false,
                'message' => 'User can upload only 1 quantity'
            ];
        }

        if ($currentUser['role'] === 'VENDOR' && $data['quantity'] < 2) {
            return [
                'success' => false,
                'message' => 'Vendor must upload at least 2 quantities'
            ];
        }

        // 🔹 Image count (paths provided by controller)
        if (empty($data['images']) || count($data['images']) < 1) {
            return [
                'success' => false,
                'message' => 'At least one tool image is required'
            ];
        }

        // 🔹 Prepare tool data
        $tool = [
            'id'            => uniqid('tool_', true),
            'user_id'       => $currentUser['id'],
            'category_id'   => $data['category_id'],
            'name'          => trim($data['name']),
            'description'   => trim($data['description']),
            'price_per_day' => $data['price_per_day'],
            'quantity'      => $data['quantity'],
            'location'      => trim($data['location']),
            'status'        => 'AVAILABLE'
        ];

        // 🔹 Save tool
        $created = $this->toolRepo->create($tool);

        if (!$created) {
            return [
                'success' => false,
                'message' => 'Failed to create tool'
            ];
        }
        foreach ($data['images'] as $imagePath) {
    $this->toolImageRepo->add(
        uniqid('img_', true),
        $tool['id'],
        $imagePath
    );
}

        return [
            'success' => true,
            'tool_id' => $tool['id'],
            'message' => 'Tool created successfully'
        ];
    }
    /* =========================
   Get Tools For Home Page
   ========================= */
public function getToolsForHome(?array $currentUser): array
{
    // Guest user
    if (empty($currentUser)) {
        return $this->toolRepo->getAllAvailable();
    }

    // Admin sees all tools
    if ($currentUser['role'] === 'ADMIN') {
        return $this->toolRepo->getAllAvailable();
    }

    // User / Vendor → exclude own tools
    return $this->toolRepo->getAllAvailableExceptUser($currentUser['id']);
}
public function getToolDetails(string $toolId, ?array $currentUser): array
{
    $tool = $this->toolRepo->getDetailsById($toolId);

    if (!$tool) {
        return [
            'success' => false,
            'message' => 'Tool not found'
        ];
    }

    // Attach images
    $tool['images'] = $this->toolRepo->getImagesByToolId($toolId);

    // 🔒 Guest restriction:
    // Guest can see owner NAME only
    if (empty($currentUser)) {
        unset(
            $tool['owner_email'],
            $tool['owner_phone'],
            $tool['owner_image']
        );
    }

    return [
        'success' => true,
        'data' => $tool
    ];
}



}
