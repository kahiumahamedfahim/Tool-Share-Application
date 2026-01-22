<?php

require_once __DIR__ . '/../../config/Database.php';

class RentalLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

public function create(array $data): bool
{
    $sql = "
        INSERT INTO rental_logs (
            id,
            rent_id,
            tool_id,
            owner_id,
            renter_id,
            rent_start_date,
            rent_end_date,
            return_confirmed_date,
            total_amount,
            created_at
        ) VALUES (
            :id,
            :rent_id,
            :tool_id,
            :owner_id,
            :renter_id,
            :rent_start,
            :rent_end,
            :return_date,
            :total_amount,
            NOW()
        )
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id'           => $data['id'],
        'rent_id'      => $data['rent_id'],
        'tool_id'      => $data['tool_id'],
        'owner_id'     => $data['owner_id'],
        'renter_id'    => $data['renter_id'],
        'rent_start'   => $data['rent_start'],
        'rent_end'     => $data['rent_end'],
        'return_date'  => $data['return_date'],
        'total_amount' => $data['total_amount']
    ]);
}


    /* =========================
       Get Logs by Renter (User)
       ========================= */
   public function getByRenter(string $renterId): array
{
    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            renter.name AS renter_name,
            owner.name AS owner_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        WHERE rl.renter_id = ?
        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$renterId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* =========================
       Get Logs by Owner (Vendor)
       ========================= */
    public function getByOwner(string $ownerId): array
{
    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            renter.name AS renter_name,
            owner.name AS owner_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        WHERE rl.owner_id = ?
        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$ownerId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /* =========================
       Admin: Get All Logs
       ========================= */
    public function getAll(): array
{
    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            owner.name AS owner_name,
            renter.name AS renter_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* =========================
       Admin: Search Logs
       ========================= */
public function search(
    ?string $logId,
    ?string $fromDate,
    ?string $toDate
): array {
    $conditions = [];
    $params = [];

    if (!empty($logId)) {
        $conditions[] = "rl.id LIKE ?";
        $params[] = "%{$logId}%";
    }

    if (!empty($fromDate) && !empty($toDate)) {
        $conditions[] = "DATE(rl.return_confirmed_date) BETWEEN ? AND ?";
        $params[] = $fromDate;
        $params[] = $toDate;
    }

    $where = '';
    if (!empty($conditions)) {
        $where = 'WHERE ' . implode(' AND ', $conditions);
    }

    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            owner.name AS owner_name,
            renter.name AS renter_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        {$where}
        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getById(string $logId): ?array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM rental_logs WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$logId]);

    $log = $stmt->fetch(PDO::FETCH_ASSOC);
    return $log ?: null;
}
/* =========================
   Vendor: Search Own Logs
   ========================= */
public function searchByOwner(
    string $ownerId,
    ?string $logId,
    ?string $fromDate,
    ?string $toDate
): array {
    $conditions = ['rl.owner_id = ?'];
    $params = [$ownerId];

    if (!empty($logId)) {
        $conditions[] = 'rl.id LIKE ?';
        $params[] = "%{$logId}%";
    }

    if (!empty($fromDate) && !empty($toDate)) {
        $conditions[] = 'DATE(rl.return_confirmed_date) BETWEEN ? AND ?';
        $params[] = $fromDate;
        $params[] = $toDate;
    }

    $where = 'WHERE ' . implode(' AND ', $conditions);

    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            owner.name AS owner_name,
            renter.name AS renter_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        {$where}
        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/* =========================
   User: Search Own Logs
   ========================= */
public function searchByRenter(
    string $renterId,
    ?string $logId,
    ?string $fromDate,
    ?string $toDate
): array {
    $conditions = ['rl.renter_id = ?'];
    $params = [$renterId];

    if (!empty($logId)) {
        $conditions[] = 'rl.id LIKE ?';
        $params[] = "%{$logId}%";
    }

    if (!empty($fromDate) && !empty($toDate)) {
        $conditions[] = 'DATE(rl.return_confirmed_date) BETWEEN ? AND ?';
        $params[] = $fromDate;
        $params[] = $toDate;
    }

    $where = 'WHERE ' . implode(' AND ', $conditions);

    $sql = "
        SELECT
            rl.id,
            rl.rent_start_date,
            rl.rent_end_date,
            rl.return_confirmed_date,
            rl.total_amount,

            t.name AS tool_name,
            owner.name AS owner_name,
            renter.name AS renter_name

        FROM rental_logs rl
        JOIN tools t ON t.id = rl.tool_id
        JOIN users owner ON owner.id = rl.owner_id
        JOIN users renter ON renter.id = rl.renter_id

        {$where}
        ORDER BY rl.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
