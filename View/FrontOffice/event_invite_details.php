<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/EventModel.php';
require_once __DIR__ . '/../../Model/InviteModel.php';
require_once __DIR__ . '/../../Controller/EventControler.php';
require_once __DIR__ . '/../../Controller/InviteController.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// SMTP Configuration
define('SMTP_HOST', 'longevityplus.store');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'siwar@longevityplus.store');
define('SMTP_PASSWORD', 'Siwarsiwar1');
define('SENDER_EMAIL', 'siwar@longevityplus.store');
define('SENDER_NAME', 'Longevity Plus Events');

// Initialize controllers
$eventController = new EventController();
$inviteController = new InviteController();

// Check if we're viewing event details or adding an invite
if (isset($_GET['id_event']) && is_numeric($_GET['id_event'])) {
    // Event Details Page
    $eventId = (int)$_GET['id_event'];
    $event = $eventController->getEventById($eventId);
    $invitations = $inviteController->getInvitesByEventId($eventId);

    if (!$event) {
        die("<div class='alert alert-danger'>Event not found.</div>");
    }

    include __DIR__ . '/header.php';
    include __DIR__ . '/headert.php';
    include __DIR__ . '/navbar.php';
    ?>
    
    <div class="container-xxl py-5 mt-5">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="events.php">Events</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($event['EventName']) ?></li>
                </ol>
            </nav>

            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="border-bottom pb-3 mb-4">
                        <h1 class="display-5 text-primary"><?= htmlspecialchars($event['EventName']) ?></h1>
                        <div class="d-flex flex-wrap gap-3 mt-3">
                            <span class="badge bg-primary">
                                <i class="fa fa-calendar-alt"></i> <?= date('d F Y', strtotime($event['Date'])) ?>
                            </span>
                            <span class="badge bg-secondary">
                                <i class="fa fa-clock"></i> <?= htmlspecialchars($event['Time']) ?>
                            </span>
                            <span class="badge bg-success">
                                <i class="fa fa-users"></i> <?= htmlspecialchars($event['AttendeLimit']) ?> seats
                            </span>
                        </div>
                        <p class="mt-3">
                            <i class="fa fa-map-marker-alt text-primary"></i>
                            <?= htmlspecialchars($event['Adresse']) ?>
                        </p>
                    </div>

                    <h3>Description</h3>
                    <div class="bg-light p-3 rounded mb-4">
                        <?= nl2br(htmlspecialchars($event['Activities'])) ?>
                    </div>

                    <h3>Organization</h3>
                    <div class="row">
                        <div class="col-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body text-center">
                                    <i class="fa fa-star fa-2x text-primary mb-2"></i>
                                    <h6>Sponsored by</h6>
                                    <p><?= htmlspecialchars($event['SponsoredBy']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body text-center">
                                    <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                    <h6>Organized by</h6>
                                    <p><?= htmlspecialchars($event['OrganisedBy']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3>Event Location</h3>
                    <div class="mb-4" style="height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3190.626099952783!2d10.186792475307755!3d36.899292062108444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12e2cb7454c6ed51%3A0x683b3ab5565cd357!2sEsprit%20pr%C3%A9pa!5e0!3m2!1sfr!2sus!4v1746014722424!5m2!1sfr!2sus"
                                width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Add Invite Form -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h4 class="m-0 font-weight-bold text-primary">Invite Guest</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            $error = '';
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $inviteData = [
                                    'id_event' => $eventId,
                                    'nom'      => trim($_POST['nom'] ?? ''),
                                    'prenom'   => trim($_POST['prenom'] ?? ''),
                                    'mail'     => trim($_POST['mail'] ?? ''),
                                    'num_tele' => trim($_POST['num_tele'] ?? '')
                                ];

                                if ($inviteController->addInvite($inviteData)) {
                                    $pdfContent = generateEventPDF($event, $inviteData);
                                    
                                    if (sendEventEmail($inviteData['mail'], $event, $pdfContent, $inviteData)) {
                                        echo '<div class="alert alert-success">Guest added and invitation sent successfully!</div>';
                                    } else {
                                        echo '<div class="alert alert-warning">Guest added but email failed to send.</div>';
                                    }
                                } else {
                                    $error = "Failed to add guest.";
                                }
                            }
                            
                            if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <input type="hidden" name="id_event" value="<?= $eventId ?>">
                                
                                <div class="form-group mb-3">
                                    <label for="nom">Last Name</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="prenom">First Name</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="mail">Email</label>
                                    <input type="email" class="form-control" id="mail" name="mail" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="num_tele">Phone</label>
                                    <input type="tel" class="form-control" id="num_tele" name="num_tele" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Send Invitation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/footer.php';
    include __DIR__ . '/footert.php';
    
} else {
    // Add Invite Page (standalone)
    $allEvents = $eventController->getAllEvents();
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inviteData = [
            'id_event' => $_POST['id_event'] ?? null,
            'nom'      => trim($_POST['nom'] ?? ''),
            'prenom'   => trim($_POST['prenom'] ?? ''),
            'mail'     => trim($_POST['mail'] ?? ''),
            'num_tele' => trim($_POST['num_tele'] ?? '')
        ];

        if ($inviteController->addInvite($inviteData)) {
            $event = $eventController->getEventById($inviteData['id_event']);
            $pdfContent = generateEventPDF($event, $inviteData);
            
            if (sendEventEmail($inviteData['mail'], $event, $pdfContent, $inviteData)) {
                $_SESSION['success_message'] = "Guest added and invitation sent successfully!";
            } else {
                $_SESSION['warning_message'] = "Guest added but email failed to send.";
            }
            
            header("Location: invitelistback.php");
            exit();
        } else {
            $error = "❌ Failed to add guest.";
        }
    }

    include __DIR__ . '/headerT.php';
    ?>
    <body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">➕ Add New Guest</h1>
                        <a href="invitelistback.php" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
                        </a>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Guest Details</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-group">
                                            <label for="id_event">Select Event</label>
                                            <select name="id_event" class="form-control" required>
                                                <option value="">-- Select Event --</option>
                                                <?php foreach ($allEvents as $event): ?>
                                                    <option value="<?= htmlspecialchars($event['id_event']) ?>">
                                                        <?= htmlspecialchars($event['EventName']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="nom">Last Name</label>
                                            <input type="text" class="form-control" id="nom" name="nom" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="prenom">First Name</label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="mail">Email</label>
                                            <input type="email" class="form-control" id="mail" name="mail" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="num_tele">Phone</label>
                                            <input type="text" class="form-control" id="num_tele" name="num_tele" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add Guest
                                        </button>
                                        <a href="invitelistback.php" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/footer.php';
}
?>

<?php
/**
 * Generate PDF with event information
 */
function generateEventPDF($event, $inviteData) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Longevity Plus');
    $pdf->SetTitle('Event Invitation: ' . $event['EventName']);
    $pdf->SetSubject('Event Details');
    
    $pdf->AddPage();
    
    // Logo
    $logo = __DIR__ . '/../../assets/img/logo.png';
    if (file_exists($logo)) {
        $pdf->Image($logo, 15, 10, 30, 0, 'PNG');
    }
    
    $pdf->SetY(40);
    
    $html = '<h1 style="text-align:center;color:#2c3e50;">Event Invitation</h1>';
    $html .= '<h2 style="text-align:center;color:#3498db;">' . htmlspecialchars($event['EventName']) . '</h2>';
    $html .= '<table border="0" cellpadding="5">';
    $html .= '<tr><td width="30%"><strong>Date:</strong></td><td>' . htmlspecialchars($event['Date']) . '</td></tr>';
    $html .= '<tr><td><strong>Time:</strong></td><td>' . htmlspecialchars($event['Time']) . '</td></tr>';
    $html .= '<tr><td><strong>Location:</strong></td><td>' . htmlspecialchars($event['Adresse']) . '</td></tr>';
    $html .= '<tr><td><strong>Organized by:</strong></td><td>' . htmlspecialchars($event['OrganisedBy']) . '</td></tr>';
    $html .= '</table>';
    
    $html .= '<h3 style="color:#2c3e50;margin-top:20px;">Guest Information</h3>';
    $html .= '<table border="0" cellpadding="5">';
    $html .= '<tr><td width="30%"><strong>Name:</strong></td><td>' . htmlspecialchars($inviteData['prenom'] . ' ' . $inviteData['nom']) . '</td></tr>';
    $html .= '<tr><td><strong>Email:</strong></td><td>' . htmlspecialchars($inviteData['mail']) . '</td></tr>';
    $html .= '<tr><td><strong>Phone:</strong></td><td>' . htmlspecialchars($inviteData['num_tele']) . '</td></tr>';
    $html .= '</table>';
    
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode("EventID:{$event['id_event']},Guest:{$inviteData['mail']}");
    $html .= '<div style="text-align:center;margin-top:20px;"><img src="' . $qrCodeUrl . '" width="100" height="100" /></div>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    return $pdf->Output('', 'S');
}

/**
 * Send email with PDF attachment
 */
function sendEventEmail($toEmail, $event, $pdfContent, $inviteData) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = SMTP_PORT;
        $mail->Timeout    = 30;
        
        // Recipients
        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
        $mail->addAddress($toEmail, $inviteData['prenom'] . ' ' . $inviteData['nom']);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Invitation: ' . $event['EventName'] . ' - ' . $event['Date'];
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #3498db; padding: 20px; color: white; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>You\'re Invited!</h1>
                <h2>' . htmlspecialchars($event['EventName']) . '</h2>
            </div>
            <div class="content">
                <p>Dear ' . htmlspecialchars($inviteData['prenom']) . ',</p>
                <p>You are invited to attend <strong>' . htmlspecialchars($event['EventName']) . '</strong>.</p>
                <h3>Event Details:</h3>
                <ul>
                    <li><strong>Date:</strong> ' . htmlspecialchars($event['Date']) . '</li>
                    <li><strong>Time:</strong> ' . htmlspecialchars($event['Time']) . '</li>
                    <li><strong>Location:</strong> ' . htmlspecialchars($event['Adresse']) . '</li>
                </ul>
                <p>Please find attached your official invitation.</p>
            </div>
            <div class="footer">
                <p>Best regards,<br>' . htmlspecialchars($event['OrganisedBy']) . '</p>
            </div>
        </body>
        </html>';
        
        $mail->AltBody = "Dear {$inviteData['prenom']},\n\nYou are invited to:\n\nEvent: {$event['EventName']}\nDate: {$event['Date']}\nTime: {$event['Time']}\nLocation: {$event['Adresse']}\n\nPlease see attached invitation.\n\nBest regards,\n{$event['OrganisedBy']}";
        
        // Attach PDF
        $mail->addStringAttachment($pdfContent, 'Invitation_'.preg_replace('/[^a-z0-9]/i', '_', $event['EventName']).'.pdf');
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}
?>