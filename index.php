<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/Controller/BackOfficeController.php';
require_once __DIR__ . '/Controller/AuthController.php';

$backOfficeController = new BackOfficeController();
$authController = new AuthController();

// Check if it's a forum-related action
if (isset($_GET['action']) && in_array($_GET['action'], ['list', 'createThread', 'edit', 'delete', 'view', 'vote', 'addComment', 'editComment', 'deleteComment'])) {
    // Include and run the ForumController
    include_once __DIR__ . '/Controller/ForumController.php';
    exit; // ForumController handles the response
}

// If no action is specified, redirect to home page
if (!isset($_GET['action'])) {
    header('Location: home.php');
    exit;
}

// Handle login/logout
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if ($authController->login($username, $password)) {
                    header('Location: view/BackOffice/dashboard.php');
                    exit;
                } else {
                    header('Location: view/login.php?error=1');
                    exit;
                }
            }
            break;

        case 'logout':
            $authController->logout();
            header('Location: view/login.php');
            exit;

        // Thread Management Actions
        case 'deleteThread':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authController->isAdmin()) {
                $threadId = $_POST['thread_id'] ?? null;
                if ($backOfficeController->deleteThread($threadId)) {
                    header('Location: view/BackOffice/threads.php?success=Thread deleted successfully');
                } else {
                    header('Location: view/BackOffice/threads.php?error=Failed to delete thread');
                }
                exit;
            }
            break;

        case 'viewThread':
            if ($authController->isAdmin()) {
                $threadId = $_GET['thread_id'] ?? null;
                $thread = $backOfficeController->getThreadById($threadId);
                if ($thread) {
                    header('Content-Type: application/json');
                    echo json_encode($thread);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Thread not found']);
                }
                exit;
            }
            break;

        case 'toggleThreadStatus':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authController->isAdmin()) {
                $threadId = $_POST['thread_id'] ?? null;
                $newStatus = $_POST['status'] ?? null;
                
                if ($backOfficeController->toggleThreadStatus($threadId, $newStatus)) {
                    header('Location: view/BackOffice/threads.php?success=Status updated successfully');
                } else {
                    header('Location: view/BackOffice/threads.php?error=Failed to update status');
                }
                exit;
            }
            break;

        // Comment Management Actions
        case 'deleteComment':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authController->isAdmin()) {
                $commentId = $_POST['comment_id'] ?? null;
                if ($backOfficeController->deleteComment($commentId)) {
                    header('Location: view/BackOffice/comments.php?success=Comment deleted successfully');
                } else {
                    header('Location: view/BackOffice/comments.php?error=Failed to delete comment');
                }
                exit;
            }
            break;

        case 'viewComment':
            if ($authController->isAdmin()) {
                $commentId = $_GET['comment_id'] ?? null;
                $comment = $backOfficeController->getCommentById($commentId);
                if ($comment) {
                    header('Content-Type: application/json');
                    echo json_encode($comment);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Comment not found']);
                }
                exit;
            }
            break;

        case 'toggleCommentStatus':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authController->isAdmin()) {
                $commentId = $_POST['comment_id'] ?? null;
                $newStatus = $_POST['status'] ?? null;
                
                if ($backOfficeController->toggleCommentStatus($commentId, $newStatus)) {
                    header('Location: view/BackOffice/comments.php?success=Status updated successfully');
                } else {
                    header('Location: view/BackOffice/comments.php?error=Failed to update status');
                }
                exit;
            }
            break;

        default:
            header('Location: view/login.php');
            exit;
    }
} else {
    header('Location: view/login.php');
    exit;
}
