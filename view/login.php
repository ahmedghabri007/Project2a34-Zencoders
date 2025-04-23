<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: /project-2a34/view/BackOffice/dashboard.php');
    exit;
}

// Handle manual login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $defaultUsername = 'admin';
    $defaultPassword = 'admin123';
    if ($username === $defaultUsername && $password === $defaultPassword) {
        $_SESSION['admin'] = true;
        header('Location: /project-2a34/view/BackOffice/dashboard.php');
        exit();
    } else {
        header('Location: login.php?error=true');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zencoders</title>
    
    <!-- Mantis Template CSS -->
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/fonts/feather.css">
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="mb-4">Admin Login</h4>
                    
                    <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        Invalid username or password
                    </div>
                    <?php endif; ?>

                    <form action="/project-2a34/index.php?action=login" method="POST">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-4">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="/project-2a34/public/mantis-assets/js/plugins/popper.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/js/plugins/bootstrap.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/js/plugins/feather.min.js"></script>
</body>
</html>
