<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        return $this->db->select($query);
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->select($query);
        return $result[0]['total'] ?? 0;
    }

    public function getRecentUsers($limit = 5) {
        $query = "SELECT * FROM users ORDER BY created_at DESC LIMIT ?";
        return $this->db->select($query, [$limit]);
    }

    public function updateStatus($userId, $status) {
        $query = "UPDATE users SET status = ? WHERE id = ?";
        return $this->db->update($query, [$status, $userId]);
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE id = ?";
        return $this->db->delete($query, [$userId]);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $result = $this->db->select($query, [$id]);
        return $result ? $result[0] : null;
    }
}
