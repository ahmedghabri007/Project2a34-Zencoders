<?php
require_once __DIR__ . '/../model/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function listUsers() {
        $users = $this->userModel->getAllUsers();
        
        // Load the users view
        require_once __DIR__ . '/../view/BackOffice/users.php';
    }

    public function editUser($id) {
        if (empty($id)) {
            $_SESSION['error'] = 'Invalid user ID';
            header('Location: /project-2a34/index.php?action=users');
            exit;
        }

        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: /project-2a34/index.php?action=users');
            exit;
        }

        // Load the edit user view
        require_once __DIR__ . '/../view/BackOffice/edit_user.php';
    }

    public function addUser() {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $role = $_POST['role'] ?? '';
            $linkedin_url = $_POST['linkedin_url'] ?? null;
            $instagram_url = $_POST['instagram_url'] ?? null;
            $facebook_url = $_POST['facebook_url'] ?? null;
            
            // Validate inputs
            if (empty($username) || strlen($username) < 2) {
                $_SESSION['error'] = 'Username must be at least 2 characters';
                header('Location: /project-2a34/index.php?action=users');
                exit;
            }

            if (!in_array($role, ['investor', 'entrepreneur'])) {
                $_SESSION['error'] = 'Invalid role selected';
                header('Location: /project-2a34/index.php?action=users');
                exit;
            }
            
            // Add the user
            $result = $this->userModel->addUser($username, $role, $linkedin_url, $instagram_url, $facebook_url);
            
            if ($result) {
                $_SESSION['success'] = 'User added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add user';
            }
            
            header('Location: /project-2a34/index.php?action=users');
            exit;
        }
        
        // If not a POST request, redirect to users list
        header('Location: /project-2a34/index.php?action=users');
        exit;
    }

    public function updateUser() {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $username = $_POST['username'] ?? '';
            $role = $_POST['role'] ?? '';
            $linkedin_url = $_POST['linkedin_url'] ?? null;
            $instagram_url = $_POST['instagram_url'] ?? null;
            $facebook_url = $_POST['facebook_url'] ?? null;
            
            // Validate inputs
            if (empty($id)) {
                $_SESSION['error'] = 'Invalid user ID';
                header('Location: /project-2a34/index.php?action=users');
                exit;
            }
            
            if (empty($username) || strlen($username) < 2) {
                $_SESSION['error'] = 'Username must be at least 2 characters';
                header('Location: /project-2a34/index.php?action=editUser&id=' . $id);
                exit;
            }

            if (!in_array($role, ['investor', 'entrepreneur'])) {
                $_SESSION['error'] = 'Invalid role selected';
                header('Location: /project-2a34/index.php?action=editUser&id=' . $id);
                exit;
            }
            
            // Update the user
            $result = $this->userModel->updateUser($id, $username, $role, $linkedin_url, $instagram_url, $facebook_url);
            
            if ($result) {
                $_SESSION['success'] = 'User updated successfully';
                header('Location: /project-2a34/index.php?action=users');
            } else {
                $_SESSION['error'] = 'Failed to update user';
                header('Location: /project-2a34/index.php?action=editUser&id=' . $id);
            }
            exit;
        }
        
        // If not a POST request, redirect to users list
        header('Location: /project-2a34/index.php?action=users');
        exit;
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            $_SESSION['error'] = 'Invalid user ID';
            header('Location: /project-2a34/index.php?action=users');
            exit;
        }
        
        $result = $this->userModel->deleteUser($id);
        
        if ($result) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete user';
        }
        
        header('Location: /project-2a34/index.php?action=users');
        exit;
    }
}
