<?php
require_once __DIR__ . '/../Model/InviteModel.php';
require_once __DIR__ . '/../config.php';

class InviteController {
    private $db;
    private $model;
    public function __construct() {
        $database = new InviteDatabase  ();
        $this->db = $database->getConnection();
    }
// Inside InviteController.php
 
    // Get invites by event ID
    public function getInvitesByEventId($eventId) {
        $sql = "SELECT * FROM `invites` WHERE id_event = :id_event";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_event', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getInviteById($id) {
        try {
            $sql = "SELECT * FROM `invites` WHERE id_invite = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting invite by ID: " . $e->getMessage());
            return false;
        }
    }

    // Get
    // Add a new invite
    public function addInvite($inviteData) {
        $query = "INSERT INTO invites (id_event, nom, prenom, mail, num_tele)
                  VALUES (:id_event, :nom, :prenom, :mail, :num_tele)";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id_event', $inviteData['id_event'], PDO::PARAM_INT);
        $stmt->bindParam(':nom', $inviteData['nom']);
        $stmt->bindParam(':prenom', $inviteData['prenom']);
        $stmt->bindParam(':mail', $inviteData['mail']);
        $stmt->bindParam(':num_tele', $inviteData['num_tele']);

        return $stmt->execute();
    }

    // Update an invite
    public function updateInvite($id_invite, $inviteData) {
        $query = "UPDATE invites SET 
                    id_event = :id_event,
                    nom = :nom,
                    prenom = :prenom,
                    mail = :mail,
                    num_tele = :num_tele
                  WHERE id_invite = :id_invite";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id_event', $inviteData['id_event'], PDO::PARAM_INT);
        $stmt->bindParam(':nom', $inviteData['nom']);
        $stmt->bindParam(':prenom', $inviteData['prenom']);
        $stmt->bindParam(':mail', $inviteData['mail']);
        $stmt->bindParam(':num_tele', $inviteData['num_tele']);
        $stmt->bindParam(':id_invite', $id_invite, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Delete an invite
    public function deleteInvite($id_invite) {
        $query = "DELETE FROM invites WHERE id_invite = :id_invite";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_invite', $id_invite, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get all invites
    public function getAllInvites() {
        return $this->model->getAllInvites();
    }
}
?>