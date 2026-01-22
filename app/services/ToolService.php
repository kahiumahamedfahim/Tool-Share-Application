<?php

require_once __DIR__ . '/../repositories/ToolRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/ToolImageRepository.php';
require_once __DIR__ . '/../repositories/RentRepository.php';


class ToolService
{
    private ToolRepository $toolRepo;
    private CategoryRepository $categoryRepo;
    private ToolImageRepository $toolImageRepo;
    private RentRepository $rentRepo;

   

    public function __construct()
    {
        $this->toolRepo     = new ToolRepository();
        $this->categoryRepo = new CategoryRepository();
         $this->toolImageRepo = new ToolImageRepository();
        $this->rentRepo     = new RentRepository();

    }

    public function createTool(array $data, array $currentUser): array
    {
        
        if (empty($currentUser)) {
            return [
                'success' => false,
                'message' => 'Unauthorized'
            ];
        }

    
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


        if ($data['price_per_day'] <= 0) {
            return [
                'success' => false,
                'message' => 'Price per day must be greater than zero'
            ];
        }
        if ($data['quantity'] <= 0 || !is_numeric($data['quantity'])) {
            return [
                'success' => false,
                'message' => 'Quantity must be a positive number'
            ];
        }
        $activeCategories = $this->categoryRepo->getActive();
        $categoryIds = array_column($activeCategories, 'id');

        if (!in_array($data['category_id'], $categoryIds)) {
            return [
                'success' => false,
                'message' => 'Invalid or inactive category'
            ];
        }

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

    
        if (empty($data['images']) || count($data['images']) < 1) {
            return [
                'success' => false,
                'message' => 'At least one tool image is required'
            ];
        }

      
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
    
public function getToolsForHome(?array $currentUser): array
{
   
    if (empty($currentUser)) {
        return $this->toolRepo->getAllAvailable();
    }

    if ($currentUser['role'] === 'ADMIN') {
        return $this->toolRepo->getAllAvailable();
    }

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

    $tool['images'] = $this->toolRepo->getImagesByToolId($toolId);

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

public function getMyToolsWithLockStatus(array $currentUser): array
{
    if (empty($currentUser)) {
        return [];
    }

    $tools = $this->toolRepo->getByUser($currentUser['id']);

    foreach ($tools as &$tool) {
       
        $isLocked = $this->rentRepo
            ->hasActiveRentForTool($tool['id']);

        $tool['is_locked'] = $isLocked;
    }

    return $tools;
}

public function canUpdateTool(string $toolId, array $currentUser): bool
{
    $tool = $this->toolRepo->getById($toolId);

    if (!$tool) {
        return false;
    }


    if ($tool['user_id'] !== $currentUser['id']) {
        return false;
    }

    return !$this->rentRepo->hasActiveRentForTool($toolId);
}

public function canDeleteTool(string $toolId, array $currentUser): bool
{
    // Same rule as update
    return $this->canUpdateTool($toolId, $currentUser);
}

public function deleteTool(string $toolId, array $currentUser): bool
{
    $tool = $this->toolRepo->getById($toolId);

    if (!$tool) {
        return false;
    }

    if ($tool['user_id'] !== $currentUser['id']) {
        return false;
    }

    if ($this->rentRepo->hasActiveRentForTool($toolId)) {
        return false;
    }

    return $this->toolRepo->delete($toolId);
}

public function getToolByIdForEdit(string $toolId, array $currentUser): ?array
{
    $tool = $this->toolRepo->getById($toolId);

    if (!$tool) {
        return null;
    }

    if ($tool['user_id'] !== $currentUser['id']) {
        return null;
    }

    return $tool;
}
public function updateTool(string $toolId, array $data, array $currentUser): array
{
    $tool = $this->toolRepo->getById($toolId);

    if (!$tool) {
        return ['success' => false, 'message' => 'Tool not found'];
    }

    if ($tool['user_id'] !== $currentUser['id']) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    if ($this->rentRepo->hasActiveRentForTool($toolId)) {
        return ['success' => false, 'message' => 'Tool is currently rented'];
    }

    $required = ['name','price_per_day','quantity','location','description'];

    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => "$field is required"];
        }
    }

    return [
        'success' => $this->toolRepo->update($toolId, [
            'name' => $data['name'],
            'price_per_day' => $data['price_per_day'],
            'quantity' => $data['quantity'],
            'location' => $data['location'],
            'description' => $data['description'],
        ]),
        'message' => 'Tool updated successfully'
    ];
}
public function getToolForEditWithImages(string $toolId, array $currentUser): ?array
{
    $tool = $this->toolRepo->getById($toolId);

    if (!$tool || $tool['user_id'] !== $currentUser['id']) {
        return null;
    }

    if ($this->rentRepo->hasActiveRentForTool($toolId)) {
        return null;
    }

    $tool['images'] = $this->toolImageRepo->getByToolId($toolId);

    return $tool;
}

  public function getImagesByToolId( $toolId): array
    {
        return $this->toolImageRepo->getByToolId($toolId);
    }

    public function deleteImagesByToolId( $toolId): void
    {
        $this->toolImageRepo->deleteByToolId($toolId);
    }

    public function addToolImage( $toolId, string $imagePath): void
    {
        $this->toolImageRepo->insert($toolId, $imagePath);
    }

}
