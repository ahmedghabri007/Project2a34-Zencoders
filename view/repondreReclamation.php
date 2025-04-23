<?php
require_once __DIR__ . '/../controller/ReclamationController.php';

$controller = new ReclamationController();

// Vérifier si l'ID de réclamation est présent dans l'URL
if (!isset($_GET['id_reclamation']) || empty($_GET['id_reclamation'])) {
    // Rediriger vers la page d'affichage des réclamations si l'ID n'est pas fourni
    header('Location: afficherReclamation.php');
    exit;
}

$id_reclamation = $_GET['id_reclamation'];
// Récupérer les détails de la réclamation
$reclamation = $controller->getReclamationById($id_reclamation);

// Vérifier si la réclamation existe
if (!$reclamation) {
    // Rediriger si la réclamation n'existe pas
    header('Location: afficherReclamation.php');
    exit;
}

// Traitement du formulaire de réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse'])) {
    $reponse = trim($_POST['reponse']);
    
    if (!empty($reponse)) {
        // Enregistrer la réponse dans la base de données
        $success = $controller->repondreReclamation($id_reclamation, $reponse);
        
        if ($success) {
            $message = "Votre réponse a été envoyée avec succès.";
            $messageType = "success";
            
            // Rediriger vers la page d'affichage après 2 secondes
            header("refresh:2;url=afficherReclamation.php");
        } else {
            $message = "Une erreur s'est produite lors de l'envoi de votre réponse.";
            $messageType = "error";
        }
    } else {
        $message = "La réponse ne peut pas être vide.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Répondre à une réclamation | Système de Réclamations</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon">
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" >
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="../assets/fonts/feather.css" >
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" >
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/material.css" >
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" >
  <link rel="stylesheet" href="../assets/css/style-preset.css" >
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
          <li class="pc-item" style="background-color: #f0f0f0; border-left: 4px solid #ff0000;">
            <a href="afficherReclamation.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-alert-circle"></i></span>
              <span class="pc-mtext">Reclamation</span>
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
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
              <span>Med Salim Hmili</span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header">
                <div class="d-flex mb-1">
                  <div class="flex-shrink-0">
                    <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar wid-35">
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Med Salim Hmili</h6>
                    <span>UI/UX Designer</span>
                  </div>
                  <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
                </div>
              </div>
            </div>
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
                <h5>Répondre à la réclamation #<?= $reclamation['id_reclamation'] ?></h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="afficherReclamation.php">Réclamations</a></li>
                <li class="breadcrumb-item">Répondre</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5><i class="ti ti-clipboard-text"></i> Détails de la réclamation</h5>
            </div>
            <div class="card-body">
              <?php if (isset($message)): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?>">
                  <?= $message ?>
                </div>
              <?php endif; ?>

              <div class="row mb-4 reclamation-details">
                <div class="col-md-6">
                  <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value"><?= htmlspecialchars($reclamation['email']) ?></span>
                  </div>
                  <div class="info-item">
                    <span class="label">Date:</span>
                    <span class="value"><?= $reclamation['date_reclamation'] ?></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="info-item">
                    <span class="label">Type:</span>
                    <span class="value"><?= htmlspecialchars($reclamation['type_reclamation']) ?></span>
                  </div>
                </div>
                <div class="col-md-12 mt-3">
                  <div class="info-item description">
                    <span class="label">Description:</span>
                    <div class="value-content">
                      <?= nl2br(htmlspecialchars($reclamation['Description'])) ?>
                    </div>
                  </div>
                </div>
              </div>

              <h5 class="mt-4 mb-3"><i class="ti ti-message-circle"></i> Votre réponse</h5>
              <?php if (isset($reclamation['reponse']) && !empty($reclamation['reponse'])): ?>
              <div class="alert alert-info">
                <h6><i class="ti ti-check-circle"></i> Cette réclamation a déjà reçu une réponse</h6>
                <div class="existing-response mt-3 p-3">
                  <strong>Réponse envoyée le <?= isset($reclamation['date_reponse']) ? $reclamation['date_reponse'] : 'N/A' ?> :</strong>
                  <p class="mt-2"><?= nl2br(htmlspecialchars($reclamation['reponse'])) ?></p>
                </div>
                <p class="mt-3">Vous pouvez mettre à jour cette réponse ci-dessous :</p>
              </div>
              <?php endif; ?>
              <form method="POST" action="">
                <div class="form-group mb-3">
                  <textarea name="reponse" class="form-control" rows="5" placeholder="Écrivez votre réponse ici..."><?= isset($reclamation['reponse']) ? htmlspecialchars($reclamation['reponse']) : '' ?></textarea>
                </div>
                <div class="d-flex">
                  <button type="submit" class="btn btn-success">Envoyer la réponse</button>
                  <a href="afficherReclamation.php" class="btn btn-secondary ms-2">Annuler</a>
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
</body>

<style>
  .reclamation-details {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
  }
  
  .info-item {
    margin-bottom: 15px;
  }
  
  .label {
    font-weight: 600;
    color: #555;
    margin-right: 10px;
    min-width: 80px;
    display: inline-block;
  }
  
  .value {
    color: #333;
  }
  
  .description .value-content {
    background-color: white;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
    margin-top: 10px;
    min-height: 100px;
  }
  
  .existing-response {
    background-color: #f8f9fa;
    border-left: 4px solid #2196f3;
    border-radius: 4px;
  }
</style>
</html> 