<?php

require_once __DIR__ . '/../repositories/RentalLogRepository.php';
require_once __DIR__ . '/PdfService.php';

class RentalLogService
{
    private RentalLogRepository $logRepo;
    private PdfService $pdfService;

    public function __construct()
    {
        $this->logRepo = new RentalLogRepository();
        $this->pdfService = new PdfService();
    }


    public function getMyLogs(array $currentUser): array
    {
        if (empty($currentUser)) {
            return [];
        }

        return $this->logRepo->getByRenter($currentUser['id']);
    }

       public function getOwnerLogs(array $currentUser): array
    {
        if (empty($currentUser)) {
            return [];
        }

        return $this->logRepo->getByOwner($currentUser['id']);
    }

    public function getAllLogs(array $currentUser): array
    {
        if (empty($currentUser) || $currentUser['role'] !== 'ADMIN') {
            return [];
        }

        return $this->logRepo->getAll();
    }

    public function searchLogs(
        array $currentUser,
        ?string $logId,
        ?string $fromDate,
        ?string $toDate
    ): array {
        if (empty($currentUser) || $currentUser['role'] !== 'ADMIN') {
            return [];
        }

        return $this->logRepo->search($logId, $fromDate, $toDate);
    }

    public function searchMyLogs(
        array $currentUser,
        ?string $logId,
        ?string $fromDate,
        ?string $toDate
    ): array {
        if (empty($currentUser)) {
            return [];
        }

        return $this->logRepo->searchByRenter(
            $currentUser['id'],
            $logId,
            $fromDate,
            $toDate
        );
    }


    public function searchOwnerLogs(
        array $currentUser,
        ?string $logId,
        ?string $fromDate,
        ?string $toDate
    ): array {
        if (empty($currentUser)) {
            return [];
        }

        return $this->logRepo->searchByOwner(
            $currentUser['id'],
            $logId,
            $fromDate,
            $toDate
        );
    }

    public function downloadMyLogsPdf(array $currentUser): void
    {
        if (empty($currentUser)) {
            die('Unauthorized');
        }

        $logs = $this->logRepo->getByRenter($currentUser['id']);
        $this->pdfService->generateRentalLogPdf($logs);
    }


    public function downloadOwnerLogsPdf(array $currentUser): void
    {
        if (empty($currentUser)) {
            die('Unauthorized');
        }

        $logs = $this->logRepo->getByOwner($currentUser['id']);
        $this->pdfService->generateRentalLogPdf($logs);
    }

   
    public function downloadAllLogsPdf(array $currentUser): void
    {
        if (empty($currentUser) || $currentUser['role'] !== 'ADMIN') {
            die('Unauthorized');
        }

        $logs = $this->logRepo->getAll();
        $this->pdfService->generateRentalLogPdf($logs);
    }


    public function downloadSearchLogsPdf(
        array $currentUser,
        ?string $logId,
        ?string $fromDate,
        ?string $toDate
    ): void {
        if (empty($currentUser) || $currentUser['role'] !== 'ADMIN') {
            die('Unauthorized');
        }

        $logs = $this->logRepo->search($logId, $fromDate, $toDate);
        $this->pdfService->generateRentalLogPdf($logs);
    }

      public function downloadSingleLogPdf(array $currentUser, string $logId): void
    {
        if (empty($currentUser)) {
            die('Unauthorized');
        }

        $log = $this->logRepo->getById($logId);

        if (!$log) {
            die('Log not found');
        }

        if ($currentUser['role'] !== 'ADMIN') {
            if (
                $log['renter_id'] !== $currentUser['id'] &&
                $log['owner_id'] !== $currentUser['id']
            ) {
                die('Unauthorized');
            }
        }

        $this->pdfService->generateInvoicePdf($log);
    }
}
