<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$pdo = Config::getConnexion();

// Handle match form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idprofile = $_POST['idprofile'];
    $match_type = $_POST['match_type'];
    $description = $_POST['description'];
    $date_created = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO matches (idprofile, match_type, description, date_created) VALUES (?, ?, ?, ?)");
    $stmt->execute([$idprofile, $match_type, $description, $date_created]);
}

// Fetch all profiles (for dropdown)
$profiles = $pdo->query("SELECT idprofile, fullname FROM profile")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all matches
$matches = $pdo->query("
    SELECT m.*, p.fullname 
    FROM matches m 
    JOIN profile p ON m.idprofile = p.idprofile 
    ORDER BY m.idmatch DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Matches</title>
    <link rel="stylesheet" href="findyourmatch.css">
    <style>
        .form-container { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add a Match</h1>
        <form method="POST" action="addmatch.php">
            <label>Matched Profile:</label>
            <select name="idprofile" required>
                <?php foreach ($profiles as $profile): ?>
                    <option value="<?= $profile['idprofile'] ?>"><?= htmlspecialchars($profile['fullname']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Match Type:</label>
            <input type="text" name="match_type" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit">Add Match</button>
        </form>
    </div>

    <div class="match-list">
        <h2>Existing Matches</h2>
        <?php if (count($matches) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Match Type</th>
                        <th>Description</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matches as $match): ?>
                        <tr>
                            <td><?= $match['idmatch'] ?></td>
                            <td><?= htmlspecialchars($match['fullname']) ?></td>
                            <td><?= htmlspecialchars($match['match_type']) ?></td>
                            <td><?= htmlspecialchars($match['description']) ?></td>
                            <td><?= $match['date_created'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No matches added yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
