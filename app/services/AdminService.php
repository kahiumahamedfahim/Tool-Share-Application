<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class AdminService
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }


    public function getAllUsers(): array
    {
        return $this->userRepo->getUsersByRole('USER');
    }
     public function getAllVendors(): array
    {
        return $this->userRepo->getUsersByRole('VENDOR');
    }
    public function getAllAdmins(): array
    {
        return $this->userRepo->getUsersByRole('ADMIN');
    }
     public function createAdmin(array $data): array
    {
        // Basic validation (service-level)
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['password'])) {
            return [
                'success' => false,
                'message' => 'All fields are required'
            ];
        }

        // Email format check
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }

        if ($this->userRepo->findByEmail($data['email'])) {
            return [
                'success' => false,
                'message' => 'Email already exists'
            ];
        }
         if (!preg_match('/^01[0-9]{9}$/', $data['phone'])) 
    {
    return [
        'success' => false,
        'message' => 'Phone number must start with 01 and be 11 digits'
    ];
    }


        $admin = [
            'id'       => uniqid('adm_', true),
            'name'     => trim($data['name']),
            'email'    => trim($data['email']),
            'phone'    => trim($data['phone']),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        $this->userRepo->createAdmin($admin);

        return [
            'success' => true,
            'message' => 'Admin created successfully'
        ];
    }
    public function getAllAdminsExceptMe(string $adminId): array
{
    return $this->userRepo->getAdminsExcept($adminId);
}
public function deleteAdmin(string $adminId, string $currentAdminId): array
{
    // 🔒 Self-delete protection
    if ($adminId === $currentAdminId) {
        return [
            'success' => false,
            'message' => 'You cannot delete yourself'
        ];
    }

  
    $deleted = $this->userRepo->deleteAdminById($adminId);

    if (!$deleted) {
        return [
            'success' => false,
            'message' => 'Admin not found or already deleted'
        ];
    }

    return [
        'success' => true,
        'message' => 'Admin deleted successfully'
    ];
}
public function changeUserStatus(string $targetUserId, string $newStatus, array $currentUser): array
{
    // 🔒 Only admin can do this
    if ($currentUser['role'] !== 'ADMIN') {
        return [
            'success' => false,
            'message' => 'Unauthorized action'
        ];
    }

    // 🔒 Admin cannot block/deactivate himself
    if ($targetUserId === $currentUser['id']) {
        return [
            'success' => false,
            'message' => 'You cannot change your own status'
        ];
    }

    // 🔒 Allowed status values
    $allowedStatus = ['ACTIVE', 'BLOCKED', 'DEACTIVATED'];

    if (!in_array($newStatus, $allowedStatus)) {
        return [
            'success' => false,
            'message' => 'Invalid status'
        ];
    }

    // 🔥 Repo call
    $updated = $this->userRepo->updateStatus($targetUserId, $newStatus);

    if (!$updated) {
        return [
            'success' => false,
            'message' => 'User not found or status unchanged'
        ];
    }

    return [
        'success' => true,
        'message' => 'User status updated successfully'
    ];
}


    
}
