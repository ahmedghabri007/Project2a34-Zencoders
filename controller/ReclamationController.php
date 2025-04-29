<?php
require_once __DIR__ . '/../model/ReclamationModel.php';
require_once __DIR__ . '/../model/WhatsAppNotification.php';

class ReclamationController
{
    private $model;
    private $whatsapp;

    public function __construct()
    {
        $this->model = new ReclamationModel();
        $this->whatsapp = new WhatsAppNotification();
    }

    public function getReclamations()
    {
        return $this->model->getAllReclamations();
    }
    
    public function getReclamationsByEmail($email)
    {
        return $this->model->getReclamationsByEmail($email);
    }

    public function ajouter($email, $description, $type)
    {
        $this->model->ajouterReclamation($email, $description, $type);
        
        $this->whatsapp->sendReclamationNotification($email, $description, $type);
    }

    public function getEmails()
    {
        return $this->model->getAllEmails();
    }

    public function getReclamationById($id_reclamation)
    {
        $model = new ReclamationModel();
        return $model->getReclamationById($id_reclamation);
    }

    public function supprimerReclamation($id_reclamation)
    {
        $this->model->supprimerReclamation($id_reclamation);
    }

    public function modifierReclamation($id_reclamation, $email, $description, $type_reclamation)
    {
        $result = $this->model->modifierReclamation($id_reclamation, $email, $description, $type_reclamation);
        return $result;
    }
 
    public function repondreReclamation($id_reclamation, $reponse)
    {
        return $this->model->repondreReclamation($id_reclamation, $reponse);
    }
}
