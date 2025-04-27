<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Create connection
$pdo = Config::getConnexion();

// Default sorting field
$sortField = 'fullname'; // default
if (isset($_POST['sort_by'])) {
    $sortOption = $_POST['sort_by'];
    if (in_array($sortOption, ['age', 'gender', 'location'])) {
        $sortField = $sortOption;
    }
}

// Query to fetch sorted profiles
$sql = "SELECT * FROM profile ORDER BY $sortField ASC";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Sort Profiles</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
        textarea { resize: vertical; }
        #chart-container {
            width: 80%;
            margin: 50px auto;
        }
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
                <span>Profiles</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard_matches.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Matches</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard_search.php">
                <i class="fas fa-fw fa-search"></i>
                <span>Search</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard_stat.php">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Statistics</span>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="dashboard_tri.php">
                <i class="fas fa-fw fa-sort"></i>
                <span>Sort</span>
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
                <h1 class="h3 mb-4 text-gray-800">Sort Profiles</h1>

                <form method="post" action="" class="mb-4">
                    <div class="form-row justify-content-center">
                        <div class="col-md-4">
                            <select name="sort_by" class="form-control">
                                <option value="age" <?php if($sortField == 'age') echo 'selected'; ?>>Age</option>
                                <option value="gender" <?php if($sortField == 'gender') echo 'selected'; ?>>Gender</option>
                                <option value="location" <?php if($sortField == 'location') echo 'selected'; ?>>Location</option>
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
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Location</th>
                            <th>Profession</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->rowCount() > 0): ?>
                            <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['fullname'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['age'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['location'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['profession'] ?? '') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No profiles found.</td>
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
