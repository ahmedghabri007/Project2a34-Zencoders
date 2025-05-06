<?php
include '../config.php';
include '../model/ModelClient.php';

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'inscription') {
    inscription($accounts);
}

function inscription($accounts)
{
    // Collect and sanitize input
    $fullname = $_POST['signupName'] ?? '';
    $email = $_POST['signupEmail'] ?? '';
    $phone = $_POST['signupPhone'] ?? '';
    $age = $_POST['signupAge'] ??'' ;
    $gender = $_POST['signupGender'] ?? '';
    $life_status = $_POST['signupLifeStatus'] ?? '';
    $role = $_POST['signupRole'] ?? '';
    $password = $_POST['signupPassword'] ?? '';
    $confirm_password = $_POST['signupConfirmPassword'] ?? '';

    // Password match check
    if ($password !== $confirm_password) {
        header("Location: ../view/frontoffice/inscription.php?message=password_mismatch");
        exit;
    }

    try {
        $pdo = config::getConnexion();

        // Email existence check
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            header("Location: ../view/frontoffice/inscription.php?message=mail_exists");
            exit;
        }

        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO accounts (fullname, email, phone, age, gender, life_status, role, password, created_at)
            VALUES (:fullname, :email, :phone, :age, :gender, :life_status, :role, :password, NOW())
        ");

        $stmt->execute([
            ':fullname' => $fullname,
            ':email' => $email,
            ':phone' => $phone,
            ':age' => $age,
            ':gender' => $gender,
            ':life_status' => $life_status,
            ':role' => $role,
            ':password' => $hashed_password
        ]);

        header("Location: ../view/frontoffice/connexion.php?message=inscription_reussie");
        exit;

    } catch (Exception $e) {

        header("Location: ../view/frontoffice/index.php?message=error");
        exit;
    }
}
?>
