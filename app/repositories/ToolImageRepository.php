<?php

require_once __DIR__ . '/../../config/Database.php';

class ToolImageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* =========================
       Save Tool Image
       ========================= */
    public function add(string $id, string $toolId, string $imagePath): bool
    {
        $sql = "INSERT INTO tool_images (
                    id,
                    tool_id,
                    image_path
                ) VALUES (
                    :id,
                    :tool_id,
                    :image_path
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'         => $id,
            'tool_id'    => $toolId,
            'image_path' => $imagePath
        ]);
    }

    /* =========================
       Get Images by Tool
       ========================= */
    public function getByToolId(string $toolId): array
    {
        $stmt = $this->db->prepare(
            "SELECT image_path
             FROM tool_images
             WHERE tool_id = ?"
        );
        $stmt->execute([$toolId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete(string $imageId): bool
{
    $stmt = $this->db->prepare(
        "DELETE FROM tool_images WHERE id = ?"
    );
    $stmt->execute([$imageId]);

    return $stmt->rowCount() > 0;
}



}
