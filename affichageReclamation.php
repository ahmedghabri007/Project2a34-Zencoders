<?php
require_once '../model/ReclamationModel.php';
require_once '../model/ResponseModel.php';

$reclamationModel = new ReclamationModel();
$responseModel = new ResponseModel();

// Récupérer toutes les réclamations
$reclamations = $reclamationModel->getAllReclamations();

// Fonction pour vérifier si une réclamation a une réponse
function hasResponse($id_reclamation, $responseModel) {
    $responses = $responseModel->getResponsesByReclamation($id_reclamation);
    return !empty($responses);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réclamations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .notification-badge {
            position: relative;
            display: inline-block;
        }
        .notification-badge[data-count]:after {
            content: attr(data-count);
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            min-width: 18px;
            text-align: center;
        }
        .reclamation-item {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .reclamation-item:hover {
            background-color: #f8f9fa;
        }
        .new-reclamation {
            background-color: #fff3cd;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Liste des Réclamations</h2>
            <div class="notification-badge" data-count="0">
                <i class="fas fa-bell fa-2x text-primary"></i>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reclamations as $reclamation): 
                        $hasResponse = hasResponse($reclamation['id_reclamation'], $responseModel);
                        $isNew = !$hasResponse;
                    ?>
                    <tr class="reclamation-item <?php echo $isNew ? 'new-reclamation' : ''; ?>" 
                        data-id="<?php echo $reclamation['id_reclamation']; ?>">
                        <td><?php echo $reclamation['id_reclamation']; ?></td>
                        <td><?php echo htmlspecialchars($reclamation['email']); ?></td>
                        <td><?php echo htmlspecialchars($reclamation['Description']); ?></td>
                        <td><?php echo htmlspecialchars($reclamation['type_reclamation']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($reclamation['date_reclamation'])); ?></td>
                        <td>
                            <?php if ($isNew): ?>
                                <span class="badge bg-warning">Nouvelle</span>
                            <?php else: ?>
                                <span class="badge bg-success">Répondu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="repondreReclamation.php?id=<?php echo $reclamation['id_reclamation']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-reply"></i> Répondre
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mettre à jour le compteur de notifications
            function updateNotificationCount() {
                const newReclamations = $('.new-reclamation').length;
                $('.notification-badge').attr('data-count', newReclamations);
            }

            // Mettre à jour le compteur au chargement
            updateNotificationCount();

            // Gérer le clic sur une réclamation
            $('.reclamation-item').click(function() {
                const id = $(this).data('id');
                window.location.href = `repondreReclamation.php?id=${id}`;
            });

            // Animation de la cloche
            $('.notification-badge').hover(
                function() {
                    $(this).find('i').addClass('fa-shake');
                },
                function() {
                    $(this).find('i').removeClass('fa-shake');
                }
            );
        });
    </script>
</body>
</html> 