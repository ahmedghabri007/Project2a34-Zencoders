<?php
// Include headers
include __DIR__ . '/header.php';
include __DIR__ . '/headerT.php';

// Include required files
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/InviteModel.php';
require_once __DIR__ . '/../../Controller/InviteController.php';
require_once __DIR__ . '/../../Controller/EventControler.php';
require_once __DIR__ . '/../../Model/eventmodel.php'; // Adjust path as needed

$inviteModel = new Invite();
$invites = $inviteModel->getAllInvites();
$eventController = new EventController();
$events = $eventController->getAllEvents();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inviteData = [
        'id_event' => $_POST['id_event'],
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'mail' => $_POST['mail'],
        'num_tele' => $_POST['num_tele']
    ];

    $inviteController = new InviteController();
    if ($inviteController->addInvite($inviteData)) {
        header("Location: ?success=1");
        exit();
    } else {
        $error = "Erreur lors de l'ajout de l'invité";
    }
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestion des Invités</h2>
        
        <!-- Add Invite Button (Triggers Modal) -->
        <a type="button" class="btn btn-primary" href="addinvite.php" >
            <i class="fas fa-plus me-2"></i>Ajouter un Invité
</a>
    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            ✅ Invité ajouté avec succès !
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            ❌ <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="../BackOffice/indexinvite.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Invites Grid -->
    <div class="row row-cols-1 row-cols-md-2 mb-2 row-cols-lg-3 g-4">
        <?php foreach ($invites as $invite): ?>
<!-- Inside your foreach loop where you display invites -->
<div class="col mb-2">
    <div class="card h-100 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><?= htmlspecialchars($invite['nom']) ?> <?= htmlspecialchars($invite['prenom']) ?></h5>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <h6 class="text-muted mb-1">Email</h6>
                <p><?= htmlspecialchars($invite['mail']) ?></p>
            </div>
            <div class="mb-3">
                <h6 class="text-muted mb-1">Téléphone</h6>
                <p><?= htmlspecialchars($invite['num_tele']) ?></p>
            </div>
            <div class="mb-3">
    <h6 class="text-muted mb-1">Événement</h6>
    <?php
    $eventModel = new Event();
    $event = $eventModel->getEventById($invite['id_event']);
    ?>
    <p>
        <?= htmlspecialchars($event['EventName'] ?? 'Unknown Event') ?>
     </p>
</div>
        </div>

        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between">
                <!-- Details Button -->
                <a href="invitedetails.php?id=<?= $invite['id_invite'] ?>" class="btn btn-outline-primary">
                    <i class="fas fa-info-circle me-2"></i>Détails
                </a>

                <a href="editinvite.php?id=<?= $invite['id_invite'] ?>" class="btn btn-outline-warning">
                    <i class="fas fa-info-circle me-2"></i>edit
                </a>
                
              
        </a>
                
                <!-- Delete Button -->
                <form method="POST" action="delete_invite.php" class="d-inline">
                    <input type="hidden" name="id_invite" value="<?= $invite['id_invite'] ?>">
                    <button type="submit" class="btn btn-outline-danger" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet invité?')">
                        <i class="fas fa-trash-alt me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

 
        <?php endforeach; ?>
    </div>
</div>

 

<?php include 'footer.php';
  ?>