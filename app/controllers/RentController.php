<?php

require_once __DIR__ . '/../services/RentService.php';

class RentController
{
    private RentService $rentService;

    public function __construct()
    {
        $this->rentService = new RentService();
    }

    /* =========================
       Create Rent Request
       ========================= */
    public function request()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $currentUser = $_SESSION['user'] ?? null;

        $data = [
            'tool_id'    => $_POST['tool_id'] ?? '',
            'start_date' => $_POST['start_date'] ?? '',
            'end_date'   => $_POST['end_date'] ?? '',
            'quantity'   => $_POST['quantity'] ?? 1
        ];

        $result = $this->rentService->createRequest($data, $currentUser);

        if ($result['success']) {
            header('Location: ?url=rent/myRequests');
            exit;
        }

        echo "<script>alert('{$result['message']}');history.back();</script>";
    }

    /* =========================
       Owner: View Incoming Requests
       ========================= */
    /* =========================
   Owner: View Incoming Rent Requests
   URL: ?url=rent/ownerRequests
   ========================= */
public function ownerRequests()
{
    $currentUser = $_SESSION['user'] ?? null;

    // 🔒 Login required
    if (!$currentUser) {
        die('Unauthorized');
    }

    // 🔒 Only owner / vendor (NOT admin)
    if ($currentUser['role'] === 'ADMIN') {
        die('Access denied');
    }

    // 🔹 Get incoming requests for this owner
    $requests = $this->rentService
        ->getIncomingRequestsForOwner($currentUser['id']);

    // 🔹 Load view (next step)
    require __DIR__ . '/../views/rent/incomingRequest.php';
}


    /* =========================
       User: View My Requests
       ========================= */
    public function myRequests()
{
    $currentUser = $_SESSION['user'] ?? null;

    // 🔒 Login required
    if (!$currentUser) {
        die('Unauthorized');
    }

    // 🔹 Call service (user = renter)
    $requests = $this->rentService
        ->getMyRentRequests($currentUser['id']);

    // 🔹 Load view (next step)
    require __DIR__ . '/../views/rent/myRequest.php';
}

    /* =========================
       Owner Accept
       ========================= */
    public function accept()
    {
        $currentUser = $_SESSION['user'] ?? null;
        $rentId = $_GET['id'] ?? '';

        $result = $this->rentService->acceptRequest($rentId, $currentUser);

        echo "<script>alert('{$result['message']}');history.back();</script>";
    }

    /* =========================
       Owner Reject
       ========================= */
    public function reject()
    {
        $currentUser = $_SESSION['user'] ?? null;
        $rentId = $_GET['id'] ?? '';

        $result = $this->rentService->rejectRequest($rentId, $currentUser);

        echo "<script>alert('{$result['message']}');history.back();</script>";
    }

    /* =========================
       User Cancel
       ========================= */
    public function cancel()
    {
        $currentUser = $_SESSION['user'] ?? null;
        $rentId = $_GET['id'] ?? '';

        $result = $this->rentService->cancelRequest($rentId, $currentUser);

        echo "<script>alert('{$result['message']}');history.back();</script>";
    }
    /* =========================
   Renter: Request Return
   ========================= */
public function requestReturn()
{
    $currentUser = $_SESSION['user'] ?? null;
    $rentId = $_GET['id'] ?? '';

    if (!$rentId) {
        die('Invalid rent request');
    }

    $result = $this->rentService
        ->requestReturn($rentId, $currentUser);

    echo "<script>
        alert('{$result['message']}');
        history.back();
    </script>";
}
/* =========================
   Owner: Confirm Return
   ========================= */
public function confirmReturn()
{
    $currentUser = $_SESSION['user'] ?? null;
    $rentId = $_GET['id'] ?? '';

    if (!$rentId) {
        die('Invalid return request');
    }

    $result = $this->rentService
        ->confirmReturn($rentId, $currentUser);

    echo "<script>
        alert('{$result['message']}');
        history.back();
    </script>";
}
/* =========================
   Admin: View All Rent Requests
   URL: ?url=rent/adminRequests
   ========================= */
public function adminRequests()
{
    $currentUser = $_SESSION['user'] ?? null;

    // 🔒 Login required
    if (!$currentUser) {
        die('Unauthorized');
    }

    // 🔒 Admin only
    if ($currentUser['role'] !== 'ADMIN') {
        die('Access denied');
    }

    // 🔹 Get all rent requests (system view)
    $requests = $this->rentService
        ->getAllRentRequestsForAdmin();

    // 🔹 Load admin view (next step)
    require __DIR__ . '/../views/rent/adminRequest.php';
}


}
