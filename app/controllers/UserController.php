<?php

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/ToolService.php';
class UserController
{
    private $userService;
     private ToolService $toolService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->toolService = new ToolService();
    }

public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        

        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== 0) {
            echo "<script>alert('Profile image is required');</script>";
            return;
        }

        $image = $_FILES['profile_image'];
        $ext   = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

       $allowedExt = ['jpg', 'jpeg', 'png', 'avif'];

      if (!in_array($ext, $allowedExt)) {
        echo "<script>alert('Only JPG, JPEG, PNG, AVIF allowed');</script>";
        return;
    }
        // unique filename
        $fileName = uniqid('profile_', true) . '.' . $ext;

        // physical path (server)
        $uploadDir  = __DIR__ . '/../../public/uploads/profile/';
        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
            echo "<script>alert('Failed to upload image');</script>";
            return;
        }


        $imagePath = 'uploads/profile/' . $fileName;

     

        $data = [
            'name'              => $_POST['name'] ?? '',
            'email'             => $_POST['email'] ?? '',
            'phone'             => $_POST['phone'] ?? '',
            'nid_number'        => $_POST['nid_number'] ?? null,
            'password'          => $_POST['password'] ?? '',
            'confirm_password'  => $_POST['confirm_password'] ?? '',
            'role'              => $_POST['role'] ?? '',
            'profile_image'     => $imagePath, 
            'shop_number'       => $_POST['shop_number'] ?? null,
            'business_card_no'  => $_POST['business_card_no'] ?? null,
        ];
            //service call

        $result = $this->userService->register($data);

       if ($result['success']) {
    echo "<script>
        alert('Registration successful');
        window.location.href = '/tool_sharing_application/public/?url=user/login';
    </script>";
    exit;
}
 else {
            echo "<script>alert('{$result['message']}');</script>";
        }
    }

    require __DIR__ . '/../views/user/register.php';
}

public function success()
{
    require __DIR__ . '/../views/user/success.php';
}
 public function login()
 {
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {

       $data = [
           'login_id' => $_POST['login_id'] ?? '',
           'password' => $_POST['password'] ?? '',
       ];

       $result = $this->userService->login($data);

      if ($result['success']) {

        // Store minimum required data
        $_SESSION['user'] = [
            'id'    => $result['user']['id'],
            'name'  => $result['user']['name'],
            'role'  => $result['user']['role'],
            'email' => $result['user']['email']
        ];

        header("Location: ?url=user/ViewForAllUser");
        exit;
      } else {
           echo "<script>alert('{$result['message']}');</script>";
      }
   }

   require __DIR__ . '/../views/user/login.php';
 }
 public function forgotPassword()
 {
    echo "forgot password controller hit";
    
 }
 public function guestView()
 {
   $currentUser = $_SESSION['user'] ?? null;

        // Get tools for home page
        $tools = $this->toolService->getToolsForHome($currentUser);

        // Load view
        require __DIR__ . '/../views/home/index.php';

}
public function logout()
{
    // Clear all session data
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: /tool_sharing_application/public/?url=user/guestView");
    exit;   
}
public function ViewForAllUser()
{
    $currentUser = $_SESSION['user'] ?? null;

        // Get tools for home page
        $tools = $this->toolService->getToolsForHome($currentUser);

        // Load view
        require __DIR__ . '/../views/home/index.php';
}
public function profile()
{
   
    if (!isset($_SESSION['user'])) {
        header("Location: ?url=user/login");
        exit;
    }

    $userId = $_SESSION['user']['id'];

    $user = $this->userService->getMyProfile($userId);

    if (!$user) {
        die('Profile not found');
    }

    $defaultImage = '/tool_sharing_application/public/uploads/default/profile.png';

// If user uploaded image exists
if (!empty($user['profile_image'])) {

    // Physical server path
    $physicalPath = __DIR__ . '/../../public/' . $user['profile_image'];

    if (file_exists($physicalPath)) {
        // Browser-accessible path
        $profileImage = '/tool_sharing_application/public/' . $user['profile_image'];
    } else {
        $profileImage = $defaultImage;
    }

} else {
    $profileImage = $defaultImage;
}

    // Standard size (used in view)
    $imageSize = [
        'width'  => 140,
        'height' => 140
    ];

    
    require __DIR__ . '/../views/user/profile.php';
}

public function updateProfile()
{
    
    if (!isset($_SESSION['user'])) {
        header("Location: ?url=user/login");
        exit;
    }

    $userId = $_SESSION['user']['id'];

    
    $user = $this->userService->getMyProfile($userId);

    if (!$user) {
        die('Profile not found');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [
            'id'               => $userId,
            'name'             => $_POST['name'] ?? '',
            'phone'            => $_POST['phone'] ?? '',
            'shop_number'      => $_POST['shop_number'] ?? null,
            'business_card_no' => $_POST['business_card_no'] ?? null,
        ];

        $result = $this->userService->updateProfile($data, $_SESSION['user']);

        if ($result['success']) {
        
            $_SESSION['user']['name']  = $data['name'];
            $_SESSION['user']['phone'] = $data['phone'];

            header("Location: ?url=user/profile");
            exit;
        } else {
            $error = $result['message'];
        }
    }


if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) 
    {

    $image = $_FILES['profile_image'];
    $ext   = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    $allowedExt = ['jpg', 'jpeg', 'png', 'avif'];

    if (!in_array($ext, $allowedExt)) {
        $error = 'Only JPG, JPEG, PNG, AVIF allowed';
        require __DIR__ . '/../views/user/update_profile.php';
        return;
    }

    $fileName = uniqid('profile_', true) . '.' . $ext;

    $uploadDir = __DIR__ . '/../../public/uploads/profile/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadPath = $uploadDir . $fileName;

    if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
        $error = 'Image upload failed';
        require __DIR__ . '/../views/user/update_profile.php';
        return;
    }

    // Delete old image (optional but professional)
    if (!empty($user['profile_image'])) {
        $oldPath = __DIR__ . '/../../public/' . $user['profile_image'];
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    $imagePath = 'uploads/profile/' . $fileName;

    // save image path
    $this->userService->updateProfileImage($userId, $imagePath);
}
    require __DIR__ . '/../views/user/update_profile.php';
}
public function changePassword()
{
    // 🔒 Login check
    if (!isset($_SESSION['user'])) {
        header("Location: ?url=user/login");
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $error  = null;

    // 🔹 Handle form submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [
            'id'               => $userId,
            'old_password'     => $_POST['old_password'] ?? '',
            'new_password'     => $_POST['new_password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
        ];

        $result = $this->userService->changePassword($data, $_SESSION['user']);

        if ($result['success']) {
            // redirect after success
            header("Location: ?url=user/profile");
            exit;
        } else {
            $error = $result['message'];
        }
    }

    // 🔹 Load view
    require __DIR__ . '/../views/user/change_password.php';
}
}