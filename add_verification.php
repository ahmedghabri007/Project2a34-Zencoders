<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include '../../config/config.php';

$response = ['success' => false, 'message' => '', 'verification_id' => null];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $documentType = $_POST['document_type'] ?? '';
    $documentNumber = strtoupper(trim($_POST['document_number'] ?? ''));

    if (empty($documentType) || empty($documentNumber)) {
        $response['message'] = "Tous les champs sont requis.";
    } elseif (!preg_match('/^[A-Z0-9]{8,12}$/', $documentNumber)) {
        $response['message'] = "Format du numéro invalide (8-12 caractères alphanumériques).";
    } else {
        try {
            $pdo = Config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO verification (document_type, document_number, status) VALUES (?, ?, 'PENDING')");
            $stmt->execute([$documentType, $documentNumber]);

            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = "Vérification réussie.";
                $response['verification_id'] = $pdo->lastInsertId();
            } else {
                $response['message'] = "Échec de l'enregistrement.";
            }
        } catch (PDOException $e) {
            $response['message'] = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Vérification réussie.',
    'verification_id' => $pdo->lastInsertId()
  ]);
  
exit;
?>
