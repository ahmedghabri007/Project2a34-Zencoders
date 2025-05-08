<?php
require_once __DIR__ . '/../model/Forum.php';
require_once __DIR__ . '/../model/PostForum.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../services/TranslationService.php';

$forumModel = new Forum();
$postModel = new PostForum();
$userModel = new User();
$translationService = new TranslationService();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'translate':
        // Handle translation request
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit();
        }
        
        $text = $_POST['text'] ?? '';
        $language = $_POST['language'] ?? 'fr';
        
        if (empty($text)) {
            echo json_encode(['success' => false, 'error' => 'No text provided']);
            exit();
        }
        
        // Set the target language
        $translationService->setTargetLanguage($language);
        
        // Translate the text
        $translation = $translationService->translate($text);
        
        echo json_encode([
            'success' => true,
            'translation' => $translation,
            'language' => $language
        ]);
        exit();
        break;
    case 'list':
    default:
        // Get search, filter, and sorting parameters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date_publication';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        // Get forums with applied filters and sorting
        $forums = $forumModel->getAllForums($search, $sortBy, $sortOrder, $status);
        if ($forums === false) {
            $forums = [];
            $_SESSION['error'] = 'Error loading forums';
        }
        
        // Pass the filter parameters to the view for maintaining state
        $filterParams = [
            'search' => $search,
            'sort' => $sortBy,
            'order' => $sortOrder,
            'status' => $status
        ];
        
        include __DIR__ . '/../view/Forum/list.php';
        break;

    case 'analytics':
        // Get top hits and top comments for today
        $topHits = $forumModel->getTopHitsToday();
        $topComments = $postModel->getTopCommentsToday();
        
        include __DIR__ . '/../view/Forum/analytics.php';
        break;
        
    case 'createThread':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sujet = trim($_POST['title'] ?? '');
            $contenu = trim($_POST['content'] ?? '');
            $user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : null;
            $error = '';

            // Validate subject
            if (empty($sujet)) {
                $error = 'Title is required';
            } elseif (strlen($sujet) < 5) {
                $error = 'Title must be at least 5 characters long';
            } elseif (strlen($sujet) > 255) {
                $error = 'Title must not exceed 255 characters';
            } elseif (!preg_match('/^[A-Za-z0-9\s\-_.,!?()]+$/', $sujet)) {
                $error = 'Title contains invalid characters';
            }

            // Validate content
            if (empty($contenu)) {
                $error = 'Content is required';
            } elseif (strlen($contenu) < 10) {
                $error = 'Content must be at least 10 characters long';
            } elseif (strlen($contenu) > 1000) {
                $error = 'Content must not exceed 1000 characters';
            }

            if ($error) {
                include __DIR__ . '/../view/Forum/create_thread.php';
            } else {
                $result = $forumModel->addForum($sujet, $contenu, $user_id);
                if ($result) {
                    $_SESSION['success'] = 'Thread created successfully!';
                    header('Location: /project-2a34/index.php?action=list');
                    exit();
                } else {
                    $error = 'Failed to create thread. Please try again.';
                    include __DIR__ . '/../view/Forum/create_thread.php';
                }
            }
        } else {
            include __DIR__ . '/../view/Forum/create_thread.php';
        }
        break;

    case 'edit':
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Forum ID is required';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sujet = trim($_POST['sujet'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');
            $error = '';

            // Validate subject
            if (empty($sujet)) {
                $error = 'Subject is required';
            } elseif (strlen($sujet) < 5) {
                $error = 'Subject must be at least 5 characters long';
            } elseif (strlen($sujet) > 255) {
                $error = 'Subject must not exceed 255 characters';
            } elseif (!preg_match('/^[A-Za-z0-9\s\-_.,!?()]+$/', $sujet)) {
                $error = 'Subject contains invalid characters';
            }

            // Validate content
            if (empty($contenu)) {
                $error = 'Content is required';
            } elseif (strlen($contenu) < 10) {
                $error = 'Content must be at least 10 characters long';
            } elseif (strlen($contenu) > 1000) {
                $error = 'Content must not exceed 1000 characters';
            }

            if ($error) {
                $forum = $forumModel->getForumById($id);
                include __DIR__ . '/../view/Forum/edit.php';
            } else {
                $result = $forumModel->updateForum($id, $sujet, $contenu);
                if ($result) {
                    $_SESSION['success'] = 'Forum updated successfully!';
                    header('Location: /project-2a34/index.php?action=list');
                    exit();
                } else {
                    $error = 'Failed to update forum. Please try again.';
                    $forum = $forumModel->getForumById($id);
                    include __DIR__ . '/../view/Forum/edit.php';
                }
            }
        } else {
            $forum = $forumModel->getForumById($id);
            if (!$forum) {
                $_SESSION['error'] = 'Forum not found';
                header('Location: /project-2a34/index.php?action=list');
                exit();
            }
            include __DIR__ . '/../view/Forum/edit.php';
        }
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Forum ID is required';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        $id = $_GET['id'];
        $result = $forumModel->deleteForum($id);
        
        if ($result) {
            $_SESSION['success'] = 'Forum deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete forum. Please try again.';
        }
        
        header('Location: /project-2a34/index.php?action=list');
        exit();
        break;

    case 'view':
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Forum ID is required';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        $id = $_GET['id'];
        
        // Increment view counter
        $forumModel->incrementViews($id);
        
        $forum = $forumModel->getForumById($id);
        
        if (!$forum) {
            $_SESSION['error'] = 'Forum not found';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        // Get users for the comment form dropdown
        $users = $userModel->getAllUsers();

        // Get comments for this forum
        $posts = $postModel->getPostsByThread($id);
        if ($posts === false) {
            $posts = [];
        }

        include __DIR__ . '/../view/Forum/view.php';
        break;

    case 'editComment':
        if (!isset($_GET['id']) || !isset($_GET['thread'])) {
            $_SESSION['error'] = 'Comment ID and Thread ID are required';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        $commentId = $_GET['id'];
        $threadId = $_GET['thread'];
        
        // Get users for the edit form
        $users = $userModel->getAllUsers();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentText = trim($_POST['comment'] ?? '');
            $error = '';
            
            // Validate comment
            if (empty($commentText)) {
                $error = 'Comment is required';
            } elseif (strlen($commentText) < 2) {
                $error = 'Comment must be at least 2 characters long';
            } elseif (strlen($commentText) > 1000) {
                $error = 'Comment must not exceed 1000 characters';
            }
            
            if ($error) {
                $comment = $postModel->getPostById($commentId);
                $forum = $forumModel->getForumById($threadId);
                include __DIR__ . '/../view/Forum/edit_comment.php';
            } else {
                $result = $postModel->updateComment($commentId, $commentText);
                if ($result) {
                    $_SESSION['success'] = 'Comment updated successfully!';
                    header('Location: /project-2a34/index.php?action=view&id=' . $threadId);
                    exit();
                } else {
                    $error = 'Failed to update comment. Please try again.';
                    $comment = $postModel->getPostById($commentId);
                    $forum = $forumModel->getForumById($threadId);
                    include __DIR__ . '/../view/Forum/edit_comment.php';
                }
            }
        } else {
            $comment = $postModel->getPostById($commentId);
            if (!$comment) {
                $_SESSION['error'] = 'Comment not found';
                header('Location: /project-2a34/index.php?action=view&id=' . $threadId);
                exit();
            }
            $forum = $forumModel->getForumById($threadId);
            include __DIR__ . '/../view/Forum/edit_comment.php';
        }
        break;
        
    case 'deleteComment':
        if (!isset($_GET['id']) || !isset($_GET['thread'])) {
            $_SESSION['error'] = 'Comment ID and Thread ID are required';
            header('Location: /project-2a34/index.php?action=list');
            exit();
        }
        
        $commentId = $_GET['id'];
        $threadId = $_GET['thread'];
        
        $result = $postModel->deletePost($commentId);
        
        if ($result) {
            $_SESSION['success'] = 'Comment deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete comment. Please try again.';
        }
        
        header('Location: /project-2a34/index.php?action=view&id=' . $threadId);
        exit();
        break;
        
    case 'vote':
        if (!isset($_GET['id']) || !isset($_GET['type']) || !isset($_GET['thread'])) {
            header('Location: /project-2a34/index.php');
            exit();
        }

        $postId = $_GET['id'];
        $voteType = $_GET['type'];
        $threadId = $_GET['thread'];

        if ($postModel->votePost($postId, $voteType)) {
            header('Location: /project-2a34/index.php?action=view&id=' . $threadId . '#comment-' . $postId);
        } else {
            $_SESSION['error'] = 'Failed to vote on the comment';
            header('Location: /project-2a34/index.php?action=view&id=' . $threadId);
        }
        exit();

    case 'addComment':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project-2a34/index.php');
            exit();
        }

        $threadId = $_POST['thread'] ?? null;
        if (!$threadId) {
            header('Location: /project-2a34/index.php');
            exit();
        }

        $comment = trim($_POST['comment'] ?? '');
        $user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : null;
        $error = '';

        // Validate comment
        if (empty($comment)) {
            $error = 'Comment is required';
        } elseif (strlen($comment) < 2) {
            $error = 'Comment must be at least 2 characters long';
        } elseif (strlen($comment) > 1000) {
            $error = 'Comment must not exceed 1000 characters';
        }

        if ($error) {
            $forum = $forumModel->getForumById($threadId);
            $posts = $postModel->getPostsByThread($threadId);
            include __DIR__ . '/../view/Forum/view.php';
            include __DIR__ . '/../view/Forum/comments.php';
        } else {
            $result = $postModel->addPost($comment, $threadId, $user_id);
            if ($result) {
                header('Location: /project-2a34/index.php?action=view&id=' . $threadId);
                exit();
            } else {
                $error = 'Failed to add comment. Please try again.';
                $forum = $forumModel->getForumById($threadId);
                $posts = $postModel->getPostsByThread($threadId);
                include __DIR__ . '/../view/Forum/view.php';
                include __DIR__ . '/../view/Forum/comments.php';
            }
        }
        break;

    case 'vote':
        header('Content-Type: application/json');
        $id = $_GET['id'];
        $type = $_GET['type'];
        
        if (!in_array($type, ['up', 'down'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid vote type']);
            exit();
        }
        
        $result = $postModel->votePost($id, $type);
        echo json_encode(['success' => $result]);
        exit();
        break;
}
