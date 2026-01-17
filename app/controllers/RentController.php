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
    public function ownerRequests()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }
$requests = $this->rentService
    ->getRequestsForOwner($currentUser['id']);


        require __DIR__ . '/../views/rent/owner_requests.php';
    }

    /* =========================
       User: View My Requests
       ========================= */
    public function myRequests()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }

        $requests = $this->rentService
    ->getRequestsForOwner($currentUser['id']);


        require __DIR__ . '/../views/rent/my_requests.php';
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
}
