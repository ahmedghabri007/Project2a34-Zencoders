<?php
// Inclure le header
include 'header.php';

// Inclure la navbar

// Inclure les classes nécessaires
require_once '../../config.php';
require_once '../../Model/eventmodel.php';
require_once '../../Controller/eventcontroler.php';

// Créer une instance du contrôleur et récupérer l'événement
$eventController = new eventcontroller();

// Vérifier si l'ID de l'événement est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: events.php");
    exit();
}

$eventId = (int)$_GET['id'];
$event = $eventController->getEventById($eventId);

// Vérifier si l'événement existe
if (!$event) {
    header("Location: events.php");
    exit();
}

// Créer une instance de Ticket et récupérer les tickets pour l'événement
require_once '../../Model/ticketmodel.php';
$ticketModel = new Ticket();
$tickets = $ticketModel->getTicketsByEventId($eventId);
?>

<!-- Contenu principal -->
<div class="container-xxl py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="events.php">Événements</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($event['EventName']) ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Colonne principale -->
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="border-bottom pb-3 mb-4">
                    <h1 class="display-5 mb-3 text-primary"><?= htmlspecialchars($event['EventName']) ?></h1>
                    
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <span class="badge bg-primary rounded-pill">
                            <i class="fa fa-calendar-alt me-1"></i> <?= date('d F Y', strtotime($event['Date'])) ?>
                        </span>
                        <span class="badge bg-secondary rounded-pill">
                            <i class="fa fa-clock me-1"></i> <?= htmlspecialchars($event['Time']) ?>
                        </span>
                        <span class="badge bg-success rounded-pill">
                            <i class="fa fa-users me-1"></i> <?= htmlspecialchars($event['AttendeLimit']) ?> places
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-map-marker-alt text-primary me-2 fs-5"></i>
                        <h5 class="mb-0"><?= htmlspecialchars($event['Adresse']) ?></h5>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="mb-5">
                    <h3 class="mb-4"><i class="fa fa-info-circle text-primary me-2"></i>Description</h3>
                    <div class="bg-light p-4 rounded">
                        <?= nl2br(htmlspecialchars($event['Activities'])) ?>
                    </div>
                </div>

                <!-- Section Organisateurs -->
                <div class="mb-5">
                    <h3 class="mb-4"><i class="fa fa-user-tie text-primary me-2"></i>Organisation</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-3 mb-3 d-inline-block">
                                        <i class="fa fa-star fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Sponsorisé par</h5>
                                    <p class="card-text"><?= htmlspecialchars($event['SponsoredBy']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-3 mb-3 d-inline-block">
                                        <i class="fa fa-users fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Organisé par</h5>
                                    <p class="card-text"><?= htmlspecialchars($event['OrganisedBy']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-light rounded p-5 mb-5">
                    <h3 class="mb-4">Informations pratiques</h3>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 bg-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fa fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Date et heure</h6>
                            <p class="mb-0">
                                <?= date('l d F Y', strtotime($event['Date'])) ?><br>
                                <?= htmlspecialchars($event['Time']) ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 bg-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fa fa-map-marker-alt text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Lieu</h6>
                            <p class="mb-0"><?= htmlspecialchars($event['Adresse']) ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 bg-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fa fa-ticket-alt text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Places disponibles</h6>
                            <p class="mb-0"><?= htmlspecialchars($event['AttendeLimit']) ?> participants</p>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg"  disabled data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fa fa-user-plus me-2"></i>S'inscrire à l'événement
                        </button>
                    </div>
                </div>

                <!-- Tickets Display -->
                <?php if (!empty($tickets)): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <p>🎟️ <?= htmlspecialchars($ticket['ticket_type']) ?> — <?= htmlspecialchars($ticket['price']) ?> DT (<?= htmlspecialchars($ticket['quantity_available']) ?> available)</p>
                    <?php endforeach; ?>
                    <div class="d-grid gap-2 mb-4">
                    <a href="addticket.php?event_id=<?= $eventId ?>" class="btn btn-success">
                    <i class="fa fa-plus me-2"></i> Ajouter un ticket
                       </a>
                       <a href="editticket.php?event_id=<?= $eventId ?>" class="btn btn-warning">
                       <i class="fa fa-edit me-2"></i> Modifier un ticket
                         </a>
                         <a href="deleteticket.php?event_id=<?= $eventId ?>" class="btn btn-danger">
                              <i class="fa fa-trash me-2"></i> Supprimer un ticket
                         </a>
                     </div>

                <?php else: ?>
                    <p>No tickets available for this event.</p>
                <?php endif; ?>

                <!-- Carte de localisation -->
                <div class="bg-light rounded p-5">
                    <h3 class="mb-4">Localisation</h3>
                    <div class="ratio ratio-16x9">
                        <iframe 
                            src="https://maps.google.com/maps?q=<?= urlencode($event['Adresse']) ?>&output=embed" 
                            allowfullscreen 
                            loading="lazy">
                        </iframe>
                    </div>
                    <a href="https://maps.google.com/?q=<?= urlencode($event['Adresse']) ?>" 
                       target="_blank" 
                       class="btn btn-outline-primary mt-3 w-100">
                        <i class="fa fa-directions me-2"></i>Itinéraire
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Inclure le footer
include 'footer.php';
?>
