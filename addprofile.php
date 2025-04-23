<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $location = trim($_POST['location']);
    $profession = trim($_POST['profession']);
    $interests = trim($_POST['interests']);
    $biography = trim($_POST['biography']);
    $phone = trim($_POST['phone']); // Get the phone number

    if (empty($fullname) || empty($age) || empty($gender) || empty($location) || empty($profession) || empty($interests) || empty($biography) || empty($phone)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($age < 18) {
        $error = "L'âge doit être au moins 18 ans.";
    } elseif (strlen($biography) < 20) {
        $error = "La biographie doit contenir au moins 20 caractères.";
    } elseif (!preg_match('/^\d{8}$/', $phone)) { // Validate phone number for 8 digits
        $error = "Le numéro de téléphone doit être valide (8 chiffres).";
    } else {
        try {
            $pdo = Config::getConnexion();

            // Add phone column to the INSERT query
            $stmt = $pdo->prepare("INSERT INTO profile (fullname, age, gender, location, profession, interests, biography, phone)
                                   VALUES (:fullname, :age, :gender, :location, :profession, :interests, :biography, :phone)");

            $stmt->execute([
                ':fullname' => $fullname,
                ':age' => $age,
                ':gender' => $gender,
                ':location' => $location,
                ':profession' => $profession,
                ':interests' => $interests,
                ':biography' => $biography,
                ':phone' => $phone  // Include phone in the insert statement
            ]);

            if ($stmt->rowCount()) {
                header("Location: ../Front_Office/formulaire.html?success=1");
                exit();
            } else {
                $error = "Une erreur s'est produite lors de l'ajout du profil.";
            }

        } catch (PDOException $e) {
            $error = "Erreur base de données : " . $e->getMessage();
        }
    }
}

if (!empty($error)) {
    echo "<div style='color: red; text-align: center;'>❌ $error</div>";
}
?>
