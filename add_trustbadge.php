<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$pdo = Config::getConnexion();
$message = '';
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_id = $_POST['verification_id'] ?? '';
    $type_badge = $_POST['type_badge'] ?? '';
    $niveau_confiance = $_POST['niveau_confiance'] ?? '';
    $status = $_POST['status'] ?? '';
    $report_status = $_POST['report_status'] ?? '';
    $user_report = $_POST['user_report'] ?? null;

    if (
        empty($verification_id) ||
        empty($type_badge) ||
        $niveau_confiance === '' ||
        empty($status) ||
        empty($report_status)
    ) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO trustbadge (verification_id, type_badge, niveau_confiance, status, report_status, user_report)
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $verification_id,
                $type_badge,
                $niveau_confiance,
                $status,
                $report_status,
                $user_report
            ]);

            if ($stmt->rowCount() > 0) {
                $message = "Trustbadge ajouté avec succès.";
                $success = true;
            } else {
                $message = "Erreur lors de l'ajout.";
            }

        } catch (PDOException $e) {
            $message = "Erreur DB : " . $e->getMessage();
        }
    }
}

// Fetch verification IDs for select
$verifications = $pdo->query("SELECT id FROM verification ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Trustbadge</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .container { max-width: 600px; margin-top: 40px; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Ajouter un Trustbadge</h2>

    <?php if ($message): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_trustbadge.php">
        <div class="form-group">
            <label for="verification_id">ID de Vérification *</label>
            <select name="verification_id" id="verification_id" class="form-control" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($verifications as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= $v['id'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="type_badge">Type de Badge *</label>
            <select name="type_badge" id="type_badge" class="form-control" required>
                <option value="">-- Sélectionner --</option>
                <option value="bronze">Bronze</option>
                <option value="silver">Silver</option>
                <option value="gold">Gold</option>
            </select>
        </div>

        <div class="form-group">
            <label for="niveau_confiance">Niveau de Confiance *</label>
            <input type="number" name="niveau_confiance" id="niveau_confiance" class="form-control" min="0" max="100" required>
        </div>

        <div class="form-group">
            <label for="status">Statut *</label>
            <select name="status" id="status" class="form-control" required>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="form-group">
            <label for="report_status">Statut du Rapport *</label>
            <select name="report_status" id="report_status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="reviewed">Reviewed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="user_report">Rapport Utilisateur</label>
            <textarea name="user_report" id="user_report" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
</body>
</html>
