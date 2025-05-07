<?php
require_once __DIR__ . '/../controller/ReclamationController.php';
$reclamationController = new ReclamationController();
$reclamations = $reclamationController->getReclamations();

// Statistiques par type
$stats = [];
foreach ($reclamations as $rec) {
    $type = $rec['type_reclamation'];
    if (!isset($stats[$type])) {
        $stats[$type] = 0;
    }
    $stats[$type]++;
}

// Statistiques par mois
$statsMois = [];
foreach ($reclamations as $rec) {
    $mois = date('Y-m', strtotime($rec['date_reclamation']));
    if (!isset($statsMois[$mois])) {
        $statsMois[$mois] = 0;
    }
    $statsMois[$mois]++;
}
ksort($statsMois);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Statistiques des Réclamations</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style-preset.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container-stat { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08); padding: 32px; }
        h2 { text-align: center; margin-bottom: 32px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { padding: 12px 16px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f5f5f5; }
        .btn-back { display: inline-block; margin-top: 16px; padding: 8px 18px; background: #2196f3; color: #fff; border-radius: 4px; text-decoration: none; }
        .btn-back:hover { background: #1976d2; }
        .chart-container { display: flex; flex-wrap: wrap; gap: 40px; justify-content: center; margin-bottom: 32px; }
        .chart-box { flex: 1 1 350px; max-width: 400px; background: #fafbfc; border-radius: 8px; padding: 24px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }
    </style>
</head>
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="../dashboard/index.html" class="b-brand text-primary">
                    <img src="../assets/images/logo.png" class="img-fluid logo-lg" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="../dashboard/index.html" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Pages</label>
                        <i class="ti ti-news"></i>
                    </li>
                    <li class="pc-item">
                        <a href="afficherReclamation.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-alert-circle"></i></span>
                            <span class="pc-mtext">Réclamations</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="historiqueReponses.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Historique des Réponses</span>
                        </a>
                    </li>
                    <li class="pc-item" style="background-color: #f0f0f0; border-left: 4px solid #ff0000;">
                        <a href="statistiquesReclamation.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
                            <span class="pc-mtext">Statistiques</span>
                        </a>
                    </li>
<<<<<<< HEAD
=======
                    <li class="pc-item">
                        <a href="mobile.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-device-mobile"></i></span>
                            <span class="pc-mtext">Mobile</span>
                        </a>
                    </li>
>>>>>>> 249843f (version finale)
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5>Statistiques des Réclamations</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                                <li class="breadcrumb-item">Statistiques</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="container-stat">
                        <h2>Statistiques des réclamations</h2>
                        <div class="chart-container">
                            <div class="chart-box">
                                <h3 style="text-align:center;">Par type</h3>
                                <canvas id="pieChart"></canvas>
                            </div>
                            <div class="chart-box">
                                <h3 style="text-align:center;">Par mois</h3>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type de réclamation</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $type => $count): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($type) ?></td>
                                        <td><?= $count ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="afficherReclamation.php" class="btn-back"><i class="ti ti-arrow-left"></i> Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/fonts/custom-font.js"></script>
    <script src="../assets/js/pcoded.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>
    <script>layout_change('light');</script>
    <script>change_box_container('false');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>
    <script>font_change("Public-Sans");</script>
    <script>
        // Données pour le camembert
        const pieLabels = <?= json_encode(array_keys($stats)) ?>;
        const pieData = <?= json_encode(array_values($stats)) ?>;
        // Données pour le bar chart
        const barLabels = <?= json_encode(array_keys($statsMois)) ?>;
        const barData = <?= json_encode(array_values($statsMois)) ?>;
        // Couleurs dynamiques
        const colors = [
            '#2196f3', '#ff9800', '#4caf50', '#e91e63', '#9c27b0', '#ffeb3b', '#00bcd4', '#8bc34a', '#f44336', '#607d8b'
        ];
        // Pie Chart
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: colors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: false }
                }
            }
        });
        // Bar Chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Nombre de réclamations',
                    data: barData,
                    backgroundColor: '#2196f3',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, stepSize: 1 }
                }
            }
        });
    </script>
</body>
</html> 