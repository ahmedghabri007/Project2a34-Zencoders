<?php
require_once __DIR__ . '/../config.php';

class PostForum {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function getPostsByThread($threadId) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM post_forum WHERE thread = ? ORDER BY id_post DESC');
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

            $stmt = $this->pdo->prepare('INSERT INTO post_forum (comment, thread, upvote, downvote) VALUES (?, ?, 0, 0)');
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
}
