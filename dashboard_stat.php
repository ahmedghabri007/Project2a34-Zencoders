<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$pdo = Config::getConnexion();
$status = $_GET['status'] ?? '';

$dates = [];
$counts = [];

if ($status) {
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) AS day, COUNT(*) AS count
        FROM verification
        WHERE status = :status
        GROUP BY DATE(created_at)
        ORDER BY day DESC
    ");
    $stmt->execute(['status' => $status]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $row) {
        $dates[] = $row['day'];
        $counts[] = $row['count'];
    }
}
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
            <a class="nav-link" href="dashboard_verification.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Verification</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard_search.php">
                    <i class="fas fa-fw fa-id-card"></i>
                    <span>Search Verification</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="dashboard_stat.php">
                    <i class="fas fa-fw fa-id-card"></i>
                    <span>Statistics Verification</span>
                </a>
            </li>
            <li class="nav-item active">
            <a class="nav-link" href="list_badge_upgrade.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Upgrade</span></a>
            </li>
            </ul>
<div class="container mt-5">
        <h1 class="h3 mb-4 text-gray-800">Verification Statistics</h1>

    <form method="GET" action="">
        <div class="form-group">
            <label for="status">Select Verification Status:</label>
            <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                <option value="">-- Select Status --</option>
                <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
    </form>

    <?php if ($status): ?>
        <div id="chart-container">
            <canvas id="verificationChart"></canvas>
        </div>
    <?php else: ?>
        <p class="text-muted">Please select a status to view statistics.</p>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if ($status): ?>
<script>
    const dates = <?= json_encode($dates) ?>;
    const counts = <?= json_encode($counts) ?>;

    const ctx = document.getElementById('verificationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Verifications per Day',
                data: counts,
                backgroundColor: '#4e73df',
                borderColor: '#2e59d9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Number of Verifications'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
</script>
<?php endif; ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>