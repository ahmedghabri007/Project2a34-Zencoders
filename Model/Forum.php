<?php
require_once __DIR__ . '/../config.php';

class Forum {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function getAllForums($search = '', $sortBy = 'date_publication', $sortOrder = 'DESC', $status = null) {
        try {
            // Base query with comment count, upvote count, and user info
            $query = 'SELECT f.*, 
                     (SELECT COUNT(*) FROM post_forum p WHERE p.thread = f.id_forum) AS comment_count,
                     (SELECT COALESCE(SUM(p.upvote), 0) FROM post_forum p WHERE p.thread = f.id_forum) AS upvote_count,
                     u.username, u.role, u.linkedin_url, u.instagram_url, u.facebook_url
                     FROM forum f 
                     LEFT JOIN users u ON f.user_id = u.id
                     WHERE 1=1';
            $params = [];
            
            // Add search condition if provided
            if (!empty($search)) {
                $query .= ' AND (f.sujet LIKE ? OR f.contenu LIKE ?)';
                $params[] = '%' . $search . '%';
                $params[] = '%' . $search . '%';
            }
            
            // Add status filter if provided
            if ($status !== null && in_array($status, ['active', 'inactive'])) {
                $query .= ' AND f.status = ?';
                $params[] = $status;
            }
            
            // Validate and add sorting
            $allowedSortFields = ['sujet', 'date_publication', 'status', 'comment_count', 'upvote_count'];
            $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'date_publication';
            
            $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
            $query .= ' ORDER BY ' . $sortBy . ' ' . $sortOrder;
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getAllForums: ' . $e->getMessage());
            return [];
        }
    }

    public function addForum($subject, $content, $user_id = null) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO forum (sujet, contenu, user_id, date_publication, status, views) VALUES (?, ?, ?, NOW(), "active", 0)');
            $result = $stmt->execute([$subject, $content, $user_id]);
            
            if (!$result) {
                throw new Exception('Failed to add forum: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            error_log('Error in addForum: ' . $e->getMessage());
            return false;
        }
    }

    public function incrementViews($id) {
        try {
            $stmt = $this->pdo->prepare('UPDATE forum SET views = views + 1 WHERE id_forum = ?');
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log('Error in incrementViews: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getTopHitsToday() {
        try {
            $query = 'SELECT f.*, u.username, u.role, u.linkedin_url, u.instagram_url, u.facebook_url
                     FROM forum f 
                     LEFT JOIN users u ON f.user_id = u.id
                     WHERE DATE(f.date_publication) = CURDATE()
                     ORDER BY f.views DESC
                     LIMIT 10';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getTopHitsToday: ' . $e->getMessage());
            return [];
        }
    }
    
    public function getForumById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT f.*, u.username, u.role, u.linkedin_url, u.instagram_url, u.facebook_url 
                                     FROM forum f 
                                     LEFT JOIN users u ON f.user_id = u.id 
                                     WHERE f.id_forum = ?');
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
