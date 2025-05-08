<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../Controller/eventcontroler.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$eventController = new eventcontroller();
$eventController->deleteEvent($_GET['id']);

header("Location: index.php");
exit();
?>