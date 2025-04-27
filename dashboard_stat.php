<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

// Fetch statistics (you can modify the queries as per your needs)
$pdo = Config::getConnexion();
$profileCount = $pdo->query("SELECT COUNT(*) FROM profile")->fetchColumn();
$matchCount = $pdo->query("SELECT COUNT(*) FROM matches")->fetchColumn();

// Fetch daily match statistics
$sql = "
    SELECT 
        DATE(date_created) AS match_date,
        COUNT(*) AS match_count
    FROM matches
    GROUP BY DATE(date_created)
    ORDER BY match_date DESC
";
$result = $pdo->query($sql);

// Calculate total matches
$totalMatches = 0;
$dailyMatches = [];
$dates = [];
$counts = [];

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $dailyMatches[] = $row;
        $totalMatches += $row['match_count'];
        $dates[] = $row['match_date'];
        $counts[] = $row['match_count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Statistics</title>
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
        <li class="nav-item">
            <a class="nav-link" href="dashboard_search.php">
                <i class="fas fa-fw fa-search"></i>
                <span>Search</span>
            </a>
        </li>
        <li class="nav-item active">
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
                <h1 class="h3 mb-4 text-gray-800">Statistics</h1>

                <div class="row">
                    <!-- Profile Count -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Profiles
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $profileCount ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Count -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Matches
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $matchCount ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Match Statistics -->
                <h2 class="h5 mb-4 text-gray-800">Daily Match Statistics</h2>
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Matches</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dailyMatches)): ?>
                            <?php foreach ($dailyMatches as $day): 
                                $percentage = ($day['match_count'] / $totalMatches) * 100;
                            ?>
                                <tr>
                                <td><?= htmlspecialchars($day['match_date'] ?? '') ?></td>
                                <td><?= htmlspecialchars($day['match_count'] ?? '') ?></td>
                                <td><span class="percentage"><?= number_format($percentage, 2) ?>%</span></td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No match data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Chart.js Diagram -->
                <div id="chart-container">
                    <canvas id="matchChart"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for the chart
    const dates = <?php echo json_encode($dates); ?>;
    const counts = <?php echo json_encode($counts); ?>;

    // Chart.js configuration
    const ctx = document.getElementById('matchChart').getContext('2d');
    const matchChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Matches per Day',
                data: counts,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
</body>
</html>
