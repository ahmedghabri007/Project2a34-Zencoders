<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$pdo = Config::getConnexion();

$verifications = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docType = trim($_POST['document_type']);
    $docNumber = trim($_POST['document_number']);
    $status = trim($_POST['status']);

    try {
        if (!empty($docType)) {
            $stmt = $pdo->prepare("SELECT * FROM verification WHERE document_type LIKE ?");
            $stmt->execute(["%$docType%"]);
            $verifications = $stmt->fetchAll();
            if (empty($verifications)) {
                $message = "No records found for document type '$docType'.";
            }
        } elseif (!empty($docNumber)) {
            $stmt = $pdo->prepare("SELECT * FROM verification WHERE document_number LIKE ?");
            $stmt->execute(["%$docNumber%"]);
            $verifications = $stmt->fetchAll();
            if (empty($verifications)) {
                $message = "No records found for document number '$docNumber'.";
            }
        } elseif (!empty($status)) {
            $stmt = $pdo->prepare("SELECT * FROM verification WHERE status LIKE ?");
            $stmt->execute(["%$status%"]);
            $verifications = $stmt->fetchAll();
            if (empty($verifications)) {
                $message = "No records found with status '$status'.";
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
        <li class="nav-item active">
            <a class="nav-link" href="list_badge_upgrade.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Upgrade</span></a>
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

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Search for verified Accounts</h1>

                <!-- Search Form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label>Search by Document Type:</label>
                        <input type="text" name="document_type" class="form-control" placeholder="e.g., Passport, ID Card">
                    </div>
                    <div class="form-group">
                        <label>OR Search by Document Number:</label>
                        <input type="text" name="document_number" class="form-control" placeholder="Enter document number">
                    </div>
                    <div class="form-group">
                        <label>OR Search by Status:</label>
                        <input type="text" name="status" class="form-control" placeholder="e.g., verified, pending, rejected">
                    </div>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>

                <!-- Results -->
                <div class="results mt-4">
                    <?php if (!empty($message)): ?>
                        <p><?= htmlspecialchars($message) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($verifications)): ?>
                        <h2 class="h4 text-gray-800">Verification Results:</h2>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($verifications as $verify): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($verify['document_type'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($verify['document_number'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($verify['status'] ?? '') ?></td>
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