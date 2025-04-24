<?php
// Activer le rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/config.php'; // adapte ce chemin si besoin

// Vérifier si l'ID du match et de profile sont fournis dans l'URL
if (isset($_GET['idmatch']) && isset($_GET['idprofile'])) {
    $idmatch = intval($_GET['idmatch']); // Assurez-vous que l'ID du match est un entier
    $idprofile = intval($_GET['idprofile']); // Assurez-vous que l'ID du profile est un entier

    try {
        $pdo = Config::getConnexion();

        // Vérifier si le match appartient bien au profile donné
        $stmt = $pdo->prepare("SELECT * FROM matches WHERE idmatch = ? AND idprofile = ?");
        $stmt->execute([$idmatch, $idprofile]);

        // Si un match est trouvé avec ces deux critères, on peut le supprimer
        if ($stmt->rowCount() > 0) {
            // Supprimer le match de la table 'matches'
            $deleteStmt = $pdo->prepare("DELETE FROM matches WHERE idmatch = ?");
            $deleteStmt->execute([$idmatch]);

            // Redirection après suppression
            header("Location: dashboard_matches.php"); // Redirige vers la page des matches
            exit();
        } else {
            echo "Le match n'existe pas pour ce profile ou les identifiants ne correspondent pas.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du match : " . $e->getMessage();
    }
} else {
    echo "Aucun ID de match ou ID de profile fourni.";
}
?>
