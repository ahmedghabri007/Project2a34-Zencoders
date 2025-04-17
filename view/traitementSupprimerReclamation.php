<?php
require_once __DIR__ . '/../controller/ReclamationController.php';

if (isset($_POST['id_reclamation'])) {
    $id_reclamation = $_POST['id_reclamation'];

    $controller = new ReclamationController();
    $controller->supprimerReclamation($id_reclamation);

    
} else {
    echo "❌ ID de réclamation invalide.";
}
