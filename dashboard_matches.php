<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Delete match with profile ID verification
if (isset($_GET['delete'], $_GET['idprofile'])) {
    $idmatch = intval($_GET['delete']);
    $idprofile = intval($_GET['idprofile']);

    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM matches WHERE idmatch = ? AND idprofile = ?");
    $stmt->execute([$idmatch, $idprofile]);
    header("Location: dashboard_matches.php");
    exit();
}

// Update match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE matches SET searcher_name = ?, description = ? WHERE idmatch = ?");
    $stmt->execute([
        $_POST['searcher_name'],
        $_POST['description'],
        $_POST['idmatch']
    ]);
    header("Location: dashboard_matches.php");
    exit();
}

// Fetch all matches joined with profile name
$pdo = Config::getConnexion();
$sql = "
    SELECT m.idmatch, m.searcher_name, m.description, m.date_created, m.idprofile, p.fullname
    FROM matches m
    JOIN profile p ON m.idprofile = p.idprofile
    ORDER BY m.idmatch DESC
";
$matches = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Matches</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        textarea { resize: vertical; }
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
            <a class="nav-link" href="dashboard_profiles.php">
                <i class="fas fa-fw fa-user"></i>
                <span>Profiles</span></a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="dashboard_matches.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Matches</span>
            </a>
        </li>
    </ul>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"></nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Manage Matches</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Searcher Name</th>
                                        <th>Description</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($matches as $match): ?>
                                        <tr>
                                            <form method="POST" action="dashboard_matches.php">
                                                <input type="hidden" name="idmatch" value="<?= $match['idmatch'] ?>">
                                                <td><?= $match['idmatch'] ?></td>
                                                <td><?= htmlspecialchars($match['fullname']) ?></td>
                                                <td><input type="text" name="searcher_name" class="form-control" value="<?= htmlspecialchars($match['searcher_name']) ?>"></td>
                                                <td><textarea name="description" class="form-control"><?= htmlspecialchars($match['description']) ?></textarea></td>
                                                <td><?= $match['date_created'] ?></td>
                                                <td>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                    <a href="?delete=<?= $match['idmatch'] ?>&idprofile=<?= $match['idprofile'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this match?')">Delete</a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if (empty($matches)): ?>
                                <div class="text-center text-muted">No matches found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
