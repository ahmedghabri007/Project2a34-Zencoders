<?php
include '../../config/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['idmatch'])) {
    $idmatch = $_GET['idmatch'];

    $query = "SELECT * FROM find_your_match WHERE idmatch = :idmatch";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idmatch' => $idmatch]);
    $match = $stmt->fetch();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idmatch = $_POST['idmatch'];
    $idprofile = $_POST['idprofile'];
    $match_type = $_POST['match_type'];
    $description = $_POST['description'];

    if (empty($idprofile) || empty($match_type) || empty($description)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE find_your_match SET match_type = :match_type, description = :description WHERE idmatch = :idmatch");
            $stmt->execute([
                ':match_type' => $match_type,
                ':description' => $description,
                ':idmatch' => $idmatch
            ]);

            if ($stmt->rowCount()) {
                header("Location: find_match.php?success=1");
                exit();
            } else {
                $error = "Une erreur s'est produite lors de la mise à jour du match.";
            }
        } catch (PDOException $e) {
            $error = "Erreur base de données : " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match</title>
</head>
<body>
    <h1>Edit Match</h1>
    <?php if (!empty($error)) echo "<p style='color: red;'>❌ $error</p>"; ?>
    <form method="POST">
        <input type="hidden" name="idmatch" value="<?= $match['idmatch'] ?>">
        <label>Match Type:</label>
        <input type="text" name="match_type" value="<?= $match['match_type'] ?>" required>
        <label>Description:</label>
        <textarea name="description" required><?= $match['description'] ?></textarea>
        <button type="submit">Update Match</button>
    </form>
</body>
</html>
