<?php
require_once __DIR__ . '/../config.php';

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function getAllCategories() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getAllCategories: ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalCategories() {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM categories");
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log('Error in getTotalCategories: ' . $e->getMessage());
            return 0;
        }
    }

    public function addCategory($name, $description) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            return $stmt->execute([$name, $description]);
        } catch (Exception $e) {
            error_log('Error in addCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $name, $description) {
        try {
            $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $id]);
        } catch (Exception $e) {
            error_log('Error in updateCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log('Error in deleteCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function getCategoryById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Error in getCategoryById: ' . $e->getMessage());
            return null;
        }
    }
}
