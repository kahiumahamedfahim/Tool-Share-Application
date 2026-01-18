<?php

require_once __DIR__ . '/../../config/Database.php';

class ToolRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* =========================
       Create Tool
       ========================= */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tools (
                    id,
                    user_id,
                    category_id,
                    name,
                    description,
                    price_per_day,
                    quantity,
                    location,
                    status,
                    created_at
                ) VALUES (
                    :id,
                    :user_id,
                    :category_id,
                    :name,
                    :description,
                    :price_per_day,
                    :quantity,
                    :location,
                    :status,
                    NOW()
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'            => $data['id'],
            'user_id'       => $data['user_id'],
            'category_id'   => $data['category_id'],
            'name'          => $data['name'],
            'description'   => $data['description'],
            'price_per_day' => $data['price_per_day'],
            'quantity'      => $data['quantity'],
            'location'      => $data['location'],
            'status'        => $data['status']
        ]);
    }

    /* =========================
       Get Tool By ID
       ========================= */
   

    public function getByUser(string $userId): array
{
    $sql = "
        SELECT
            t.*,
            (
                SELECT ti.image_path
                FROM tool_images ti
                WHERE ti.tool_id = t.id
                ORDER BY ti.id ASC
                LIMIT 1
            ) AS image
        FROM tools t
        WHERE t.user_id = ?
        ORDER BY t.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /* =========================
       Update Tool Quantity
       ========================= */
    public function updateQuantity(string $toolId, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE tools SET quantity = ? WHERE id = ?"
        );
        $stmt->execute([$quantity, $toolId]);

        return $stmt->rowCount() > 0;
    }

    public function updateStatus(string $toolId, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE tools SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $toolId]);

        return $stmt->rowCount() > 0;
    }
    /* =========================
   Get All Available Tools
   ========================= */
public function getAllAvailable(): array
{
    $sql = "
        SELECT 
            t.id,
            t.user_id,
            t.name,
            t.price_per_day,
            t.description,
            t.location,
            t.quantity,
            t.status,
            u.name AS owner_name,
            (
                SELECT ti.image_path 
                FROM tool_images ti 
                WHERE ti.tool_id = t.id 
                ORDER BY ti.id ASC 
                LIMIT 1
            ) AS image
        FROM tools t
        JOIN users u ON u.id = t.user_id
        WHERE t.status = 'AVAILABLE'
        ORDER BY t.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /* =========================
   Get All Available Tools Except Own
   ========================= */
public function getAllAvailableExceptUser(string $userId): array
{
    $sql = "
        SELECT 
            t.id,
            t.user_id,
            t.name,
            t.price_per_day,
             t.description,
            t.location,
            t.quantity,
            t.status,
            u.name AS owner_name,
            (
                SELECT ti.image_path 
                FROM tool_images ti 
                WHERE ti.tool_id = t.id 
                ORDER BY ti.id ASC 
                LIMIT 1
            ) AS image
        FROM tools t
        JOIN users u ON u.id = t.user_id
        WHERE t.status = 'AVAILABLE'
          AND t.user_id != ?
        ORDER BY t.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/* =========================
   Get Tool Details By ID
   ========================= */
// ToolRepository.php
public function getDetailsById(string $toolId): ?array
{
    $sql = "
        SELECT
            t.id,
            t.user_id,
            t.category_id,
            t.name,
            t.description,
            t.price_per_day,
            t.quantity,
            t.location,
            t.status,
            t.created_at,
            u.name  AS owner_name,
                u.email AS owner_email,
                u.phone AS owner_phone,
                
                u.profile_image AS owner_image
        FROM tools t
        JOIN users u ON u.id = t.user_id
        WHERE t.id = ?
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$toolId]);

    $tool = $stmt->fetch(PDO::FETCH_ASSOC);

    return $tool ?: null;
}
public function getImagesByToolId(string $toolId): array
{
    $sql = "
        SELECT image_path
        FROM tool_images
        WHERE tool_id = ?
        ORDER BY id ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$toolId]);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
/* =========================
   Get Tool By ID
   ========================= */
public function getById(string $id): ?array
{
    $sql = "SELECT 
                id,
                user_id,
                category_id,
                name,
                description,
                price_per_day,
                quantity,
                location,
                status,
                created_at
            FROM tools
            WHERE id = ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    $tool = $stmt->fetch(PDO::FETCH_ASSOC);

    return $tool ?: null;
}

public function delete(string $toolId): bool
{
    $stmt = $this->db->prepare(
        "DELETE FROM tools WHERE id = ?"
    );
    $stmt->execute([$toolId]);

    return $stmt->rowCount() > 0;
}
public function update(string $toolId, array $data): bool
{
    $sql = "
        UPDATE tools SET
            name = :name,
            price_per_day = :price,
            quantity = :qty,
            location = :location,
            description = :description
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'name' => $data['name'],
        'price' => $data['price_per_day'],
        'qty' => $data['quantity'],
        'location' => $data['location'],
        'description' => $data['description'],
        'id' => $toolId
    ]);
}



}
