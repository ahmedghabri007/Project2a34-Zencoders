<?php
require_once __DIR__ . '/../controller/ReclamationController.php';
require_once __DIR__ . '/../controller/ResponseController.php';

header('Content-Type: application/json');

try {
    $reclamationController = new ReclamationController();
    $responseController = new ResponseController();

    // Récupérer toutes les réclamations
    $reclamations = $reclamationController->getReclamations();
    
    // Filtrer les réclamations non répondues
    $unansweredReclamations = [];
    foreach ($reclamations as $reclamation) {
        $responses = $responseController->getResponsesByReclamation($reclamation['id_reclamation']);
        if (empty($responses)) {
            $unansweredReclamations[] = [
                'id_reclamation' => $reclamation['id_reclamation'],
                'type_reclamation' => $reclamation['type_reclamation'],
                'description' => $reclamation['Description'],
                'date_reclamation' => $reclamation['date_reclamation'],
                'email' => $reclamation['email']
            ];
        }
    }

    // Préparer la réponse
    $response = [
        'success' => true,
        'count' => count($unansweredReclamations),
        'reclamations' => $unansweredReclamations
    ];

    echo json_encode($response);
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 