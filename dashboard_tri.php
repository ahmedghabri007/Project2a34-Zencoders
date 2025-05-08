<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Create connection
$pdo = Config::getConnexion();

// Default sorting field
$sortField = 'document_type'; // default sort
$allowedFields = ['document_type', 'document_number', 'status'];

if (isset($_POST['sort_by']) && in_array($_POST['sort_by'], $allowedFields)) {
    $sortField = $_POST['sort_by'];
}

// Query to fetch sorted verifications
$sql = "SELECT * FROM verification ORDER BY $sortField ASC";
$result = $pdo->query($sql);
?>

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
            <div class="sidebar-brand-text mx-3">Elev8Talent</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="list_verification.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Verification</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard_search.php">
                    <i class="fas fa-fw fa-id-card"></i>
                    <span>Search Verification</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard_stat.php">
                    <i class="fas fa-fw fa-id-card"></i>
                    <span>Statistics Verification</span>
                </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="list_badge_upgrade.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Upgrade</span></a>
            </li>
            <li class="nav-item active">
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

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Sort Verifications</h1>

                <form method="post" action="" class="mb-4">
                    <div class="form-row justify-content-center">
                        <div class="col-md-4">
                            <select name="sort_by" class="form-control">
                                <option value="document_type" <?php if($sortField == 'document_type') echo 'selected'; ?>>Document Type</option>
                                <option value="document_number" <?php if($sortField == 'document_number') echo 'selected'; ?>>Document Number</option>
                                <option value="status" <?php if($sortField == 'status') echo 'selected'; ?>>Status</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Sort</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Document Type</th>
                            <th>Document Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->rowCount() > 0): ?>
                            <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['document_type'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['document_number'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No verification records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span>Copyright &copy; Elev8Talent 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>