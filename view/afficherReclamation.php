<?php
require_once __DIR__ . '/../controller/ReclamationController.php';
require_once __DIR__ . '/../controller/ResponseController.php';

$reclamationController = new ReclamationController();
$responseController = new ResponseController();

// Récupérer le terme de recherche
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Récupérer le critère de tri
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Récupérer les réclamations avec filtrage si nécessaire
if (!empty($searchTerm)) {
    if (is_numeric($searchTerm)) {
        $reclamations = $reclamationController->getReclamationById($searchTerm);
        if ($reclamations) {
            $reclamations = [$reclamations];
        } else {
            $reclamations = [];
        }
    } else {
        $reclamations = $reclamationController->getReclamationsByEmail($searchTerm);
    }
} else {
    $reclamations = $reclamationController->getReclamations();
}

// Trier les réclamations
if (!empty($reclamations)) {
    usort($reclamations, function($a, $b) use ($sortBy, $sortOrder) {
        if ($sortBy === 'id') {
            $valueA = $a['id_reclamation'];
            $valueB = $b['id_reclamation'];
        } else if ($sortBy === 'type') {
            $valueA = $a['type_reclamation'];
            $valueB = $b['type_reclamation'];
        } else if ($sortBy === 'date') {
            $valueA = strtotime($a['date_reclamation']);
            $valueB = strtotime($b['date_reclamation']);
        }
        
        if ($sortOrder === 'asc') {
            return $valueA <=> $valueB;
        } else {
            return $valueB <=> $valueA;
        }
    });
}

// Pour chaque réclamation, vérifier si elle a des réponses
foreach ($reclamations as &$reclamation) {
    $responses = $responseController->getResponsesByReclamation($reclamation['id_reclamation']);
    $reclamation['has_response'] = !empty($responses);
    $reclamation['status'] = $reclamation['has_response'] ? 'Répondu' : 'En attente';
    $reclamation['status_class'] = $reclamation['has_response'] ? 'success' : 'warning';
}

// Fonction pour générer l'URL de tri
function getSortUrl($field) {
    global $sortBy, $sortOrder, $searchTerm;
    $newOrder = ($sortBy === $field && $sortOrder === 'asc') ? 'desc' : 'asc';
    $params = [
        'sort' => $field,
        'order' => $newOrder
    ];
    if (!empty($searchTerm)) {
        $params['search'] = $searchTerm;
    }
    return '?' . http_build_query($params);
}

// Fonction pour générer l'icône de tri
function getSortIcon($field) {
    global $sortBy, $sortOrder;
    if ($sortBy !== $field) {
        return '<i class="ti ti-arrows-sort"></i>';
    }
    return $sortOrder === 'asc' ? '<i class="ti ti-sort-ascending"></i>' : '<i class="ti ti-sort-descending"></i>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Liste des Réclamations | Système de Réclamations</title>
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
                    <li class="pc-item">
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
                                <h5>Liste des Réclamations</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                                <li class="breadcrumb-item">Réclamations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-alert-circle"></i> Réclamations</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <form class="d-flex" method="GET" action="afficherReclamation.php" style="width: 300px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Rechercher par ID ou email..." value="<?= htmlspecialchars($searchTerm) ?>">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="ti ti-search"></i>
                                        </button>
                                    </div>
                                </form>
                                <div>
                                    <a href="ajouterReclamation.php" class="btn btn-primary me-2">
                                        <i class="ti ti-plus"></i> Ajouter une réclamation
                                    </a>
                                    <a href="../controller/ExportPDF.php" class="btn btn-success">
                                        <i class="ti ti-file-export"></i> Exportation
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($reclamations)): ?>
                                <div class="alert alert-info">
                                    Aucune réclamation n'a été trouvée.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <a href="<?= getSortUrl('id') ?>" class="text-decoration-none">
                                                        ID <?= getSortIcon('id') ?>
                                                    </a>
                                                </th>
                                                <th>Email</th>
                                                <th>
                                                    <a href="<?= getSortUrl('type') ?>" class="text-decoration-none">
                                                        Type <?= getSortIcon('type') ?>
                                                    </a>
                                                </th>
                                                <th>Description</th>
                                                <th>
                                                    <a href="<?= getSortUrl('date') ?>" class="text-decoration-none">
                                                        Date <?= getSortIcon('date') ?>
                                                    </a>
                                                </th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reclamations as $reclamation): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($reclamation['id_reclamation']) ?></td>
                                                    <td><?= htmlspecialchars($reclamation['email']) ?></td>
                                                    <td><?= htmlspecialchars($reclamation['type_reclamation']) ?></td>
                                                    <td><?= nl2br(htmlspecialchars($reclamation['Description'])) ?></td>
                                                    <td><?= $reclamation['date_reclamation'] ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $reclamation['status_class'] ?>">
                                                            <?= $reclamation['status'] ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="repondreReclamation.php?id_reclamation=<?= $reclamation['id_reclamation'] ?>" class="btn btn-primary btn-sm">
                                                            <i class="ti ti-message-circle"></i> Répondre
                                                        </a>
                                                        <a href="modifierReclamation.php?id_reclamation=<?= $reclamation['id_reclamation'] ?>" class="btn btn-warning btn-sm">
                                                            <i class="ti ti-edit"></i> Modifier
                                                        </a>
                                                        <button onclick="supprimerReclamation(<?= $reclamation['id_reclamation'] ?>)" class="btn btn-danger btn-sm">
                                                            <i class="ti ti-trash"></i> Supprimer
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
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
    
    <script>
    function supprimerReclamation(id_reclamation) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "traitementSupprimerReclamation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response.trim() === 'success') {
                        // Supprime la ligne du tableau
                        var row = document.getElementById('reclamation_' + id_reclamation);
                        if (row) {
                            row.remove();
                        }
                        // Affiche un message de succès
                        alert('Réclamation supprimée avec succès.');
                    } else {
                        alert('Erreur lors de la suppression de la réclamation.');
                    }
                }
            };
            xhr.send("id_reclamation=" + id_reclamation);
        }
    }
    </script>

    <script>layout_change('light');</script>
    <script>change_box_container('false');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>
    <script>font_change("Public-Sans");</script>
</body>
</html>


