<?php 
include __DIR__ . '/header.php';
 
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/InviteModel.php';
require_once __DIR__ . '/../../Controller/InviteController.php';
require_once __DIR__ . '/../../Model/EventModel.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Add this for QR code

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
 use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
//use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevel;

if (!isset($_GET['id'])) {
    header("Location: indexinvite.php");
    exit();
}

$inviteController = new InviteController();
$invite = $inviteController->getInviteById($_GET['id']);

if (!$invite) {
    header("Location: indexinvite.php");
    exit();
}

// Get event details
$eventModel = new Event();
$event = $eventModel->getEventById($invite['id_event']);

// Generate QR code data
$qrData = [
    'Invite ID' => $invite['id_invite'],
    'Name' => $invite['nom'] . ' ' . $invite['prenom'],
    'Email' => $invite['mail'],
    'Phone' => $invite['num_tele'],
    'Event' => $event['EventName'],
    'Date' => $event['Date'],
    'Location' => $event['Adresse']
];

$qrContent = json_encode($qrData, JSON_PRETTY_PRINT);

// Generate QR code
$qrCode = Builder::create()
->writer(new \Endroid\QrCode\Writer\SvgWriter())
    ->data($qrContent)
    ->encoding(new Encoding('UTF-8'))
  //  ->errorCorrectionLevel(ErrorCorrectionLevel::LOW)
    ->size(200)
    ->margin(10)
    //->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->build();

$qrCodeDataUri = $qrCode->getDataUri();
?>


<body id="page-top">
<div id="wrapper">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
         <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
        </nav>
        
        <div class="container-fluid">
        
            
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($invite['nom'] . ' ' . $invite['prenom']) ?></h6>
                            <div>
                                <a href="editinvite.php?id=<?= $invite['id_invite'] ?>" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nom:</strong> <?= htmlspecialchars($invite['nom']) ?></p>
                                    <p><strong>Prénom:</strong> <?= htmlspecialchars($invite['prenom']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($invite['mail']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Téléphone:</strong> <?= htmlspecialchars($invite['num_tele']) ?></p>
                                    <p><strong>Inscrit le:</strong> <?= date('d-m-Y H:i', strtotime($invite['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <!-- QR Code Section -->
                            <div class="mt-4 border-top pt-3">
                                <h5 class="font-weight-bold text-gray-800 mb-3">QR Code d'accès</h5>
                                <div class="text-center">
                                    <img src="<?= $qrCodeDataUri ?>" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                                    <p class="text-muted">Ce QR code contient les informations de l'invité et peut être utilisé pour vérification.</p>
                                    <a href="<?= $qrCodeDataUri ?>" download="qrcode-invite-<?= $invite['id_invite'] ?>.png" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Télécharger le QR Code
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Event Details Section -->
                            <div class="mt-4 border-top pt-3">
                                <h5 class="font-weight-bold text-gray-800 mb-3">Détails de l'événement</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nom de l'événement:</strong> <?= htmlspecialchars($event['EventName']) ?></p>
                                        <p><strong>Date:</strong> <?= htmlspecialchars($event['Date']) ?></p>
                                        <p><strong>Heure:</strong> <?= htmlspecialchars($event['Time']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Adresse:</strong> <?= htmlspecialchars($event['Adresse']) ?></p>
                                        <p><strong>Limité à:</strong> <?= htmlspecialchars($event['AttendeLimit']) ?> participants</p>
                                        <p><strong>Organisé par:</strong> <?= htmlspecialchars($event['OrganisedBy']) ?></p>
                                    </div>
                                </div>
                                <?php if (!empty($event['SponsoredBy'])): ?>
                                    <p><strong>Sponsorisé par:</strong> <?= htmlspecialchars($event['SponsoredBy']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($event['Activities'])): ?>
                                    <hr>
                                    <h6 class="font-weight-bold">Activités:</h6>
                                    <p><?= nl2br(htmlspecialchars($event['Activities'])) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4">
                                <a href="indexinvite.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  
include __DIR__ . '/footerT.php'; ?>