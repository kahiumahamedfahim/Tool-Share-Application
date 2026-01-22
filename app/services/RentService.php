<?php

require_once __DIR__ . '/../repositories/RentRepository.php';
require_once __DIR__ . '/../repositories/ToolRepository.php';
require_once __DIR__ . '/../repositories/RentalLogRepository.php';


class RentService
{
    private RentRepository $rentRepo;
    private ToolRepository $toolRepo;
    private RentalLogRepository $logRepo;
    public function __construct()
    {
        $this->rentRepo = new RentRepository();
        $this->toolRepo = new ToolRepository();
        $this->logRepo = new RentalLogRepository();
    }

    public function createRequest(array $data, array $currentUser): array
    {
       
        if (empty($currentUser)) {
            return ['success' => false, 'message' => 'Login required'];
        }

        if ($currentUser['role'] === 'ADMIN') {
            return ['success' => false, 'message' => 'Admin cannot rent tools'];
        }

    
        $required = ['tool_id', 'start_date', 'end_date', 'quantity'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
                ];
            }
        }

        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            return ['success' => false, 'message' => 'Invalid rent date range'];
        }

       
        $tool = $this->toolRepo->getById($data['tool_id']);

        if (!$tool) {
            return ['success' => false, 'message' => 'Tool not found'];
        }

  
        if ($tool['user_id'] === $currentUser['id']) {
            return ['success' => false, 'message' => 'You cannot rent your own tool'];
        }

   
        if ($tool['status'] !== 'AVAILABLE' || $tool['quantity'] <= 0) {
            return ['success' => false, 'message' => 'Tool not available'];
        }

        $requestedQty = (int)$data['quantity'];

        if ($requestedQty < 1) {
            return ['success' => false, 'message' => 'Invalid quantity'];
        }

        if ($requestedQty > $tool['quantity']) {
            return ['success' => false, 'message' => 'Requested quantity exceeds availability'];
        }

        if ($this->rentRepo->hasPendingRequest($tool['id'], $currentUser['id'])) {
            return [
                'success' => false,
                'message' => 'You already have a pending request for this tool'
            ];
        }

        for ($i = 1; $i <= $requestedQty; $i++) {

            $rent = [
                'id'         => uniqid('rent_', true),
                'tool_id'    => $tool['id'],
                'owner_id'   => $tool['user_id'],
                'renter_id'  => $currentUser['id'],
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'status'     => 'REQUESTED'
            ];

            $this->rentRepo->create($rent);
        }

        return [
            'success' => true,
            'message' => 'Rent request sent successfully'
        ];
    }


    public function acceptRequest(string $rentId, array $currentUser): array
    {
        $rent = $this->rentRepo->getById($rentId);

        if (!$rent) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        if ($rent['owner_id'] !== $currentUser['id']) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

      
        if ($rent['status'] !== 'REQUESTED') {
            return ['success' => false, 'message' => 'Invalid request state'];
        }

        $tool = $this->toolRepo->getById($rent['tool_id']);

        if ($tool['quantity'] <= 0) {
            return ['success' => false, 'message' => 'Tool out of stock'];
        }

        // 🔹 Update status
        $this->rentRepo->updateStatus($rentId, 'ACCEPTED');

        $this->toolRepo->updateQuantity(
            $tool['id'],
            $tool['quantity'] - 1
        );

        return ['success' => true, 'message' => 'Request accepted'];
    }

   
    public function rejectRequest(string $rentId, array $currentUser): array
    {
        $rent = $this->rentRepo->getById($rentId);

        if (!$rent) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        if ($rent['owner_id'] !== $currentUser['id']) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($rent['status'] !== 'REQUESTED') {
            return ['success' => false, 'message' => 'Invalid request state'];
        }

        $this->rentRepo->updateStatus($rentId, 'REJECTED');

        return ['success' => true, 'message' => 'Request rejected'];
    }

    public function cancelRequest(string $rentId, array $currentUser): array
    {
        $rent = $this->rentRepo->getById($rentId);

        if (!$rent) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        if ($rent['renter_id'] !== $currentUser['id']) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($rent['status'] !== 'REQUESTED') {
            return ['success' => false, 'message' => 'Cannot cancel now'];
        }

        $this->rentRepo->updateStatus($rentId, 'CANCELLED');

        return ['success' => true, 'message' => 'Request cancelled'];
    }
    public function getRequestsForOwner(string $ownerId): array
{
    return $this->rentRepo->getByOwner($ownerId);
}
public function getRequestsForRenter(string $renterId): array
{
    return $this->rentRepo->getByRenter($renterId);
}

public function getMyRentRequests(string $renterId): array
{
    
    return $this->rentRepo->getDetailedRequestsByRenter($renterId);
}
public function getIncomingRequestsForOwner(string $ownerId): array
{
   
    return $this->rentRepo->getDetailedRequestsByOwner($ownerId);
}

public function requestReturn(string $rentId, array $currentUser): array
{
    if (empty($currentUser)) {
        return ['success' => false, 'message' => 'Login required'];
    }

    $rent = $this->rentRepo->getRentWithTool($rentId);

    if (!$rent) {
        return ['success' => false, 'message' => 'Rent not found'];
    }

 
    if ($rent['renter_id'] !== $currentUser['id']) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    if ($rent['status'] !== 'ACCEPTED') {
        return ['success' => false, 'message' => 'Return not allowed'];
    }

    $this->rentRepo->updateStatus($rentId, 'RETURN_REQUESTED');

    return [
        'success' => true,
        'message' => 'Return request sent to owner'
    ];
}
public function confirmReturn(string $rentId, array $currentUser): array
{
    if (empty($currentUser)) {
        return ['success' => false, 'message' => 'Login required'];
    }

    $rent = $this->rentRepo->getRentWithTool($rentId);

    if (!$rent) {
        return ['success' => false, 'message' => 'Rent not found'];
    }

   
    if ($rent['owner_id'] !== $currentUser['id']) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    if ($rent['status'] !== 'RETURN_REQUESTED') {
        return ['success' => false, 'message' => 'Invalid return state'];
    }

   
    $this->rentRepo->updateStatus($rentId, 'RETURNED');

  
    $tool = $this->toolRepo->getById($rent['tool_id']);

    $this->toolRepo->updateQuantity(
        $tool['id'],
        $tool['quantity'] + 1
    );


    // Rent duration (days)
    $rentDays = (
        strtotime($rent['end_date']) -
        strtotime($rent['start_date'])
    ) / (60 * 60 * 24) + 1;

    $totalAmount = $rentDays * $tool['price_per_day'];

    $this->logRepo->create([
        'id'            => uniqid('log_'),
        'rent_id'       => $rent['id'],
        'tool_id'       => $rent['tool_id'],
        'owner_id'      => $rent['owner_id'],
        'renter_id'     => $rent['renter_id'],
        'rent_start'    => $rent['start_date'],
        'rent_end'      => $rent['end_date'],
        'return_date'   => date('Y-m-d'),
        'total_amount'  => $totalAmount
    ]);

    return [
        'success' => true,
        'message' => 'Tool returned successfully'
    ];
}


public function getAllRentRequestsForAdmin(): array
{
    
    return $this->rentRepo->getAllDetailedRequests();
}



}
