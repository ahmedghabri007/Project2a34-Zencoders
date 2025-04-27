<?php
require_once __DIR__ . '/../cnx.php';

class ResponseModel {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function createResponse($id_reclamation, $email_admin, $contenu_reponse) {
        $sql = "INSERT INTO reponse (id_reclamation, email_admin, contenu_reponse) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_reclamation, $email_admin, $contenu_reponse]);
    }

    public function getResponsesByReclamation($id_reclamation) {
        $sql = "SELECT * FROM reponse WHERE id_reclamation = ? ORDER BY date_reponse DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_reclamation]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResponseById($id_reponse) {
        $sql = "SELECT * FROM reponse WHERE id_reponse = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_reponse]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateResponse($id_reponse, $contenu_reponse) {
        $sql = "UPDATE reponse SET contenu_reponse = ? WHERE id_reponse = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$contenu_reponse, $id_reponse]);
    }

    public function deleteResponse($id_reponse) {
        $sql = "DELETE FROM reponse WHERE id_reponse = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_reponse]);
    }

    public function getAllResponses() {
        $sql = "SELECT r.*, rec.Description as reclamation_description 
                FROM reponse r 
                JOIN reclamation rec ON r.id_reclamation = rec.id_reclamation 
                ORDER BY r.date_reponse DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 