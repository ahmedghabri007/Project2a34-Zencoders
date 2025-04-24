<?php
include '../../config/config.php';

if (isset($_GET['idmatch'])) {
    $idmatch = $_GET['idmatch'];

    try {
        $stmt = $pdo->prepare("DELETE FROM find_your_match WHERE idmatch = :idmatch");
        $stmt->execute([':idmatch' => $idmatch]);

        if ($stmt->rowCount()) {
            header("Location: find_match.php?success=1");
            exit();
        } else {
            echo "No match found to delete.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
