<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Delete verification
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM verification WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard_verification.php");
    exit();
}

// Update verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE verification SET document_type = ?, document_number = ?, status = ? WHERE id = ?");
    $stmt->execute([
        $_POST['document_type'],
        $_POST['document_number'],
        $_POST['status'],
        $_POST['id']
    ]);
    header("Location: dashboard_verification.php");
    exit();
}

// Fetch all
$pdo = Config::getConnexion();
$verifications = $pdo->query("SELECT * FROM verification ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Verification</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-text mx-3">Verification</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
    </ul>

    <!-- Content -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Manage Verifications</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Document Type</th>
                                        <th>Document Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($verifications as $doc): ?>
                                        <tr>
                                            <form method="POST" action="dashboard_verification.php">
                                                <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                                <td><?= $doc['id'] ?></td>
                                                <td>
                                                    <select name="document_type" class="form-control">
                                                        <option value="CNI" <?= $doc['document_type'] == 'CNI' ? 'selected' : '' ?>>CNI</option>
                                                        <option value="PASSEPORT" <?= $doc['document_type'] == 'PASSEPORT' ? 'selected' : '' ?>>Passeport</option>
                                                        <option value="PERMIS" <?= $doc['document_type'] == 'PERMIS' ? 'selected' : '' ?>>Permis</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="document_number" class="form-control" value="<?= htmlspecialchars($doc['document_number']) ?>"></td>
                                                <td>
                                                    <select name="status" class="form-control">
                                                        <option value="PENDING" <?= $doc['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                        <option value="APPROVED" <?= $doc['status'] == 'APPROVED' ? 'selected' : '' ?>>APPROVED</option>
                                                        <option value="REJECTED" <?= $doc['status'] == 'REJECTED' ? 'selected' : '' ?>>REJECTED</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                    <a href="?delete=<?= $doc['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php if (empty($verifications)): ?>
                                <div class="text-center text-muted">No verifications found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span>Copyright &copy; elev8talent</span>
                </div>
            </div>
        </footer>

    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
