<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Delete trustbadge
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM trustbadge WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: list_trustbadge.php");
    exit();
}

// Update trustbadge
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE trustbadge SET type_badge = ?, niveau_confiance = ?, status = ?, rejected_reason = ?, notes = ?, user_report = ?, report_status = ? WHERE id = ?");
    $stmt->execute([
        $_POST['type_badge'],
        $_POST['niveau_confiance'],
        $_POST['status'],
        $_POST['rejected_reason'],
        $_POST['notes'],
        $_POST['user_report'],
        $_POST['report_status'],
        $_POST['id']
    ]);
    header("Location: list_trustbadge.php");
    exit();
}

// Fetch trustbadges
$pdo = Config::getConnexion();
$badges = $pdo->query("SELECT * FROM trustbadge ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML Starts -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Trustbadge</title>
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

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Manage Trust Badges</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Verification ID</th>
                                        <th>Type</th>
                                        <th>Niveau</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($badges as $badge): ?>
                                        <tr>
                                            <form method="POST" action="list_trustbadge.php">
                                                <input type="hidden" name="id" value="<?= $badge['id'] ?>">
                                                <td><?= $badge['id'] ?></td>
                                                <td><?= $badge['verification_id'] ?></td>
                                                <td>
                                                    <select name="type_badge" class="form-control">
                                                        <option value="bronze" <?= $badge['type_badge'] == 'bronze' ? 'selected' : '' ?>>Bronze</option>
                                                        <option value="silver" <?= $badge['type_badge'] == 'silver' ? 'selected' : '' ?>>Silver</option>
                                                        <option value="gold" <?= $badge['type_badge'] == 'gold' ? 'selected' : '' ?>>Gold</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" name="niveau_confiance" class="form-control" value="<?= $badge['niveau_confiance'] ?>"></td>
                                                <td>
                                                    <select name="status" class="form-control">
                                                        <option value="approved" <?= $badge['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                                        <option value="rejected" <?= $badge['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                    <a href="?delete=<?= $badge['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php if (empty($badges)): ?>
                                <div class="text-center text-muted">No trust badges found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span>&copy; elev8talent</span>
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
