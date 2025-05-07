<?php
require_once __DIR__ . '/../controller/ResponseController.php';

$responseController = new ResponseController();

// Vérifier si l'ID de la réponse est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: historiqueReponses.php');
    exit;
}

$id_reponse = $_GET['id'];

// Supprimer la réponse
$success = $responseController->deleteResponse($id_reponse);

if ($success) {
    $message = "La réponse a été supprimée avec succès.";
    $messageType = "success";
} else {
    $message = "Une erreur s'est produite lors de la suppression de la réponse.";
    $messageType = "error";
}

// Rediriger vers la page d'historique après 2 secondes
header("refresh:2;url=historiqueReponses.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Supprimer une Réponse | Système de Réclamations</title>
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
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?>">
                                <?= $message ?>
                                <br>
                                <small>Redirection vers la page d'historique des réponses...</small>
                            </div>
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
</body>
</html> 