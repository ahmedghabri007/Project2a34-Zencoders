<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'event';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=localhost;dbname=event",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}

class Event {
    public $id_event;  // Corrected (variable names can't contain hyphens)
    public $EventName;
    public $Date;
    public $Time;
    public $Adresse;
    public $AttendeLimit;
    public $SponsoredBy;
    public $OrganisedBy;
    public $Activities;

    private $db;

    public function __construct($EventName = null, $Date = null, $Time = null, $Adresse = null, $AttendeLimit = null, $SponsoredBy = null, $OrganisedBy = null, $Activities = null) {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->EventName = $EventName;
        $this->Date = $Date;
        $this->Time = $Time;
        $this->Adresse = $Adresse;
        $this->AttendeLimit = $AttendeLimit;
        $this->SponsoredBy = $SponsoredBy;
        $this->OrganisedBy = $OrganisedBy;
        $this->Activities = $Activities;
    }




    public function getAllEvents() {
        $stmt = $this->db->prepare("SELECT * FROM event ORDER BY EventName ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEvent($id_event) {
        $stmt = $this->db->prepare("DELETE FROM event WHERE id_event = :id_event");
        $stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventById($id) {
        $sql = "SELECT * FROM event WHERE id_event = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
