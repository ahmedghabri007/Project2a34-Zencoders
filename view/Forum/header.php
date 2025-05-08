<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forum - ZenCoders</title>
    
    <!-- Favicon -->
    <link href="/project-2a34/public/img/favicon.ico" rel="icon" />

    <!-- Mantis Template CSS -->
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/css/style.css" />
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/css/custom.css" />
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="/project-2a34/public/mantis-assets/fonts/tabler-icons.min.css" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="/project-2a34/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="/project-2a34/public/lib/animate/animate.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="/project-2a34/public/mantis-assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link href="/project-2a34/public/css/style.css" rel="stylesheet" />
    
    <!-- Google Font for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    
    <!-- RTL Support for Arabic -->
    <style type="text/css">
        /* RTL Support */
        .rtl {
            direction: rtl;
            text-align: right;
            font-family: 'Tajawal', Arial, sans-serif;
        }
        
        /* Translation Styling */
        .translation-result {
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn {
            margin-right: 2px;
        }
        
        /* Loading animation for translations */
        .translation-loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 123, 255, 0.3);
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Mantis Integration Styles */
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-bottom: 1px solid #e9ecef;
        }
        
        .btn {
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            <a href="/project-2a34/index.php" class="b-brand">
                <img src="/project-2a34/public/mantis-assets/images/logo.png" alt="" class="logo logo-lg" />
            </a>
        </div>
        <div class="pcm-toolbar">
            <a href="#!" class="pc-head-link" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
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
                <a href="/project-2a34/index.php" class="b-brand">
                    <img src="/project-2a34/public/mantis-assets/images/logo.png" alt="" class="logo logo-lg" />
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="/project-2a34/index.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Forums</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="/project-2a34/index.php?action=create" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plus"></i></span>
                            <span class="pc-mtext">Create Thread</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="/project-2a34/index.php?action=analytics" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
                            <span class="pc-mtext">Analytics</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="/project-2a34/view/BackOffice/dashboard.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-settings"></i></span>
                            <span class="pc-mtext">Admin Panel</span>
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
                        <h5 class="mb-0">ZenCoders Forum</h5>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" href="#">
                            <i class="ti ti-search"></i>
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
