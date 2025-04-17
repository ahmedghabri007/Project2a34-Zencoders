<?php
error_reporting(E_ALL);  // Enable error reporting for debugging
ini_set('display_errors', 1);  // Show errors in the browser

include '../../config/config.php';  // Include the database configuration

$error = '';  // Variable to hold any error message
$success = '';  // Variable to hold success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $documentType = $_POST['document_type'];
    $documentNumber = strtoupper(trim($_POST['document_number']));  // Convert to uppercase and trim spaces

    // Simple validation
    if (empty($documentType) || empty($documentNumber)) {
        $error = "All fields are required.";
    }

    // Validate document number (8-12 alphanumeric characters)
    if (!preg_match('/^[A-Z0-9]{8,12}$/i', $documentNumber)) {
        $error = "Invalid document number format. It must be between 8 and 12 alphanumeric characters.";
    }

    // If no errors, insert data into the database
    if (empty($error)) {
        try {
            $pdo = Config::getConnexion();

            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO verification (document_type, document_number, status) VALUES (:type, :number, 'PENDING')");

            // Bind parameters and execute the query
            $stmt->execute([
                ':type' => $documentType,
                ':number' => $documentNumber
            ]);

            // Redirect or display success message
            if ($stmt->rowCount() > 0) {
                header("Location: front-office.html?success=1");  // Redirect to front office with success message
                exit();
            } else {
                $error = "Failed to insert data. Please try again.";
            }

        } catch (PDOException $e) {
            // Handle any errors
            $error = "Database error: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification</title>
</head>
<body>
    <div>
        <?php if (!empty($error)): ?>
            <div style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="add_verification.php">
            <label for="document_type">Document Type:</label>
            <select name="document_type" id="document_type" required>
                <option value="">Select Document Type</option>
                <option value="passport" <?= isset($_POST['document_type']) && $_POST['document_type'] == 'passport' ? 'selected' : '' ?>>Passport</option>
                <option value="id_card" <?= isset($_POST['document_type']) && $_POST['document_type'] == 'id_card' ? 'selected' : '' ?>>National ID Card</option>
                <option value="drivers_license" <?= isset($_POST['document_type']) && $_POST['document_type'] == 'drivers_license' ? 'selected' : '' ?>>Driver's License</option>
            </select>

            <label for="document_number">Document Number:</label>
            <input type="text" name="document_number" id="document_number" value="<?= isset($_POST['document_number']) ? htmlspecialchars($_POST['document_number']) : '' ?>" required>

            <button type="submit">Submit Verification</button>
        </form>

        <a href="front_office.html">⬅ Back to Form</a>
    </div>
</body>
</html>
