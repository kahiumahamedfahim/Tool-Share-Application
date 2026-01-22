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
            require __DIR__ . '/../views/user/register.php';
            return;
        }

        $image = $_FILES['profile_image'];
        $ext   = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        $allowedExt = ['jpg', 'jpeg', 'png', 'avif'];

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Only JPG, JPEG, PNG, AVIF allowed');</script>";
            require __DIR__ . '/../views/user/register.php';
            return;
        }

    
        $fileName = uniqid('profile_', true) . '.' . $ext;

        
        $uploadDir = __DIR__ . '/../../public/uploads/profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
            echo "<script>alert('Failed to upload image');</script>";
            require __DIR__ . '/../views/user/register.php';
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

        $result = $this->userService->register($data);

        if ($result['success']) {
            header("Location: ?url=user/login");
            exit;
        } else {
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

       
        $_SESSION['user'] = [
            'id'    => $result['user']['id'],
            'name'  => $result['user']['name'],
            'role'  => $result['user']['role'],
            'email' => $result['user']['email']
        ];
 
            header("Location: ?url=user/viewForAllUser");
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

        $tools = $this->toolService->getToolsForHome($currentUser);

       
        require __DIR__ . '/../views/home/index.php';

}
public function logout()
{

    session_unset();
    session_destroy();

    
    header("Location: ?url=user/guestView");
    exit;
}
public function ViewForAllUser()
{
    $currentUser = $_SESSION['user'] ?? null;

        
        $tools = $this->toolService->getToolsForHome($currentUser);

      
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

    $defaultImage = '/final/Tool-Share-Application/public/uploads/default/profile.png';

    if (!empty($user['profile_image'])) {

        $physicalPath = __DIR__ . '/../../public/' . $user['profile_image'];

        if (file_exists($physicalPath)) {
            // browser URL
            $profileImage = '/final/Tool-Share-Application/public/' . $user['profile_image'];
        } else {
            $profileImage = $defaultImage;
        }

    } else {
        $profileImage = $defaultImage;
    }

  
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

        if ($result['success']) 
            {

            $_SESSION['user']['name']  = $data['name'];
            $_SESSION['user']['phone'] = $data['phone'];

            header("Location: ?url=user/profile");
            exit;

        } 
    }

    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {

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

        if (!empty($user['profile_image'])) {
            $oldPath = __DIR__ . '/../../public/' . $user['profile_image'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $this->userService->updateProfileImage(
            $userId,
            'uploads/profile/' . $fileName
        );
    }

   
    require __DIR__ . '/../views/user/update_profile.php';
}




public function changePassword()
{
   
    if (!isset($_SESSION['user'])) {
        header("Location: ?url=user/login");
        exit;
    }

    $error  = null;
    $userId = $_SESSION['user']['id'];


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [
            'id'               => $userId,
            'old_password'     => $_POST['old_password'] ?? '',
            'new_password'     => $_POST['new_password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
        ];

        $result = $this->userService->changePassword($data, $_SESSION['user']);

        if ($result['success']) {
            
            header("Location: ?url=user/profile");
            exit;
        }

        
        $error = $result['message'];
    }

  
    require __DIR__ . '/../views/user/change_password.php';
}
}