<?php
require_once __DIR__ . '/../controller/ResponseController.php';

$responseController = new ResponseController();

// Vérifier si l'ID de la réponse est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: historiqueReponses.php');
    exit;
}

$id_reponse = $_GET['id'];
$response = $responseController->getResponseById($id_reponse);

// Vérifier si la réponse existe
if (!$response) {
    header('Location: historiqueReponses.php');
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu_reponse'])) {
    $contenu_reponse = trim($_POST['contenu_reponse']);
    
    if (!empty($contenu_reponse)) {
        $success = $responseController->updateResponse($id_reponse, $contenu_reponse);
        
        if ($success) {
            $message = "La réponse a été mise à jour avec succès.";
            $messageType = "success";
            // Rafraîchir les données de la réponse
            $response = $responseController->getResponseById($id_reponse);
        } else {
            $message = "Une erreur s'est produite lors de la mise à jour de la réponse.";
            $messageType = "error";
        }
    } else {
        $message = "Le contenu de la réponse ne peut pas être vide.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier une Réponse | Système de Réclamations</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- [Favicon] icon -->
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style-preset.css">

    <!-- Validation JavaScript -->
    <script src="js/validation.js"></script>

    <style>
    /* ... existing code ... */
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
                    <li class="pc-item" style="background-color: #f0f0f0; border-left: 4px solid #ff0000;">
                        <a href="historiqueReponses.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Historique des Réponses</span>
                        </a>
                    </li>
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
                                <h5>Modifier une Réponse</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="historiqueReponses.php">Historique des Réponses</a></li>
                                <li class="breadcrumb-item">Modifier</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-edit"></i> Modifier la Réponse</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($message)): ?>
                                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?>">
                                    <?= $message ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" class="reponse-form" id="reponseForm">
                                <div class="form-group mb-3">
                                    <textarea name="contenu_reponse" id="contenu" class="form-control" rows="5" placeholder="Écrivez votre réponse ici..."><?= htmlspecialchars($response['contenu_reponse']) ?></textarea>
                                    <div id="contenu-error" class="error-message"></div>
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success btn-submit">Modifier la réponse</button>
                                    <a href="historiqueReponses.php" class="btn btn-secondary ms-2">Annuler</a>
                                </div>
                            </form>
                        </div>
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
        $(document).ready(function() {
            // Fonction de validation
            function validateForm() {
                let isValid = true;
                const contenu = $('#contenu').val().trim();

                // Validation du contenu
                if (contenu === '') {
                    $('#contenu-error').text('Le contenu de la réponse est obligatoire');
                    isValid = false;
                } else if (contenu.length < 10) {
                    $('#contenu-error').text('Le contenu doit contenir au moins 10 caractères');
                    isValid = false;
                } else {
                    $('#contenu-error').text('');
                }

                return isValid;
            }

            $('#reponseForm').on('submit', function(event) {
                event.preventDefault(); // Empêche le rechargement de la page

                if (!validateForm()) {
                    return false;
                }

                var formData = $(this).serialize();
                formData += '&id=<?= $id_reponse ?>'; // Ajout de l'ID de la réponse

                $.ajax({
                    type: 'POST',
                    url: '', // Envoi à la même page
                    data: formData,
                    success: function(response) {
                        alert('Réponse mise à jour avec succès !');
                        window.location.href = 'historiqueReponses.php';
                    },
                    error: function() {
                        alert('Une erreur est survenue lors de la mise à jour de la réponse.');
                    }
                });
            });

            // Validation en temps réel pour le contenu
            $('#contenu').on('input', function() {
                const contenu = $(this).val().trim();
                if (contenu === '') {
                    $('#contenu-error').text('Le contenu de la réponse est obligatoire');
                } else if (contenu.length < 10) {
                    $('#contenu-error').text('Le contenu doit contenir au moins 10 caractères');
                } else {
                    $('#contenu-error').text('');
                }
            });
        });
    </script>
</body>
</html> 