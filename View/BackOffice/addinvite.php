    <?php
    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../../Model/InviteModel.php';
    require_once __DIR__ . '/../../Controller/InviteController.php';
    require_once __DIR__ . '/../../Controller/EventControler.php';
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

    // Fetch all events for dropdown
    $allEvents = $eventController->getAllEvents();
    $error = '';

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

    // Handle form submission
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

    // Include header
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
                                            <label for="nom">Name</label>
                                            <input type="text" class="form-control" id="nom" name="nom" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="prenom">Prename</label>
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

    <?php
    include __DIR__ . '/footer.php';
    ?>