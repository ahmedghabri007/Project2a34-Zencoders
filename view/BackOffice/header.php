<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BackOffice - Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../public/mantis-assets/images/favicon.ico" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="../../public/mantis-assets/css/style.css" />
    <link rel="stylesheet" href="../../public/mantis-assets/css/custom.css" />
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="../../public/mantis-assets/fonts/tabler-icons.min.css" />
    
    <!-- DataTables -->
    <link rel="stylesheet" href="../../public/mantis-assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />

    <!-- Custom CSS -->
    <style type="text/css">
        /* Enhanced Gradients */
        .pc-sidebar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        
        .pc-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        /* Card Enhancements */
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }

        /* Stats Card */
        .stats-card {
            background: linear-gradient(135deg, #4680ff 0%, #366cc9 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #2ed8b6 0%, #25a890 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #ffb64d 0%, #f5a43b 100%);
        }
        
        .stats-card.danger {
            background: linear-gradient(135deg, #ff5252 0%, #f04848 100%);
        }

        /* Navigation Enhancements */
        .pc-navbar .pc-item .pc-link {
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 0 10px;
        }
        
        .pc-navbar .pc-item .pc-link:hover {
            background: linear-gradient(135deg, #4680ff15 0%, #4680ff05 100%);
            transform: translateX(5px);
        }

        /* Button Styles */
        .btn {
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Table Enhancements */
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #e9ecef;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        /* Badge Enhancements */
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .badge i {
            margin-right: 4px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #2ed8b6 0%, #25a890 100%);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #ff5252 0%, #f04848 100%);
            color: white;
        }

        /* Breadcrumb */
        .page-header {
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "•";
            color: #4680ff;
        }

        /* Modal Enhancements */
        .modal-content {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card, .stats-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* DataTables Enhancement */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 5px;
            border: 1px solid #e9ecef;
            padding: 5px 10px;
            margin-left: 10px;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border-radius: 5px;
            border: 1px solid #e9ecef;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Mobile header ] start -->
    <div class="pc-mob-header pc-header">
        <div class="pcm-logo">
            <img src="../../public/mantis-assets/images/logo.png" alt="" class="logo logo-lg" />
        </div>
        <div class="pcm-toolbar">
            <a href="#!" class="pc-head-link" id="mobile-collapse">
                <i class="ti ti-menu-2"></i>
            </a>
            <a href="#!" class="pc-head-link" id="header-collapse">
                <i class="ti ti-more-vertical"></i>
            </a>
        </div>
    </div>
    <!-- [ Mobile header ] End -->

    <!-- [ navigation menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="dashboard.php" class="b-brand">
                    <img src="../../public/mantis-assets/images/logo.png" alt="" class="logo logo-lg" />
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="dashboard.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="categories.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-category-2"></i></span>
                            <span class="pc-mtext">Categories</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="threads.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-messages"></i></span>
                            <span class="pc-mtext">Threads</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="comments.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-message-dots"></i></span>
                            <span class="pc-mtext">Comments</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="/project-2a34/index.php?action=users" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <h5 class="mb-0">BackOffice Admin Panel</h5>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" href="../logout.php">
                            <i class="ti ti-logout"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pcoded-content">
            <!-- [ Breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10"><?php echo ucfirst(basename($_SERVER['PHP_SELF'], '.php')); ?></h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo ucfirst(basename($_SERVER['PHP_SELF'], '.php')); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row"><?php // This div will be closed in footer.php ?>
