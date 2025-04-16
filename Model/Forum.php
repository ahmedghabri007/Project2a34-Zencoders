<?php
require_once __DIR__ . '/../config.php';

class Forum {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function getAllForums() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM forum ORDER BY date_publication DESC');
            if (!$stmt) {
                error_log('Error in getAllForums: ' . implode(', ', $this->pdo->errorInfo()));
                return false;
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Exception in getAllForums: ' . $e->getMessage());
            return false;
        }
    }

    public function getForumById($id) {
        try {
            if (!$id) {
                return false;
            }
            $stmt = $this->pdo->prepare('SELECT * FROM forum WHERE id_forum = ?');
            if (!$stmt->execute([$id])) {
                error_log('Error in getForumById: ' . implode(', ', $stmt->errorInfo()));
                return false;
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Exception in getForumById: ' . $e->getMessage());
            return false;
        }
    }

    public function addForum($sujet, $contenu) {
        try {
            error_log('Attempting to add forum with subject: ' . $sujet);
            $stmt = $this->pdo->prepare('INSERT INTO forum (sujet, contenu, date_publication) VALUES (?, ?, NOW())');
            $result = $stmt->execute([$sujet, $contenu]);
            if (!$result) {
                $error = implode(', ', $stmt->errorInfo());
                error_log('Failed to insert forum: ' . $error);
                throw new Exception('Database error: ' . $error);
            }
            error_log('Successfully added forum with ID: ' . $this->pdo->lastInsertId());
            return true;
        } catch (Exception $e) {
            error_log('Error in addForum: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function updateForum($id, $sujet, $contenu) {
        $stmt = $this->pdo->prepare('UPDATE forum SET sujet = ?, contenu = ? WHERE id_forum = ?');
        return $stmt->execute([$sujet, $contenu, $id]);
    }

    public function deleteForum($id) {
        $stmt = $this->pdo->prepare('DELETE FROM forum WHERE id_forum = ?');
        return $stmt->execute([$id]);
    }
}
