<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// DELETE logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM badge_upgrade WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: list_badge_upgrade.php");
    exit();
}

// UPDATE logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE badge_upgrade SET new_document_type = ?, new_document_number = ?, request_status = ?, admin_feedback = ? WHERE id = ?");
    $stmt->execute([
        $_POST['new_document_type'],
        $_POST['new_document_number'],
        $_POST['request_status'],
        $_POST['admin_feedback'],
        $_POST['id']
    ]);
    header("Location: list_badge_upgrade.php");
    exit();
}

// Fetch all badge upgrade requests
$pdo = Config::getConnexion();
$badgeUpgrades = $pdo->query("SELECT * FROM badge_upgrade ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Badge Upgrade</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
        textarea { width: 100%; height: 60px; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-text mx-3">Elev8Talent</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="list_verification.php">
                <i class="fas fa-fw fa-id-card"></i>
                <span>Verification</span>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="dashboard_search.php">
                <i class="fas fa-fw fa-search"></i>
                <span>Search Verification</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard_stat.php">
                <i class="fas fa-fw fa-id-card"></i>
                <span>Statistics Verification</span>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="list_badge_upgrade.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Upgrade</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard_tri.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Sort</span></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="list_trustbadge.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>TrustBadge</span></a>
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
                <h1 class="h3 mb-4 text-gray-800">Manage Badge Upgrade Requests</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>New Document Type</th>
                                        <th>New Document Number</th>
                                        <th>Status</th>
                                        <th>Admin Feedback</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($badgeUpgrades as $upgrade): ?>
                                    <tr>
                                        <form method="POST" action="list_badge_upgrade.php">
                                            <input type="hidden" name="id" value="<?= $upgrade['id'] ?>">
                                            <td><?= $upgrade['id'] ?></td>
                                            <td><input type="text" name="new_document_type" class="form-control" value="<?= htmlspecialchars($upgrade['new_document_type']) ?>"></td>
                                            <td><input type="text" name="new_document_number" class="form-control" value="<?= htmlspecialchars($upgrade['new_document_number']) ?>"></td>
                                            <td>
                                                <select name="request_status" class="form-control">
                                                    <option value="PENDING" <?= $upgrade['request_status'] == 'PENDING' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="APPROVED" <?= $upgrade['request_status'] == 'APPROVED' ? 'selected' : '' ?>>Approved</option>
                                                    <option value="REJECTED" <?= $upgrade['request_status'] == 'REJECTED' ? 'selected' : '' ?>>Rejected</option>

                                                </select>
                                            </td>
                                            <td><textarea name="admin_feedback" class="form-control"><?= htmlspecialchars($upgrade['admin_feedback']) ?></textarea></td>
                                            <td>
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                <a href="?delete=<?= $upgrade['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php if (empty($badgeUpgrades)): ?>
                                <div class="text-center text-muted">No badge upgrade requests found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span>Copyright &copy; Elev8Talent</span>
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
