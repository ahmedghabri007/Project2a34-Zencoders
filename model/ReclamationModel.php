<?php
require_once __DIR__ . '/../cnx.php';

class ReclamationModel
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion(); // ✅ Connexion via la classe config
    }

    public function getAllReclamations()
    {
        $sql = "SELECT id_reclamation, email, Description, type_reclamation, date_reclamation FROM reclamation ORDER BY date_reclamation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getReclamationsByEmail($email)
    {
        $sql = "SELECT id_reclamation, email, Description, type_reclamation, date_reclamation FROM reclamation WHERE email LIKE :email ORDER BY date_reclamation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', '%' . $email . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllEmails()
    {
        $stmt = $this->db->query("SELECT email FROM accounts");
        return $stmt->fetchAll();
    }

    public function getReclamationById($id_reclamation)
    {
        $sql = "SELECT id_reclamation, email, Description, type_reclamation, date_reclamation FROM reclamation WHERE id_reclamation = :id_reclamation";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_reclamation', $id_reclamation, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function ajouterReclamation($email, $description, $type)
    {
        $stmt = $this->db->prepare("INSERT INTO reclamation (email, Description, type_reclamation, date_reclamation) VALUES (:email, :description, :type, NOW())");
        $stmt->execute([
            ':email' => $email,
            ':description' => $description,
            ':type' => $type
        ]);
    }

    public function supprimerReclamation($id)
    {
        $stmt = $this->db->prepare("DELETE FROM reclamation WHERE id_reclamation = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function modifierReclamation($id, $email, $description, $type)
    {
        $stmt = $this->db->prepare("UPDATE reclamation SET email = :email, Description = :description, type_reclamation = :type WHERE id_reclamation = :id");
        return $stmt->execute([
            ':email' => $email,
            ':description' => $description,
            ':type' => $type,
            ':id' => $id
        ]);
    }
    
    public function repondreReclamation($id_reclamation, $reponse)
    {
        $stmt = $this->db->prepare("UPDATE reclamation SET reponse = :reponse, date_reponse = NOW() WHERE id_reclamation = :id_reclamation");
        return $stmt->execute([
            ':reponse' => $reponse,
            ':id_reclamation' => $id_reclamation
        ]);
    }
}
