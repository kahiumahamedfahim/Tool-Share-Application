<?php

require_once __DIR__ . '/../../config/Database.php';

class CategoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(string $id, string $name): bool
    {
        $sql = "INSERT INTO categories (id, name)
                VALUES (:id, :name)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'   => $id,
            'name' => $name
        ]);
    }

    /* =========================
       Get All Categories
       ========================= */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT id, name, status, created_at
             FROM categories
             ORDER BY name ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       Get Only Active Categories
       ========================= */
    public function getActive(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name
             FROM categories
             WHERE status = 'ACTIVE'
             ORDER BY name ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       Find Category by Name
       ========================= */
    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM categories WHERE name = ? LIMIT 1"
        );
        $stmt->execute([$name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /* =========================
       Update Status (ACTIVE / INACTIVE)
       ========================= */
    public function updateStatus(string $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $id]);

        return $stmt->rowCount() > 0;
    }

    /* =========================
       Delete Category (Hard delete)
       ========================= */
    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }
}
