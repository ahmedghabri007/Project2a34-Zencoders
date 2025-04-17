<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/ModelClient.php';

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'inscription') {
    inscription();
}




function inscription()
{
    $fullname = $_POST['signupName'] ?? '';
    $email = $_POST['signupEmail'] ?? '';
    $phone = $_POST['signupPhone'] ?? '';
    $age = $_POST['signupAge'] ?? '';
    $gender = $_POST['signupGender'] ?? '';
    $life_status = $_POST['signupLifeStatus'] ?? '';
    $role = $_POST['signupRole'] ?? '';
    $password = $_POST['signupPassword'] ?? '';
    $confirm_password = $_POST['signupConfirmPassword'] ?? '';

    // Vérification : les deux mots de passe doivent être identiques
    if ($password !== $confirm_password) {
        header("Location: ../view/frontoffice/inscription.php");
        exit;
    }

    try {
        $pdo = Config::getConnexion();

        // Vérifier si l'email existe déjà (dans la bonne table : accounts)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            header("Location: ../view/frontoffice/inscription.php");
            exit;
        }

        // Créer et enregistrer le client
        $client = new ModelClient($fullname, $email, $phone, $age, $gender, $life_status, $role, $password);
        $id = $client->save();

        if ($id) {
            header("Location: ../view/frontoffice/inscription.php");
        } else {
            header("Location: ../view/frontoffice/inscription.php?message=save_failed");
        }
        exit;
    } catch (Exception $e) {
        error_log("Inscription error: " . $e->getMessage());
        header("Location: ../view/frontoffice/index.php?message=error");
        exit;
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    login();
}

if ( $action === 'logout') {
    logout();
}


function login(): void
{
    session_start(); // Démarrage de session

    $email = $_POST["loginEmail"] ?? '';
    $password = $_POST["loginPassword"] ?? '';

    // Log pour debug
    error_log("Tentative de login avec l'email: $email");

    if (empty($email) || empty($password)) {
        error_log("Email ou mot de passe manquant.");
        header('Location: ../view/frontoffice/index.php?error=missing_fields');
        exit();
    }

    try {
        $db = Config::getConnexion();

        $sql = "SELECT * FROM accounts WHERE email = :email";
        $req = $db->prepare($sql);
        $req->bindParam(':email', $email);
        $req->execute();
        $result = $req->fetch();

        var_dump($result);
        var_dump($result['password']);
        var_dump($password);

        if ($result) {
            $storedHash = $result['password'];

            if (password_verify($password, $storedHash)) {
                // Authentification réussie
                error_log("Mot de passe vérifié avec succès.");

                $_SESSION['id'] = $result['id'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['fullname'] = $result['fullname'] ?? '';

                header('Location: ../view/frontoffice/inscription.php?error=gregregregregrgergr');
                exit();
            } else {
                error_log("Échec vérification mot de passe.");
                header('Location: ../view/frontoffice/index.php?error=invalid_credentials');
                exit();
            }
        } else {
            error_log("Aucun utilisateur trouvé avec l'email: $email");
            header('Location: ../view/frontoffice/index.php?error=no_user_found');
            exit();
        }
    } catch (Exception $e) {
        error_log("Erreur lors du login : " . $e->getMessage());
        header('Location: ../view/frontoffice/index.php?error=server_error');
        exit();
    }
}


 function logout()
    {

         session_start();
        
         // Unset all session variables
         session_unset();
         
         // Destroy the session completely
         session_destroy();

        // Rediriger vers la page d'accueil après la déconnexion
        header("Location: ../view/frontoffice/inscription.php");
        exit();
    }