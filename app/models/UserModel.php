<?php
class UserModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function register($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password_hash, nama_lengkap) VALUES (:username, :email, :password_hash, :nama_lengkap)"
        );
        try {
            return $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password_hash' => $data['password_hash'],
                ':nama_lengkap' => $data['nama_lengkap']
            ]);
        } catch (PDOException $e) {
            // error_log("Error registrasi user: " . $e->getMessage()); // Log error
            return false;
        }
    }

    public function findByUsernameOrEmail($identifier)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :identifier OR email = :identifier LIMIT 1");
        $stmt->execute([':identifier' => $identifier]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function storeRememberToken($userId, $selector, $validatorHash, $expiresAt)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO remember_tokens (user_id, selector, validator_hash, expires_at) VALUES (:user_id, :selector, :validator_hash, :expires_at)"
        );
        return $stmt->execute([
            ':user_id' => $userId,
            ':selector' => $selector,
            ':validator_hash' => $validatorHash,
            ':expires_at' => $expiresAt
        ]);
    }

    public function findRememberTokenBySelector($selector)
    {
        $stmt = $this->db->prepare("SELECT * FROM remember_tokens WHERE selector = :selector AND expires_at >= NOW() LIMIT 1");
        $stmt->execute([':selector' => $selector]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteRememberTokensByUserId($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM remember_tokens WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $userId]);
    }

    public function deleteRememberTokenBySelector($selector)
    {
        $stmt = $this->db->prepare("DELETE FROM remember_tokens WHERE selector = :selector");
        return $stmt->execute([':selector' => $selector]);
    }
}
