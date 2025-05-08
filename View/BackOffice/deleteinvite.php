<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/invitemodel.php';
require_once __DIR__ . '/../../Controller/invitecontroller.php';

// Check if 'id' is passed
if (!isset($_GET['id'])) {
    header("Location: invitelistback.php"); // Redirect to the guest list page
    exit();
}

// Instantiate the controller and delete the guest
$inviteController = new InviteController();
$inviteController->deleteInvite($_GET['id']);

header("Location: invitelistback.php");
exit();
?>
