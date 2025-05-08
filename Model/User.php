<?php
require_once __DIR__ . '/../config.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    /**
     * Get all users
     */
    public function getAllUsers() {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users ORDER BY id DESC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getAllUsers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Error in getUserById: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Add a new user
     */
    public function addUser($username, $role, $linkedin_url = null, $instagram_url = null, $facebook_url = null) {
        try {
            if (empty($username) || strlen($username) < 2) {
                throw new Exception('Username must be at least 2 characters');
            }

            if (!in_array($role, ['investor', 'entrepreneur'])) {
                throw new Exception('Role must be either investor or entrepreneur');
            }

            $stmt = $this->pdo->prepare('INSERT INTO users (username, role, linkedin_url, instagram_url, facebook_url, created_at) 
                                       VALUES (?, ?, ?, ?, ?, NOW())');
            $result = $stmt->execute([
                trim($username), 
                $role, 
                $linkedin_url ? trim($linkedin_url) : null, 
                $instagram_url ? trim($instagram_url) : null, 
                $facebook_url ? trim($facebook_url) : null
            ]);
            
            if (!$result) {
                throw new Exception('Failed to add user: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            error_log('Error in addUser: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing user
     */
    public function updateUser($id, $username, $role, $linkedin_url = null, $instagram_url = null, $facebook_url = null) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid user ID');
            }
            
            if (empty($username) || strlen($username) < 2) {
                throw new Exception('Username must be at least 2 characters');
            }

            if (!in_array($role, ['investor', 'entrepreneur'])) {
                throw new Exception('Role must be either investor or entrepreneur');
            }
            
            // Check if user exists
            $user = $this->getUserById($id);
            if (!$user) {
                throw new Exception('User not found with ID: ' . $id);
            }
            
            // Update the user
            $stmt = $this->pdo->prepare('UPDATE users SET username = ?, role = ?, linkedin_url = ?, instagram_url = ?, facebook_url = ? WHERE id = ?');
            $result = $stmt->execute([
                trim($username), 
                $role, 
                $linkedin_url ? trim($linkedin_url) : null, 
                $instagram_url ? trim($instagram_url) : null, 
                $facebook_url ? trim($facebook_url) : null,
                $id
            ]);
            
            if (!$result) {
                $error = $stmt->errorInfo();
                throw new Exception('Failed to update user: ' . ($error[2] ?? 'Unknown error'));
            }
            
            return true;
        } catch (Exception $e) {
            error_log('Error in updateUser: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user
     */
    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
            $result = $stmt->execute([$id]);

            if (!$result) {
                throw new Exception('Failed to delete user: ' . implode(', ', $stmt->errorInfo()));
            }

            return true;
        } catch (Exception $e) {
            error_log('Error in deleteUser: ' . $e->getMessage());
            return false;
        }
    }
}
