<?php

require_once __DIR__ . '/../services/RentalLogService.php';

class RentalLogController
{
    private RentalLogService $logService;

    public function __construct()
    {
        $this->logService = new RentalLogService();
    }

    /* =========================
       USER: View My Rental Logs
       URL: ?url=rentalLog/my
       ========================= */
    public function my()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }

        $logs = $this->logService->getMyLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/myLogs.php';
    }

    /* =========================
       VENDOR: View My Tool Logs
       URL: ?url=rentalLog/owner
       ========================= */
    public function owner()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }

        $logs = $this->logService->getOwnerLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/ownerLogs.php';
    }

    /* =========================
       ADMIN: View All Rental Logs
       URL: ?url=rentalLog/admin
       ========================= */
    public function admin()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser || $currentUser['role'] !== 'ADMIN') {
            die('Unauthorized');
        }

        $logs = $this->logService->getAllLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/adminLogs.php';
    }

    /* =========================
       ADMIN: Search Rental Logs
       URL: ?url=rentalLog/search
       ========================= */
    public function search()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser || $currentUser['role'] !== 'ADMIN') {
            die('Unauthorized');
        }

        $logId    = $_GET['log_id'] ?? null;
        $fromDate = $_GET['from_date'] ?? null;
        $toDate   = $_GET['to_date'] ?? null;

        $logs = $this->logService->searchLogs(
            $currentUser,
            $logId,
            $fromDate,
            $toDate
        );

        require __DIR__ . '/../views/rentalLog/adminLogs.php';
    }

    /* =========================
       USER: Download My Logs PDF
       URL: ?url=rentalLog/downloadMyPdf
       ========================= */
    public function downloadMyPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadMyLogsPdf($currentUser);
    }

    /* =========================
       VENDOR: Download Owner Logs PDF
       URL: ?url=rentalLog/downloadOwnerPdf
       ========================= */
    public function downloadOwnerPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadOwnerLogsPdf($currentUser);
    }

    /* =========================
       ADMIN: Download All Logs PDF
       URL: ?url=rentalLog/downloadAllPdf
       ========================= */
    public function downloadAllPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadAllLogsPdf($currentUser);
    }

    /* =========================
       ADMIN: Download Search Result PDF
       URL: ?url=rentalLog/downloadSearchPdf
       ========================= */
    public function downloadSearchPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $logId    = $_GET['log_id'] ?? null;
        $fromDate = $_GET['from_date'] ?? null;
        $toDate   = $_GET['to_date'] ?? null;

        $this->logService->downloadSearchLogsPdf(
            $currentUser,
            $logId,
            $fromDate,
            $toDate
        );
    }
    
    
public function searchOwner()
{
    $currentUser = $_SESSION['user'] ?? null;

    if (!$currentUser || $currentUser['role'] !== 'VENDOR') {
        die('Unauthorized');
    }

    $logId    = $_GET['log_id'] ?? null;
    $fromDate = $_GET['from_date'] ?? null;
    $toDate   = $_GET['to_date'] ?? null;

    $logs = $this->logService->searchOwnerLogs(
        $currentUser,
        $logId,
        $fromDate,
        $toDate
    );

    require __DIR__ . '/../views/rentalLog/ownerLogs.php';
}
/* =========================
   USER: Search Own Rental Logs
   URL: ?url=rentalLog/searchMy
   ========================= */
public function searchMy()
{
    $currentUser = $_SESSION['user'] ?? null;

    if (!$currentUser || $currentUser['role'] !== 'USER') {
        die('Unauthorized');
    }

    $logId    = $_GET['log_id'] ?? null;
    $fromDate = $_GET['from_date'] ?? null;
    $toDate   = $_GET['to_date'] ?? null;

    $logs = $this->logService->searchMyLogs(
        $currentUser,
        $logId,
        $fromDate,
        $toDate
    );

    require __DIR__ . '/../views/rentalLog/myLogs.php';
}

    public function downloadOne()
{
    $currentUser = $_SESSION['user'] ?? null;
    $logId = $_GET['id'] ?? '';

    $this->logService
        ->downloadSingleLogPdf($currentUser, $logId);
}


}
