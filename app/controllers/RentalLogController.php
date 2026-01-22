<?php

require_once __DIR__ . '/../services/RentalLogService.php';

class RentalLogController
{
    private RentalLogService $logService;

    public function __construct()
    {
        $this->logService = new RentalLogService();
    }


    public function my()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }

        $logs = $this->logService->getMyLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/myLogs.php';
    }

    public function owner()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            die('Unauthorized');
        }

        $logs = $this->logService->getOwnerLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/ownerLogs.php';
    }

    public function admin()
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser || $currentUser['role'] !== 'ADMIN') {
            die('Unauthorized');
        }

        $logs = $this->logService->getAllLogs($currentUser);

        require __DIR__ . '/../views/rentalLog/adminLogs.php';
    }


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

 
    public function downloadMyPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadMyLogsPdf($currentUser);
    }


    public function downloadOwnerPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadOwnerLogsPdf($currentUser);
    }

    public function downloadAllPdf()
    {
        $currentUser = $_SESSION['user'] ?? null;

        $this->logService->downloadAllLogsPdf($currentUser);
    }

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
