<?php

require_once __DIR__ . '/../../config/Database.php';

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO users 
        (
            id, name, email, phone, nid_number,
            password, profile_image, role,
            shop_number, business_card_no
        )
        VALUES
        (
            :id, :name, :email, :phone, :nid_number,
            :password, :profile_image, :role,
            :shop_number, :business_card_no
        )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'               => $data['id'],
            'name'             => $data['name'],
            'email'            => $data['email'],
            'phone'            => $data['phone'],
            'nid_number'       => $data['nid_number'],
            'password'         => $data['password'],
            'profile_image'    => $data['profile_image'],
            'role'             => $data['role'],
            'shop_number'      => $data['shop_number'],
            'business_card_no' => $data['business_card_no'],
           
        ]);
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function findByEmailOrPhone(string $value)
{
    $sql = "SELECT * 
            FROM users 
            WHERE email = :value 
               OR phone = :value
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':value', $value);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getUsersByRole(string $role): array
{
    $sql = "SELECT 
                id,
                name,
                email,
                phone,
                shop_number,
                business_card_no,
                role,
                status,
                created_at
            FROM users
            WHERE role = ?
            ORDER BY created_at DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$role]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function createAdmin(array $data): bool
{
    $sql = "INSERT INTO users 
            (
                id,
                name,
                email,
                phone,
                password,
                role,
                status,
                created_at
            )
            VALUES
            (
                :id,
                :name,
                :email,
                :phone,
                :password,
                'ADMIN',
                'ACTIVE',
                NOW()
            )";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id'       => $data['id'],
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'],
        'password' => $data['password']
    ]);
}
public function getAdminsExcept(string $adminId): array
{
    $sql = "SELECT *
            FROM users
            WHERE role = 'ADMIN'
            AND id != :adminId";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['adminId' => $adminId]);

    return $stmt->fetchAll();
}
public function deleteAdminById(string $adminId): bool
{
    $sql = "DELETE FROM users WHERE id = ? AND role = 'ADMIN'";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([$adminId]);
}
public function getUserById(string $id): ?array
{
    $sql = "SELECT 
                id,
                name,
                email,
                phone,
                nid_number,
                profile_image,
                role,
                shop_number,
                business_card_no,
                status,
                created_at
            FROM users
            WHERE id = ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}
public function updateStatus(string $userId, string $status): bool
{
    $sql = "UPDATE users 
            SET status = :status
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'status' => $status,
        'id'     => $userId
    ]);

    return $stmt->rowCount() > 0;
}
public function isPhoneExists(string $phone): bool
{
    $stmt = $this->db->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
    $stmt->execute([$phone]);
    return (bool) $stmt->fetch();
}

public function isNidExists(string $nid): bool
{
    $stmt = $this->db->prepare("SELECT id FROM users WHERE nid_number = ? LIMIT 1");
    $stmt->execute([$nid]);
    return (bool) $stmt->fetch();
}

public function isVendorFieldExists(string $field, string $value): bool
{
    if (!in_array($field, ['shop_number', 'business_card_no'])) {
        return false;
    }

    $stmt = $this->db->prepare("SELECT id FROM users WHERE {$field} = ? LIMIT 1");
    $stmt->execute([$value]);
    return (bool) $stmt->fetch();
}
public function updateProfileBasic(array $data): bool
{
    $sql = "UPDATE users SET
                name = :name,
                phone = :phone,
                shop_number = :shop_number,
                business_card_no = :business_card_no
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'name'             => $data['name'],
        'phone'            => $data['phone'],
        'shop_number'      => $data['shop_number'],
        'business_card_no' => $data['business_card_no'],
        'id'               => $data['id']
    ]);

    return $stmt->rowCount() > 0;
}
public function updateProfileImage(string $userId, ?string $imagePath): bool
{
    $stmt = $this->db->prepare(
        "UPDATE users SET profile_image = ? WHERE id = ?"
    );
    return $stmt->execute([$imagePath, $userId]);
}
public function isPhoneExistsExceptUser(string $phone, string $userId): bool
{
    $sql = "SELECT id 
            FROM users 
            WHERE phone = ? AND id != ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$phone, $userId]);

    return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}
public function updatePassword(string $userId, string $hashedPassword): bool
{
    $sql = "UPDATE users 
            SET password = :password
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'password' => $hashedPassword,
        'id'       => $userId
    ]);

    return $stmt->rowCount() > 0;
}

public function getUserPasswordById(string $id): ?string
{
    $stmt = $this->db->prepare(
        "SELECT password FROM users WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['password'] ?? null;
}


}

