<?php
class InviteDatabase {
    private $host     = 'localhost';
    private $db_name  = 'event';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}

class Invite {
    public $id_invite;
    public $id_event;
    public $nom;
    public $prenom;
    public $mail;
    public $num_tele;

    private $db;

    public function __construct($id_event = null, $nom = null, $prenom = null, $mail = null, $num_tele = null) {
        $database = new InviteDatabase();
        $this->db = $database->getConnection();
        $this->id_event = $id_event;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mail = $mail;
        $this->num_tele = $num_tele;
    }

    // Get all invites
    public function getAllInvites() {
        $stmt = $this->db->prepare('SELECT * FROM `invites` ORDER BY id_invite DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single invite by ID
    public function getInviteById($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM `invites` WHERE id_invite = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    // Get invites by event ID
    public function getInvitesByEventId($eventId) {
        $stmt = $this->db->prepare('SELECT * FROM `invites` WHERE id_event = :id_event');
        $stmt->bindParam(':id_event', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete invite by ID
    public function deleteInvite($id_invite) {
        $stmt = $this->db->prepare('DELETE FROM `invites` WHERE id_invite = :id_invite');
        $stmt->bindParam(':id_invite', $id_invite, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
