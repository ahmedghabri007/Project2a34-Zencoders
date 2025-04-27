<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$pdo = Config::getConnexion();

$matches = [];
$profiles = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searcherName = trim($_POST['searcher_name']);
    $profileName = trim($_POST['fullname']);

    try {
        if (!empty($searcherName)) {
            // Search for matches by searcher_name
            $stmt = $pdo->prepare("SELECT * FROM matches WHERE searcher_name LIKE ?");
            $stmt->execute(["%$searcherName%"]);
            $matches = $stmt->fetchAll();
            if (empty($matches)) {
                $message = "No matches found for searcher name '$searcherName'.";
            }
        } elseif (!empty($profileName)) {
            // Search for profiles by fullname
            $stmt = $pdo->prepare("SELECT * FROM profile WHERE fullname LIKE ?");
            $stmt->execute(["%$profileName%"]);
            $profiles = $stmt->fetchAll();
            if (empty($profiles)) {
                $message = "No profiles found for full name '$profileName'.";
            }
        } else {
            $message = "Please enter a search term.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Search</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
        textarea { resize: vertical; }
        .percentage {
            color: #007bff; /* blue */
            font-weight: bold;
        }
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
        <li class="nav-item active">
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
        <li class="nav-item">
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
                <h1 class="h3 mb-4 text-gray-800">Search for a Match or Profile</h1>

                <!-- Search Form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label>Search by Searcher Name (Match):</label>
                        <input type="text" name="searcher_name" class="form-control" placeholder="Enter searcher name">
                    </div>
                    <div class="form-group">
                        <label>OR Search by Profile Full Name:</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Enter full name">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <!-- Results -->
                <div class="results mt-4">
                    <?php if (!empty($message)): ?>
                        <p><?= htmlspecialchars($message) ?></p>
                    <?php endif; ?>

                    <!-- Display Matches -->
                    <?php if (!empty($matches)): ?>
                        <h2 class="h4 text-gray-800">Match Results:</h2>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID Match</th>
                                    <th>ID Profile</th>
                                    <th>Searcher Name</th>
                                    <th>Description</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($matches as $match): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($match['idmatch'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($match['idprofile'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($match['searcher_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($match['description'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($match['date_created'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif (!empty($profiles)): ?>
                        <!-- Display Profiles -->
                        <h2 class="h4 text-gray-800">Profile Results:</h2>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID Profile</th>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Location</th>
                                    <th>Profession</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($profiles as $profile): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($profile['idprofile'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($profile['fullname'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($profile['age'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($profile['gender'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($profile['location'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($profile['profession'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
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
