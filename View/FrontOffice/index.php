<?php 
// Inclure le header
include 'header.php';
include 'headert.php';

// Inclure la navbar
include 'navbar.php';

// Inclure les classes nécessaires
require_once '../../config.php';
require_once '../../Model/eventmodel.php';
require_once '../../Controller/eventcontroler.php';
require_once '../../Model/invitemodel.php'; // Changed to invitemodel
require_once '../../Controller/invitecontroller.php'; // Changed to invitecontroller

$inviteController = new InviteController(); // Instantiate it

// Créer une instance du contrôleur
$eventController = new eventcontroller();

// Récupérer tous les événements
$events = $eventController->getAllEvents();
?>

<!-- Contenu principal -->
<div class="container-xxl mt-5 py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="display-5 mb-5 text-primary">Événements à Venir</h1>
            <p class="lead">Ne manquez pas nos prochains événements exclusifs !</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): 
                    $invitations = $inviteController->getInvitesByEventId($event['id_event']);
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="card h-100 shadow border-0 event-card">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-3">
                                    <h5 class="card-title text-primary"><?= htmlspecialchars($event['EventName']) ?></h5>
                                    <p class="card-text mb-2">
                                        <i class="fa fa-calendar-alt text-primary me-2"></i> 
                                        <?= date('d/m/Y', strtotime($event['Date'])) ?> à <?= htmlspecialchars($event['Time']) ?>
                                    </p>
                                    <p class="card-text mb-2">
                                        <i class="fa fa-map-marker-alt text-primary me-2"></i> 
                                        <?= htmlspecialchars($event['Adresse']) ?>
                                    </p>
                                    <p class="card-text mb-2">
                                        <i class="fa fa-users text-primary me-2"></i> 
                                        <?= htmlspecialchars($event['AttendeLimit']) ?> participants
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-secondary">Activités :</h6>
                                    <p class="small"><?= nl2br(htmlspecialchars($event['Activities'])) ?></p>
                                </div>

                                <div class="mt-auto">
                                    <?php if (!empty($invitations)): ?>
                                        <div class="mb-3">
                                            <h6 class="text-secondary">Invitations disponibles :</h6>
                                            <ul class="list-unstyled small">
                                                
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <p class="small text-muted">Aucune invitation disponible</p>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between mb-3">
                                        <small class="text-muted"><i class="fa fa-star me-1"></i><?= htmlspecialchars($event['SponsoredBy']) ?></small>
                                        <small class="text-muted"><i class="fa fa-user-tie me-1"></i><?= htmlspecialchars($event['OrganisedBy']) ?></small>
                                    </div>
                                    
                                    <a href="event_invite_details.php?id_event=<?= $event['id_event'] ?>" class="btn btn-primary w-100">Voir Détails</a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info p-5">
                        <h4 class="alert-heading">Aucun événement pour l'instant</h4>
                        <p>Restez connecté pour découvrir nos futurs événements !</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

      

<?php 
// Inclure le footer
include 'footer.php';
include 'footert.php';
?>

