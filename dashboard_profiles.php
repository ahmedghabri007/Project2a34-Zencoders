<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Delete profile
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM profile WHERE idprofile = ?");
    $stmt->execute([$id]);
    header("Location: dashboard_profiles.php");
    exit();
}

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE profile SET fullname = ?, age = ?, gender = ?, location = ?, profession = ?, biography = ?, interests = ?, phone = ? WHERE idprofile = ?");
    $stmt->execute([
        $_POST['fullname'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['location'],
        $_POST['profession'],
        $_POST['biography'],
        $_POST['interests'],
        $_POST['phone'],
        $_POST['idprofile']
    ]);
    header("Location: dashboard_profiles.php");
    exit();
}

// Fetch all profiles
$pdo = Config::getConnexion();
$profiles = $pdo->query("SELECT * FROM profile ORDER BY idprofile DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Profiles</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
        textarea { resize: vertical; }
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
        <li class="nav-item active">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Profiles</span></a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="dashboard_matches.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Matches</span>
        </a>
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
                <h1 class="h3 mb-4 text-gray-800">Manage Profiles</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Location</th>
                                        <th>Profession</th>
                                        <th>Biography</th>
                                        <th>Interests</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profiles as $profile): ?>
                                        <tr>
                                            <form method="POST" action="dashboard_profiles.php">
                                                <input type="hidden" name="idprofile" value="<?= $profile['idprofile'] ?>">
                                                <td><?= $profile['idprofile'] ?></td>
                                                <td><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($profile['fullname'] ?? '') ?>"></td>
                                                <td><input type="number" name="age" class="form-control" value="<?= htmlspecialchars($profile['age'] ?? '') ?>"></td>
                                                <td>
                                                    <select name="gender" class="form-control">
                                                        <option value="Male" <?= $profile['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                                        <option value="Female" <?= $profile['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="location" class="form-control" value="<?= htmlspecialchars($profile['location'] ?? '') ?>"></td>
                                                <td><input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($profile['profession'] ?? '') ?>"></td>
                                                <td><textarea name="biography" class="form-control"><?= htmlspecialchars($profile['biography'] ?? '') ?></textarea></td>
                                                <td><textarea name="interests" class="form-control"><?= htmlspecialchars($profile['interests'] ?? '') ?></textarea></td>
                                                <td><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars(string: $profile['phone'] ?? '') ?>"></td>
                                                <td>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                    <a href="?delete=<?= $profile['idprofile'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this profile?')">Delete</a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if (empty($profiles)): ?>
                                <div class="text-center text-muted">No profiles found.</div>
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
