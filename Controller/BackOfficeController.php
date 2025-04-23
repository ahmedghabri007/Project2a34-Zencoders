<?php
require_once __DIR__ . '/../model/Forum.php';
require_once __DIR__ . '/../model/PostForum.php';
require_once __DIR__ . '/../model/Category.php';

class BackOfficeController {
    private $forumModel;
    private $postModel;
    private $categoryModel;

    public function __construct() {
        $this->forumModel = new Forum();
        $this->postModel = new PostForum();
        $this->categoryModel = new Category();
    }

    public function getDashboardStats() {
        try {
            $threads = $this->getAllThreads();
            $comments = $this->getAllComments();
            
            // Calculate active and inactive counts
            $activeThreads = 0;
            $inactiveThreads = 0;
            foreach ($threads as $thread) {
                if (isset($thread['status']) && $thread['status'] === 'inactive') {
                    $inactiveThreads++;
                } else {
                    $activeThreads++;
                }
            }

            $activeComments = 0;
            $inactiveComments = 0;
            foreach ($comments as $comment) {
                if (isset($comment['status']) && $comment['status'] === 'inactive') {
                    $inactiveComments++;
                } else {
                    $activeComments++;
                }
            }

            // Get recent items (last 5)
            $recentThreads = array_slice($threads, 0, 5);
            $recentComments = array_slice($comments, 0, 5);

            return [
                'totalThreads' => count($threads),
                'activeThreads' => $activeThreads,
                'inactiveThreads' => $inactiveThreads,
                'totalComments' => count($comments),
                'activeComments' => $activeComments,
                'inactiveComments' => $inactiveComments,
                'recentThreads' => $recentThreads,
                'recentComments' => $recentComments
            ];
        } catch (Exception $e) {
            error_log('Error in getDashboardStats: ' . $e->getMessage());
            return [
                'totalThreads' => 0,
                'activeThreads' => 0,
                'inactiveThreads' => 0,
                'totalComments' => 0,
                'activeComments' => 0,
                'inactiveComments' => 0,
                'recentThreads' => [],
                'recentComments' => []
            ];
        }
    }

    public function getAllThreads() {
        try {
            return $this->forumModel->getAllForums();
        } catch (Exception $e) {
            error_log('Error in getAllThreads: ' . $e->getMessage());
            return [];
        }
    }

    public function getAllComments() {
        try {
            $threads = $this->getAllThreads();
            $allComments = [];
            foreach ($threads as $thread) {
                $comments = $this->postModel->getPostsByThread($thread['id_forum']);
                foreach ($comments as $comment) {
                    $comment['thread_title'] = $thread['sujet'];
                    $comment['thread_id'] = $thread['id_forum'];
                    $allComments[] = $comment;
                }
            }
            return $allComments;
        } catch (Exception $e) {
            error_log('Error in getAllComments: ' . $e->getMessage());
            return [];
        }
    }

    // Category Management Methods
    public function getAllCategories() {
        try {
            return $this->categoryModel->getAllCategories();
        } catch (Exception $e) {
            error_log('Error in getAllCategories: ' . $e->getMessage());
            return [];
        }
    }

    public function addCategory($name, $description) {
        try {
            return $this->categoryModel->addCategory($name, $description);
        } catch (Exception $e) {
            error_log('Error in addCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $name, $description) {
        try {
            return $this->categoryModel->updateCategory($id, $name, $description);
        } catch (Exception $e) {
            error_log('Error in updateCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            return $this->categoryModel->deleteCategory($id);
        } catch (Exception $e) {
            error_log('Error in deleteCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function getCategoryById($id) {
        try {
            return $this->categoryModel->getCategoryById($id);
        } catch (Exception $e) {
            error_log('Error in getCategoryById: ' . $e->getMessage());
            return null;
        }
    }

    public function toggleThreadStatus($id) {
        try {
            $thread = $this->forumModel->getForumById($id);
            if (!$thread) {
                throw new Exception('Thread not found');
            }
            $newStatus = isset($thread['status']) && $thread['status'] === 'active' ? 'inactive' : 'active';
            return $this->forumModel->updateStatus($id, $newStatus);
        } catch (Exception $e) {
            error_log('Error in toggleThreadStatus: ' . $e->getMessage());
            return false;
        }
    }

    public function toggleCommentStatus($id) {
        try {
            $comment = $this->postModel->getPostById($id);
            if (!$comment) {
                throw new Exception('Comment not found');
            }
            $newStatus = isset($comment['status']) && $comment['status'] === 'active' ? 'inactive' : 'active';
            return $this->postModel->updateStatus($id, $newStatus);
        } catch (Exception $e) {
            error_log('Error in toggleCommentStatus: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteThread($id) {
        try {
            return $this->forumModel->deleteForum($id);
        } catch (Exception $e) {
            error_log('Error in deleteThread: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteComment($id) {
        try {
            return $this->postModel->deletePost($id);
        } catch (Exception $e) {
            error_log('Error in deleteComment: ' . $e->getMessage());
            return false;
        }
    }
}
