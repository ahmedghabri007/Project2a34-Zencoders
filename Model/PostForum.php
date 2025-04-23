<?php
require_once __DIR__ . '/../config.php';

class PostForum {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function getPostsByThread($threadId) {
        try {
            $stmt = $this->pdo->prepare('SELECT p.*, f.sujet as thread_title FROM post_forum p 
                                       LEFT JOIN forum f ON p.thread = f.id_forum 
                                       WHERE p.thread = ? ORDER BY p.id_post DESC');
            $stmt->execute([$threadId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in getPostsByThread: ' . $e->getMessage());
            return [];
        }
    }

    public function addPost($comment, $threadId) {
        try {
            if (empty($comment) || strlen($comment) < 2 || strlen($comment) > 1000) {
                throw new Exception('Comment must be between 2 and 1000 characters');
            }

            $stmt = $this->pdo->prepare('INSERT INTO post_forum (comment, thread, upvote, downvote, status, date_publication) 
                                       VALUES (?, ?, 0, 0, "active", NOW())');
            $result = $stmt->execute([trim($comment), $threadId]);
            
            if (!$result) {
                throw new Exception('Failed to add comment: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return true;
        } catch (Exception $e) {
            error_log('Error in addPost: ' . $e->getMessage());
            return false;
        }
    }

    public function votePost($postId, $voteType) {
        try {
            if (!in_array($voteType, ['up', 'down'])) {
                throw new Exception('Invalid vote type');
            }

            // Start transaction
            $this->pdo->beginTransaction();

            // Get current votes
            $stmt = $this->pdo->prepare('SELECT upvote, downvote FROM post_forum WHERE id_post = ? FOR UPDATE');
            if (!$stmt->execute([$postId])) {
                throw new Exception('Failed to get current votes');
            }

            $post = $stmt->fetch();
            if (!$post) {
                throw new Exception('Post not found');
            }

            // Update the vote
            $column = $voteType === 'up' ? 'upvote' : 'downvote';
            $stmt = $this->pdo->prepare('UPDATE post_forum SET ' . $column . ' = ' . $column . ' + 1 WHERE id_post = ?');
            if (!$stmt->execute([$postId])) {
                throw new Exception('Failed to update vote: ' . implode(', ', $stmt->errorInfo()));
            }

            // Commit transaction
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            // Rollback on error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Error in votePost: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePost($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM post_forum WHERE id_post = ?');
            $result = $stmt->execute([$id]);

            if (!$result) {
                throw new Exception('Failed to delete post: ' . implode(', ', $stmt->errorInfo()));
            }

            return true;
        } catch (Exception $e) {
            error_log('Error in deletePost: ' . $e->getMessage());
            return false;
        }
    }

    public function getPostById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM post_forum WHERE id_post = ?');
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Error in getPostById: ' . $e->getMessage());
            return null;
        }
    }

    public function updateStatus($id, $status) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid comment ID');
            }

            if (!in_array($status, ['active', 'inactive'])) {
                throw new Exception('Invalid status value: ' . $status);
            }

            // Check if comment exists
            $comment = $this->getPostById($id);
            if (!$comment) {
                throw new Exception('Comment not found with ID: ' . $id);
            }

            // Update the status
            $stmt = $this->pdo->prepare('UPDATE post_forum SET status = ? WHERE id_post = ?');
            $result = $stmt->execute([$status, $id]);

            if (!$result) {
                $error = $stmt->errorInfo();
                throw new Exception('Failed to update comment status: ' . ($error[2] ?? 'Unknown error'));
            }

            return true;
        } catch (Exception $e) {
            error_log('Error in updateStatus: ' . $e->getMessage());
            return false;
        }
    }
    
    public function updateComment($id, $comment) {
        try {
            if (empty($id)) {
                throw new Exception('Invalid comment ID');
            }
            
            if (empty($comment) || strlen($comment) < 2 || strlen($comment) > 1000) {
                throw new Exception('Comment must be between 2 and 1000 characters');
            }
            
            // Check if comment exists
            $existingComment = $this->getPostById($id);
            if (!$existingComment) {
                throw new Exception('Comment not found with ID: ' . $id);
            }
            
            // Update the comment
            $stmt = $this->pdo->prepare('UPDATE post_forum SET comment = ? WHERE id_post = ?');
            $result = $stmt->execute([trim($comment), $id]);
            
            if (!$result) {
                $error = $stmt->errorInfo();
                throw new Exception('Failed to update comment: ' . ($error[2] ?? 'Unknown error'));
            }
            
            return true;
        } catch (Exception $e) {
            error_log('Error in updateComment: ' . $e->getMessage());
            return false;
        }
    }
}
