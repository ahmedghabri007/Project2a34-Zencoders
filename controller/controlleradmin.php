<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/ModelClient.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($action == 'supprimer' && $id) {
    try {
        $user = ModelClient::getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        ModelClient::delete($id);
        header("Location: ../view/Backoffice/index.php");

        exit;
    } catch (Exception $e) {
        error_log("Delete Error: " . $e->getMessage());
        exit;
    }
}

if ($action == 'update' && $id) {
    try {
        $data = [
            'fullname' => $_POST['fullname'] ?? null,
            'email' => $_POST['email'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'age' => $_POST['age'] ?? null,
            'gender' => $_POST['gender'] ?? null,
            'life_status' => $_POST['life_status'] ?? null,
            'role' => $_POST['role'] ?? null,
            'admin' => $_POST['admin'] ?? null,

        ];

        if (updateUser($id, $data)) {
            header("Location: ../view/Backoffice/index.php");
        } else {
            throw new Exception("Update failed.");
        }
        exit;
    } catch (Exception $e) {
        error_log("Update Error: " . $e->getMessage());
        header("Location: ../view/Backoffice/index.php");
        exit;
    }
}

function showUsers()
{
    try {
        $pdo = Config::getConnexion();
        $query = "SELECT * FROM accounts";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users === false) {
            throw new Exception('No users found or an error occurred during fetching.');
        }

        return $users;
    } catch (PDOException $e) {
        error_log("PDO Error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        return [];
    }
}

function updateUser($id, $data)
{
    try {
        $user = ModelClient::getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        if (!isset($data['fullname'], $data['email'], $data['phone'], $data['age'], $data['gender'], $data['life_status'], $data['role'])) {
            throw new Exception("Incomplete data provided for update.");
        }

        $userToUpdate = new ModelClient(
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['age'],
            $data['gender'],
            $data['life_status'],
            $data['role']
        );
        $userToUpdate->setId($id);
        $userToUpdate->update();

        return true;
    } catch (Exception $e) {
        error_log("Update Error: " . $e->getMessage());
        return false;
    }
}
