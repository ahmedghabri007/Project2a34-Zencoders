<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documentType = $_POST['document_type'];
    $documentNumber = strtoupper(trim($_POST['document_number']));

    if (empty($documentType) || empty($documentNumber)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[A-Z0-9]{8,12}$/', $documentNumber)) {
        $error = "Invalid document number format.";
    }

    if (empty($error)) {
        try {
            $pdo = Config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO verification (document_type, document_number, status) VALUES (:type, :number, 'PENDING')");
            $stmt->execute([
                ':type' => $documentType,
                ':number' => $documentNumber
            ]);

            if ($stmt->rowCount() > 0) {
                header("Location: ../Front_Office/form.html?success=1"); // ✅ fixed redirect
                exit();
            } else {
                $error = "Failed to insert data.";
            }

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }

    if (!empty($error)) {
        echo "<div style='color:red;'>$error</div>";
    }
}
?>
