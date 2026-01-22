<?php

require_once __DIR__ . '/../services/AdminService.php';

class AdminController
{
    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
    }

    public function users()
    {
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header("Location: ?url=user/login");
            exit;
        }

        $users = $this->adminService->getAllUsers();

        require __DIR__ . '/../views/Admin/users.php';
    }
    public function vendors()
{
    // 🔒 Admin protection
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
        header("Location: ?url=user/login");
        exit;
    }


    $vendors = $this->adminService->getAllVendors();

   
    require __DIR__ . '/../views/admin/vendors.php';
}
public function manageAdmins()
{
  
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
        header("Location: ?url=user/login");
        exit;
    }

    $currentAdminId = $_SESSION['user']['id'];
    $admins = $this->adminService->getAllAdminsExceptMe($currentAdminId);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [
            'name'     => $_POST['name'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'phone'    => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];

        $result = $this->adminService->createAdmin($data);

        if ($result['success']) {
            header("Location: ?url=admin/manageAdmins");
            exit;
        } else {
            $error = $result['message'];
        }
    }

    require __DIR__ . '/../views/Admin/manageAdmin.php';
}   
public function deleteAdmin()
{
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
        header("Location: ?url=user/login");
        exit;
    }

    
    $adminId = $_GET['id'] ?? null;

    if (!$adminId) {
        header("Location: ?url=admin/manageAdmins");
        exit;
    }

    $currentAdminId = $_SESSION['user']['id'];

    $result = $this->adminService->deleteAdmin($adminId, $currentAdminId);


    header("Location: ?url=admin/manageAdmins");
    exit;
}
public function changeStatus()
{
    // 🔒 Login + Admin check
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
        header("Location: ?url=user/login");
        exit;
    }

    // 🔑 Read params
    $targetUserId = $_GET['id'] ?? null;
    $newStatus    = $_GET['status'] ?? null;

    if (!$targetUserId || !$newStatus) {
        header("Location: ?url=admin/users");
        exit;
    }

  
    $result = $this->adminService->changeUserStatus(
        $targetUserId,
        $newStatus,
        $_SESSION['user']
    );

    // 🔁 Redirect back to user list
    header("Location: ?url=admin/users");
    exit;
}


}
