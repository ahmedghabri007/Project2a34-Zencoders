<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);
    $location = trim($_POST['location']);
    $profession = trim($_POST['profession']);
    $interests = trim($_POST['interests']);
    $biography = trim($_POST['biography']);
    $phone = trim($_POST['phone']);

    // Gérer le fichier image
    $profile_picture_path = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB max size for the image
        
        // Check file size
        if ($_FILES['profile_picture']['size'] > $maxFileSize) {
            $error = "La taille du fichier dépasse la limite autorisée (5 Mo).";
        } elseif (!in_array($fileExtension, $allowedExtensions)) {
            $error = "Format de photo non supporté. (jpg, png, gif)";
        } else {
            // Generate a unique file name using uniqid
            $newFileName = 'profile_' . uniqid() . '.' . $fileExtension;
            $uploadDir = '../../uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            // Attempt to move the uploaded file
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_picture_path = 'uploads/' . $newFileName;
            } else {
                $error = "Erreur lors du téléchargement de la photo.";
            }
        }
    }

    // Form validation
    if (empty($fullname) || empty($age) || empty($gender) || empty($location) || empty($profession) || empty($interests) || empty($biography) || empty($phone)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($age < 18) {
        $error = "L'âge doit être au moins 18 ans.";
    } elseif (strlen($biography) < 20) {
        $error = "La biographie doit contenir au moins 20 caractères.";
    } elseif (!preg_match('/^\d{8}$/', $phone)) {
        $error = "Le numéro de téléphone doit être valide (8 chiffres).";
    } elseif (empty($profile_picture_path)) {
        $error = "Veuillez télécharger une photo de profil valide.";
    } else {
        try {
            $pdo = Config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO profile (fullname, age, gender, location, profession, interests, biography, phone, profile_picture)
                                   VALUES (:fullname, :age, :gender, :location, :profession, :interests, :biography, :phone, :profile_picture)");

            $stmt->execute([
                ':fullname' => $fullname,
                ':age' => $age,
                ':gender' => $gender,
                ':location' => $location,
                ':profession' => $profession,
                ':interests' => $interests,
                ':biography' => $biography,
                ':phone' => $phone,
                ':profile_picture' => $profile_picture_path
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
