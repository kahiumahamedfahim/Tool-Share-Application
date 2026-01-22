<?php
require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }
public function register($data)
{
    

    $requiredFields = ['name', 'email', 'phone', 'password', 'profile_image', 'role'];

    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            return [
                'success' => false,
                'message' => ucfirst($field) . ' is required'
            ];
        }
    }

   
    if (!in_array($data['role'], ['USER', 'VENDOR'])) {
        return [
            'success' => false,
            'message' => 'Invalid role selected'
        ];
    }


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

    if ($this->userRepo->findByEmailOrPhone($data['phone'])) {
        return [
            'success' => false,
            'message' => 'Phone number already exists'
        ];
    }
if ($this->userRepo->isPhoneExists($data['phone']))
     {
    return [
        'success' => false,
        'message' => 'Phone number already exists'
    ];
    }
   

    if ($data['role'] === 'VENDOR') {
        if (empty($data['shop_number']) || empty($data['business_card_no'])) {
            return [
                'success' => false,
                'message' => 'Shop number and business card number are required for vendor'
            ];
        }
    }
    if ($data['role'] === 'VENDOR') {

    if (empty($data['shop_number']) || empty($data['business_card_no'])) {
        return [
            'success' => false,
            'message' => 'Shop number and business card number are required'
        ];
    }

    if ($this->userRepo->isVendorFieldExists('shop_number', $data['shop_number'])) {
        return [
            'success' => false,
            'message' => 'Shop number already exists'
        ];
    }

    if ($this->userRepo->isVendorFieldExists('business_card_no', $data['business_card_no'])) 
        {
        return [
            'success' => false,
            'message' => 'Business card number already exists'
        ];
        }
    }


    if ($data['role'] === 'USER' && empty($data['nid_number'])) {
        return [
            'success' => false,
            'message' => 'NID number is required for user'
        ];
    }
    if ($this->userRepo->isPhoneExists($data['phone'])) {
    return [
        'success' => false,
        'message' => 'Phone number already exists'
    ];
}

    $user = [
        'id'               => uniqid('usr_', true),
        'name'             => trim($data['name']),
        'email'            => trim($data['email']),
        'phone'            => trim($data['phone']),
        'password'         => password_hash($data['password'], PASSWORD_DEFAULT),
        'profile_image'    => $data['profile_image'], 
        'role'             => $data['role'],
        'shop_number'      => $data['role'] === 'VENDOR' ? $data['shop_number'] : null,
        'business_card_no' => $data['role'] === 'VENDOR' ? $data['business_card_no'] : null,
        'nid_number'       => $data['role'] === 'USER' ? $data['nid_number'] : null,
    ];


    $this->userRepo->create($user);

    return [
        'success' => true,
        'message' => $data['role'] === 'VENDOR'
            ? 'Vendor registered successfully'
            : 'User registered successfully'
    ];
}


public function login(array $data): array
{
    

    if (empty($data['login_id']) || empty($data['password'])) {
        return [
            'success' => false,
            'message' => 'Email/Phone and password are required'
        ];
    }

  $user = $this->userRepo->findByEmailOrPhone($data['login_id']);

    if (!$user) {
        return [
            'success' => false,
            'message' => 'User not found'
        ];
    }


    if (!password_verify($data['password'], $user['password'])) {
        return [
            'success' => false,
            'message' => 'Incorrect password'
        ];
    }


    if (isset($user['status']) && $user['status'] !== 'ACTIVE') {
        return [
            'success' => false,
            'message' => 'Account is not active'
        ];
    }

    return [
        'success' => true,
        'message' => 'Login successful',
        'user'    => $user
    ];
}
public function getMyProfile(string $userId): ?array
{
    if (empty($userId)) {
        return null;
    }

    $user = $this->userRepo->getUserById($userId);

    if (!$user) {
        return null;
    }

    return $user;
}
public function updateProfile(array $data, array $currentUser): array
{
  
    if ($data['id'] !== $currentUser['id']) {
        return [
            'success' => false,
            'message' => 'Unauthorized profile update'
        ];
    }


    if (!preg_match('/^01[0-9]{9}$/', $data['phone'])) {
        return [
            'success' => false,
            'message' => 'Phone number must start with 01 and be 11 digits'
        ];
    }

    if ($this->userRepo->isPhoneExistsExceptUser($data['phone'], $data['id'])) {
        return [
            'success' => false,
            'message' => 'Phone number already exists'
        ];
    }

  
    if ($currentUser['role'] === 'USER') {
        $data['shop_number'] = null;
        $data['business_card_no'] = null;
    }

    if ($currentUser['role'] === 'ADMIN') {
        $data['shop_number'] = null;
        $data['business_card_no'] = null;
    }

 
    if ($currentUser['role'] === 'VENDOR') {
        if (empty($data['shop_number']) || empty($data['business_card_no'])) {
            return [
                'success' => false,
                'message' => 'Shop number and business card number are required'
            ];
        }
    }


    $updated = $this->userRepo->updateProfileBasic($data);

    if (!$updated) {
        return [
            'success' => false,
           
        ];
    }

    return [
        'success' => true,
        'message' => 'Profile updated successfully'
    ];
}
public function updateProfileImage(string $userId, ?string $imagePath): void
{
   $this->userRepo->updateProfileImage($userId, $imagePath);
   
}
public function changePassword(array $data, array $currentUser): array
{
   
    if ($data['id'] !== $currentUser['id']) {
        return [
            'success' => false,
            'message' => 'Unauthorized password change'
        ];
    }

    if (
        empty($data['old_password']) ||
        empty($data['new_password']) ||
        empty($data['confirm_password'])
    ) {
        return [
            'success' => false,
            'message' => 'All password fields are required'
        ];
    }


    if ($data['new_password'] !== $data['confirm_password']) {
        return [
            'success' => false,
            'message' => 'New password and confirm password do not match'
        ];
    }

    if (strlen($data['new_password']) < 6) {
        return [
            'success' => false,
            'message' => 'Password must be at least 6 characters'
        ];
    }

   
    $oldHashedPassword = $this->userRepo->getUserPasswordById($data['id']);

    if (!$oldHashedPassword || !password_verify($data['old_password'], $oldHashedPassword)) {
        return [
            'success' => false,
            'message' => 'Old password is incorrect'
        ];
    }

    // 🔹 Prevent reuse of old password
    if (password_verify($data['new_password'], $oldHashedPassword)) {
        return [
            'success' => false,
            'message' => 'New password must be different from old password'
        ];
    }

    // 🔹 Hash new password
    $newHashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

    // 🔹 Update DB
    $updated = $this->userRepo->updatePassword($data['id'], $newHashedPassword);

    if (!$updated) {
        return [
            'success' => false,
            'message' => 'Password update failed'
        ];
    }

    return [
        'success' => true,
        'message' => 'Password changed successfully'
    ];
}


}
?>