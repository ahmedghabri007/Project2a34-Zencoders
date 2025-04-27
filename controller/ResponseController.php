<?php
require_once __DIR__ . '/../model/ResponseModel.php';

class ResponseController {
    private $responseModel;

    public function __construct() {
        $this->responseModel = new ResponseModel();
    }

    // Create a new response
    public function createResponse($id_reclamation, $email_admin, $contenu_reponse) {
        return $this->responseModel->createResponse($id_reclamation, $email_admin, $contenu_reponse);
    }

    // Get all responses for a reclamation
    public function getResponsesByReclamation($id_reclamation) {
        return $this->responseModel->getResponsesByReclamation($id_reclamation);
    }

    // Get a single response by ID
    public function getResponseById($id_reponse) {
        return $this->responseModel->getResponseById($id_reponse);
    }

    // Update a response
    public function updateResponse($id_reponse, $contenu_reponse) {
        return $this->responseModel->updateResponse($id_reponse, $contenu_reponse);
    }

    // Delete a response
    public function deleteResponse($id_reponse) {
        return $this->responseModel->deleteResponse($id_reponse);
    }

    // Get all responses (for history page)
    public function getAllResponses() {
        return $this->responseModel->getAllResponses();
    }
} 