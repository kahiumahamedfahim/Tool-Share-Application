<?php

require_once __DIR__ . '/../repositories/RentRepository.php';
require_once __DIR__ . '/../repositories/ToolRepository.php';

class RentService
{
    private RentRepository $rentRepo;
    private ToolRepository $toolRepo;

    public function __construct()
    {
        $this->rentRepo = new RentRepository();
        $this->toolRepo = new ToolRepository();
    }

    /* =========================
       Create Rent Request(s)
       ========================= */
    public function createRequest(array $data, array $currentUser): array
    {
        // 🔒 Auth check
        if (empty($currentUser)) {
            return ['success' => false, 'message' => 'Login required'];
        }

        // ❌ Admin cannot rent
        if ($currentUser['role'] === 'ADMIN') {
            return ['success' => false, 'message' => 'Admin cannot rent tools'];
        }

        // 🔹 Required fields
        $required = ['tool_id', 'start_date', 'end_date', 'quantity'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
                ];
            }
        }

        // 🔹 Date validation
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            return ['success' => false, 'message' => 'Invalid rent date range'];
        }

        // 🔹 Tool check
        $tool = $this->toolRepo->getById($data['tool_id']);

        if (!$tool) {
            return ['success' => false, 'message' => 'Tool not found'];
        }

        // ❌ Owner cannot rent own tool
        if ($tool['user_id'] === $currentUser['id']) {
            return ['success' => false, 'message' => 'You cannot rent your own tool'];
        }

        // ❌ Tool unavailable
        if ($tool['status'] !== 'AVAILABLE' || $tool['quantity'] <= 0) {
            return ['success' => false, 'message' => 'Tool not available'];
        }

        // 🔹 Quantity rules
        $requestedQty = (int)$data['quantity'];

        if ($requestedQty < 1) {
            return ['success' => false, 'message' => 'Invalid quantity'];
        }

        if ($requestedQty > $tool['quantity']) {
            return ['success' => false, 'message' => 'Requested quantity exceeds availability'];
        }

        // 🔹 Duplicate pending check (per unit logic)
        if ($this->rentRepo->hasPendingRequest($tool['id'], $currentUser['id'])) {
            return [
                'success' => false,
                'message' => 'You already have a pending request for this tool'
            ];
        }

        // =========================
        // Create requests (1 unit per row)
        // =========================
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

    /* =========================
       Owner Accept Request
       ========================= */
    public function acceptRequest(string $rentId, array $currentUser): array
    {
        $rent = $this->rentRepo->getById($rentId);

        if (!$rent) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        // 🔒 Only owner
        if ($rent['owner_id'] !== $currentUser['id']) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        // 🔹 Status check
        if ($rent['status'] !== 'REQUESTED') {
            return ['success' => false, 'message' => 'Invalid request state'];
        }

        // 🔹 Tool availability
        $tool = $this->toolRepo->getById($rent['tool_id']);

        if ($tool['quantity'] <= 0) {
            return ['success' => false, 'message' => 'Tool out of stock'];
        }

        // 🔹 Update status
        $this->rentRepo->updateStatus($rentId, 'ACCEPTED');

        // 🔹 Reduce quantity
        $this->toolRepo->updateQuantity(
            $tool['id'],
            $tool['quantity'] - 1
        );

        return ['success' => true, 'message' => 'Request accepted'];
    }

    /* =========================
       Owner Reject Request
       ========================= */
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

    /* =========================
       User Cancel Request
       ========================= */
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
/* =========================
   Get My Rent Requests (User)
   ========================= */
public function getMyRentRequests(string $renterId): array
{
    // No business rule here
    // Just delegate to repository
    return $this->rentRepo->getDetailedRequestsByRenter($renterId);
}
public function getIncomingRequestsForOwner(string $ownerId): array
{
    // Thin service: no logic, just delegate
    return $this->rentRepo->getDetailedRequestsByOwner($ownerId);
}
/* =========================
   Renter: Request Tool Return
   ========================= */
public function requestReturn(string $rentId, array $currentUser): array
{
    if (empty($currentUser)) {
        return ['success' => false, 'message' => 'Login required'];
    }

    $rent = $this->rentRepo->getRentWithTool($rentId);

    if (!$rent) {
        return ['success' => false, 'message' => 'Rent not found'];
    }

    // 🔒 Only renter can request return
    if ($rent['renter_id'] !== $currentUser['id']) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    // 🔹 Only ACCEPTED can be returned
    if ($rent['status'] !== 'ACCEPTED') {
        return ['success' => false, 'message' => 'Return not allowed'];
    }

    $this->rentRepo->updateStatus($rentId, 'RETURN_REQUESTED');

    return [
        'success' => true,
        'message' => 'Return request sent to owner'
    ];
}
/* =========================
   Owner: Confirm Tool Return
   ========================= */
public function confirmReturn(string $rentId, array $currentUser): array
{
    if (empty($currentUser)) {
        return ['success' => false, 'message' => 'Login required'];
    }

    $rent = $this->rentRepo->getRentWithTool($rentId);

    if (!$rent) {
        return ['success' => false, 'message' => 'Rent not found'];
    }

    // 🔒 Only owner can confirm
    if ($rent['owner_id'] !== $currentUser['id']) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    // 🔹 Only RETURN_REQUESTED can be confirmed
    if ($rent['status'] !== 'RETURN_REQUESTED') {
        return ['success' => false, 'message' => 'Invalid return state'];
    }

    // 🔹 Update status
    $this->rentRepo->updateStatus($rentId, 'RETURNED');

    // 🔹 Increase tool quantity
    $tool = $this->toolRepo->getById($rent['tool_id']);

    $this->toolRepo->updateQuantity(
        $tool['id'],
        $tool['quantity'] + 1
    );

    return [
        'success' => true,
        'message' => 'Tool returned successfully'
    ];
}
/* =========================
   Admin: Get All Rent Requests
   ========================= */
public function getAllRentRequestsForAdmin(): array
{
    // Admin is read-only here
    // Just delegate to repository
    return $this->rentRepo->getAllDetailedRequests();
}



}
