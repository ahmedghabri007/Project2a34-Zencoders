<?php
require_once __DIR__ . '/../model/ReclamationModel.php';

class ReclamationController
{
    private $model;

    public function __construct()
    {
        $this->model = new ReclamationModel();
    }

    public function getReclamations()
    {
        return $this->model->getAllReclamations();
    }
    

    public function ajouter($email, $description, $type)
    {
        $this->model->ajouterReclamation($email, $description, $type);
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

 // Nouvelle méthode pour supprimer une réclamation
 public function supprimerReclamation($id_reclamation)
 {
     $this->model->supprimerReclamation($id_reclamation);
 }
 public function modifierReclamation($id_reclamation, $email, $description, $type_reclamation)
 {
     $result = $this->model->modifierReclamation($id_reclamation, $email, $description, $type_reclamation);
     return $result;  // Retourner true si la mise à jour a réussi, sinon false
 }
 
}
