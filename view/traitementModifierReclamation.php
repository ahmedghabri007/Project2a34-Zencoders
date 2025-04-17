<?php
require_once __DIR__ . '/../controller/ReclamationController.php';

if (isset($_POST['id_reclamation'], $_POST['email'], $_POST['description'], $_POST['type_reclamation'])) {
    $id_reclamation = $_POST['id_reclamation'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $type_reclamation = $_POST['type_reclamation'];
    
    $controller = new ReclamationController();
    
    // Mettre à jour la réclamation dans la base de données
    $result = $controller->modifierReclamation($id_reclamation, $email, $description, $type_reclamation);
    
    if ($result) {
        echo 'Réclamation mise à jour avec succès.';
        echo '<br><a href="afficherReclamation.php">Retour à la liste des réclamations</a>';
    } else {
        echo 'Erreur lors de la mise à jour de la réclamation.';
        echo '<br><a href="afficherReclamation.php">Retour à la liste des réclamations</a>';
    }
} else {
    echo 'Tous les champs doivent être remplis.';
    echo '<br><a href="afficherReclamation.php">Retour à la liste des réclamations</a>';
}
