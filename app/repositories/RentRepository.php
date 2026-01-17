<?php

require_once __DIR__ . '/../../config/Database.php';

class RentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* =========================
       Create Rent Request
       ========================= */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO rent_requests (
                    id,
                    tool_id,
                    owner_id,
                    renter_id,
                    start_date,
                    end_date,
                    status,
                    created_at
                ) VALUES (
                    :id,
                    :tool_id,
                    :owner_id,
                    :renter_id,
                    :start_date,
                    :end_date,
                    :status,
                    NOW()
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'          => $data['id'],
            'tool_id'     => $data['tool_id'],
            'owner_id'    => $data['owner_id'],
            'renter_id'   => $data['renter_id'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'status'      => $data['status']
        ]);
    }

    /* =========================
       Check Pending Request
       ========================= */
    public function hasPendingRequest(string $toolId, string $renterId): bool
    {
        $sql = "SELECT COUNT(*) FROM rent_requests
                WHERE tool_id = ?
                  AND renter_id = ?
                  AND status = 'REQUESTED'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$toolId, $renterId]);

        return $stmt->fetchColumn() > 0;
    }

    /* =========================
       Get Requests by Owner
       ========================= */
    public function getByOwner(string $ownerId): array
    {
        $sql = "SELECT *
                FROM rent_requests
                WHERE owner_id = ?
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ownerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       Get Requests by Renter
       ========================= */
    public function getByRenter(string $renterId): array
    {
        $sql = "SELECT *
                FROM rent_requests
                WHERE renter_id = ?
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$renterId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       Admin: Get All Requests
       ========================= */
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM rent_requests
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       Get Single Request
       ========================= */
    public function getById(string $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM rent_requests WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);

        $rent = $stmt->fetch(PDO::FETCH_ASSOC);

        return $rent ?: null;
    }

    /* =========================
       Update Status
       ========================= */
    public function updateStatus(string $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE rent_requests
             SET status = ?, updated_at = NOW()
             WHERE id = ?"
        );

        $stmt->execute([$status, $id]);

        return $stmt->rowCount() > 0;
    }
    
}
