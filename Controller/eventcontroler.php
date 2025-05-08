<?php 
require_once __DIR__ . '/../Model/eventmodel.php';

class eventcontroller {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Add event
    public function addEvent($event) {
        $query = "INSERT INTO event (EventName, Date, Time, Adresse, AttendeLimit, SponsoredBy, OrganisedBy, Activities)
                  VALUES (:EventName, :Date, :Time, :Adresse, :AttendeLimit, :SponsoredBy, :OrganisedBy, :Activities)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':EventName', $event['EventName']);
        $stmt->bindParam(':Date', $event['Date']);
        $stmt->bindParam(':Time', $event['Time']);
        $stmt->bindParam(':Adresse', $event['Adresse']);
        $stmt->bindParam(':AttendeLimit', $event['AttendeLimit']);
        $stmt->bindParam(':SponsoredBy', $event['SponsoredBy']);
        $stmt->bindParam(':OrganisedBy', $event['OrganisedBy']);
        $stmt->bindParam(':Activities', $event['Activities']);
        return $stmt->execute();
    }

    public function updateEvent($id_event, $event) {
        $query = "UPDATE event SET 
                    EventName = :EventName,
                    Date = :Date,
                    Time = :Time,
                    Adresse = :Adresse,
                    AttendeLimit = :AttendeLimit,
                    SponsoredBy = :SponsoredBy,
                    OrganisedBy = :OrganisedBy,
                    Activities = :Activities
                  WHERE `id_event` = :id_event";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':EventName', $event['EventName']);
        $stmt->bindParam(':Date', $event['Date']);
        $stmt->bindParam(':Time', $event['Time']);
        $stmt->bindParam(':Adresse', $event['Adresse']);
        $stmt->bindParam(':AttendeLimit', $event['AttendeLimit']);
        $stmt->bindParam(':SponsoredBy', $event['SponsoredBy']);
        $stmt->bindParam(':OrganisedBy', $event['OrganisedBy']);
        $stmt->bindParam(':Activities', $event['Activities']);
        $stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM event WHERE id_event = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    

    public function getAllEvents() {
        $query = "SELECT * FROM event";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEvent($id_event) {
        $query = "DELETE FROM event WHERE `id_event` = :id_event";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function showEvents() {
        $events = $this->getAllEvents();
        require_once __DIR__ . '/../View/BackOffice/index.php';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $errors = [];

    $EventName = trim($_POST['EventName']);
    $Date = $_POST['Date'];
    $Time = $_POST['Time'];
    $Adresse = trim($_POST['Adresse']);
    $AttendeLimit = intval($_POST['AttendeLimit']);
    $SponsoredBy = trim($_POST['SponsoredBy']);
    $OrganisedBy = trim($_POST['OrganisedBy']);
    $Activities = trim($_POST['Activities']);

    if (empty($EventName)) $errors[] = "Event Name is required.";
    if (empty($Date) || strtotime($Date) < strtotime(date("Y-m-d"))) $errors[] = "Valid future date is required.";
    if (empty($Time)) $errors[] = "Time is required.";
    if (empty($Adresse)) $errors[] = "Address is required.";
    if ($AttendeLimit <= 0) $errors[] = "Attendee limit must be greater than zero.";
    if (empty($SponsoredBy)) $errors[] = "Sponsor name is required.";
    if (empty($OrganisedBy)) $errors[] = "Organizer name is required.";
    if (empty($Activities)) $errors[] = "Activities description is required.";

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo "<a href='../View/BackOffice/addeventform.php'>Go Back</a>";
        exit();
    }

    $eventData = [
        'EventName' => $EventName,
        'Date' => $Date,
        'Time' => $Time,
        'Adresse' => $Adresse,
        'AttendeLimit' => $AttendeLimit,
        'SponsoredBy' => $SponsoredBy,
        'OrganisedBy' => $OrganisedBy,
        'Activities' => $Activities
    ];

    $controller = new eventcontroller();
    $controller->addEvent($eventData);

    header("Location: ../View/BackOffice/showevent.php");
    exit();
}
?>
