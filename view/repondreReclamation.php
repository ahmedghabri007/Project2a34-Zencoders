<?php
require_once __DIR__ . '/../controller/ReclamationController.php';
require_once __DIR__ . '/../controller/ResponseController.php';
require_once __DIR__ . '/../cnx.php';

$reclamationController = new ReclamationController();
$responseController = new ResponseController();

// Vérifier si l'ID de réclamation est présent dans l'URL
if (!isset($_GET['id_reclamation']) || empty($_GET['id_reclamation'])) {
    // Rediriger vers la page d'affichage des réclamations si l'ID n'est pas fourni
    header('Location: afficherReclamation.php');
    exit;
}

$id_reclamation = $_GET['id_reclamation'];
// Récupérer les détails de la réclamation
$reclamation = $reclamationController->getReclamationById($id_reclamation);

// Vérifier si la réclamation existe
if (!$reclamation) {
    // Rediriger si la réclamation n'existe pas
    header('Location: afficherReclamation.php');
    exit;
}

// Récupérer les réponses existantes pour cette réclamation
$responses = $responseController->getResponsesByReclamation($id_reclamation);

// Récupérer l'email de l'admin connecté
$db = config::getConnexion();
try {
    // D'abord essayer de trouver un admin
    $stmt = $db->prepare("SELECT email FROM accounts WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si aucun admin n'est trouvé, prendre le premier utilisateur
    if (!$admin) {
        $stmt = $db->prepare("SELECT email FROM accounts LIMIT 1");
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    $email_admin = $admin['email'] ?? null;

    if (!$email_admin) {
        // Si aucun utilisateur n'est trouvé, créer un utilisateur admin par défaut
        $default_email = 'admin@system.com';
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO accounts (email, password, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$default_email, $default_password]);
        
        $email_admin = $default_email;
    }
} catch (Exception $e) {
    die("Erreur lors de la récupération de l'email admin: " . $e->getMessage());
}

// Traitement du formulaire de réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse'])) {
    $reponse = trim($_POST['reponse']);
    
    if (!empty($reponse)) {
        // Enregistrer la réponse dans la base de données
        $success = $responseController->createResponse($id_reclamation, $email_admin, $reponse);
        
        if ($success) {
            $message = "Votre réponse a été envoyée avec succès.";
            $messageType = "success";
            
            // Rediriger vers la page d'historique après 2 secondes
            header("refresh:2;url=historiqueReponses.php");
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

  <!-- Validation JavaScript -->
  <script src="js/validation.js"></script>
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
              <?php if (!empty($responses)): ?>
              <div class="alert alert-info">
                <h6><i class="ti ti-history"></i> Historique des réponses</h6>
                <div class="responses-history mt-3">
                  <?php foreach ($responses as $response): ?>
                    <div class="response-item p-3 mb-3" style="background-color: #f8f9fa; border-left: 4px solid #2196f3; border-radius: 4px;">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Réponse du <?= $response['date_reponse'] ?></small>
                        <div>
                          <a href="modifierReponse.php?id=<?= $response['id_reponse'] ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit"></i>
                          </a>
                          <a href="supprimerReponse.php?id=<?= $response['id_reponse'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">
                            <i class="ti ti-trash"></i>
                          </a>
                        </div>
                      </div>
                      <p class="mb-0"><?= nl2br(htmlspecialchars($response['contenu_reponse'])) ?></p>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
              <form method="POST" action="" class="reponse-form" id="reponseForm">
                <div class="form-group mb-3">
                  <textarea name="reponse" id="contenu" class="form-control" rows="5" placeholder="Écrivez votre réponse ici..."></textarea>
                  <div id="contenu-error" class="error-message"></div>
                </div>
                <div class="d-flex">
                  <button type="submit" class="btn btn-success btn-submit">Envoyer la réponse</button>
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
  
  .responses-history {
    max-height: 300px;
    overflow-y: auto;
  }
  
  .response-item {
    transition: all 0.3s ease;
  }
  
  .response-item:hover {
    background-color: #e9ecef !important;
  }

  .reponse-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .reponse-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
  }

  .reponse-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
  }

  .reponse-form .btn-submit {
    width: 100%;
    padding: 10px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
  }

  .reponse-form .btn-submit:hover {
    background-color: #1976d2;
  }

  .error-message {
    color: red;
    font-size: 0.9em;
    margin-top: -10px;
    margin-bottom: 10px;
  }
</style>
</html> 