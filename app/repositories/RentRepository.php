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
    /* =========================
   Get Rent Requests for Renter (UI Ready)
   ========================= */
public function getDetailedRequestsByRenter(string $renterId): array
{
    $sql = "
        SELECT
            rr.id AS rent_id,
            rr.start_date,
            rr.end_date,
            rr.status,

            t.name AS tool_name,

            (
                SELECT ti.image_path
                FROM tool_images ti
                WHERE ti.tool_id = t.id
                ORDER BY ti.id ASC
                LIMIT 1
            ) AS tool_image,

            u.name AS owner_name,
            u.profile_image AS owner_image

        FROM rent_requests rr
        JOIN tools t ON t.id = rr.tool_id
        JOIN users u ON u.id = rr.owner_id

        WHERE rr.renter_id = ?
        ORDER BY rr.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$renterId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/* =========================
   Get Incoming Rent Requests for Owner (UI Ready)
   ========================= */
public function getDetailedRequestsByOwner(string $ownerId): array
{
    $sql = "
        SELECT
            rr.id AS rent_id,
            rr.start_date,
            rr.end_date,
            rr.status,

            t.name AS tool_name,

            (
                SELECT ti.image_path
                FROM tool_images ti
                WHERE ti.tool_id = t.id
                ORDER BY ti.id ASC
                LIMIT 1
            ) AS tool_image,

            u.name AS renter_name,
            u.profile_image AS renter_image

        FROM rent_requests rr
        JOIN tools t ON t.id = rr.tool_id
        JOIN users u ON u.id = rr.renter_id

        WHERE rr.owner_id = ?
        ORDER BY rr.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$ownerId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/* =========================
   Get Rent With Tool Info (For Return)
   ========================= */
public function getRentWithTool(string $rentId): ?array
{
    $sql = "
        SELECT
            rr.id,
            rr.tool_id,
            rr.owner_id,
            rr.renter_id,
            rr.status
        FROM rent_requests rr
        WHERE rr.id = ?
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$rentId]);

    $rent = $stmt->fetch(PDO::FETCH_ASSOC);

    return $rent ?: null;
}
/* =========================
   Admin: Get All Rent Requests (UI Ready with Images)
   ========================= */
public function getAllDetailedRequests(): array
{
    $sql = "
        SELECT
            rr.id AS rent_id,
            rr.start_date,
            rr.end_date,
            rr.status,
            rr.created_at,

            t.name AS tool_name,

            (
                SELECT ti.image_path
                FROM tool_images ti
                WHERE ti.tool_id = t.id
                ORDER BY ti.id ASC
                LIMIT 1
            ) AS tool_image,

            owner.name AS owner_name,
            owner.profile_image AS owner_image,

            renter.name AS renter_name,
            renter.profile_image AS renter_image

        FROM rent_requests rr
        JOIN tools t ON t.id = rr.tool_id
        JOIN users owner ON owner.id = rr.owner_id
        JOIN users renter ON renter.id = rr.renter_id

        ORDER BY rr.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
}
