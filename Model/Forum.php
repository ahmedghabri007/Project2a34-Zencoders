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
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getAllForums: ' . $e->getMessage());
            return [];
        }
    }

    public function addForum($subject, $content) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO forum (sujet, contenu, date_publication, status) VALUES (?, ?, NOW(), "active")');
            $result = $stmt->execute([$subject, $content]);
            
            if (!$result) {
                throw new Exception('Failed to add forum: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            error_log('Error in addForum: ' . $e->getMessage());
            return false;
        }
    }

    public function getForumById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM forum WHERE id_forum = ?');
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Error in getForumById: ' . $e->getMessage());
            return null;
        }
    }

    public function updateStatus($id, $status) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid thread ID');
            }

            if (!in_array($status, ['active', 'inactive'])) {
                throw new Exception('Invalid status value: ' . $status);
            }

            // Check if thread exists
            $thread = $this->getForumById($id);
            if (!$thread) {
                throw new Exception('Thread not found with ID: ' . $id);
            }

            // Update the status
            $stmt = $this->pdo->prepare('UPDATE forum SET status = ? WHERE id_forum = ?');
            $result = $stmt->execute([$status, $id]);

            if (!$result) {
                $error = $stmt->errorInfo();
                throw new Exception('Failed to update thread status: ' . ($error[2] ?? 'Unknown error'));
            }

            return true;
        } catch (Exception $e) {
            error_log('Error in updateStatus: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteForum($id) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid thread ID');
            }

            // Start transaction
            $this->pdo->beginTransaction();

            // Delete all comments first
            $stmt = $this->pdo->prepare('DELETE FROM post_forum WHERE thread = ?');
            if (!$stmt->execute([$id])) {
                throw new Exception('Failed to delete thread comments');
            }

            // Then delete the thread
            $stmt = $this->pdo->prepare('DELETE FROM forum WHERE id_forum = ?');
            if (!$stmt->execute([$id])) {
                throw new Exception('Failed to delete thread');
            }

            // Commit transaction
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            // Rollback on error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Error in deleteForum: ' . $e->getMessage());
            return false;
        }
    }
    
    public function updateForum($id, $subject, $content) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid thread ID');
            }

            // Check if thread exists
            $thread = $this->getForumById($id);
            if (!$thread) {
                throw new Exception('Thread not found with ID: ' . $id);
            }

            // Update the thread
            $stmt = $this->pdo->prepare('UPDATE forum SET sujet = ?, contenu = ? WHERE id_forum = ?');
            $result = $stmt->execute([$subject, $content, $id]);

            if (!$result) {
                $error = $stmt->errorInfo();
                throw new Exception('Failed to update thread: ' . ($error[2] ?? 'Unknown error'));
            }

            return true;
        } catch (Exception $e) {
            error_log('Error in updateForum: ' . $e->getMessage());
            return false;
        }
    }
}
