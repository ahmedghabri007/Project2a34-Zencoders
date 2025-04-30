<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $documentType = $_POST['document_type'] ?? '';
    $documentNumber = strtoupper(trim($_POST['document_number'] ?? ''));

    // ✅ Only validate if the user filled something
    if ($documentType === '' && $documentNumber === '') {
        // Skip processing if both are empty (assume page is just loading)
    } else {
        if (empty($documentType) || empty($documentNumber)) {
            $error = "All fields are required.";
        } elseif (!preg_match('/^[A-Z0-9]{8,12}$/', $documentNumber)) {
            $error = "Invalid document number format.";
        }

        if (empty($error)) {
            try {
                $pdo = Config::getConnexion();

                $stmt = $pdo->prepare("INSERT INTO badge_upgrade (new_document_type, new_document_number, request_status) VALUES (:type, :number, 'PENDING')");
                $stmt->execute([
                    ':type' => $documentType,
                    ':number' => $documentNumber
                ]);

                if ($stmt->rowCount() > 0) {
                    $id = $pdo->lastInsertId(); 
                   header("Location: ../Front_Office/formver.html?success=1&id=$id");
                    exit();
                } else {
                    $error = "Failed to insert data.";
                }

            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

