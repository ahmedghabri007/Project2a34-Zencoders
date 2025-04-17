<?php
// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/config.php';

// DELETE logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM verification WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard_verification.php");
    exit();
}

// UPDATE logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("UPDATE verification SET document_type = ?, document_number = ?, status = ? WHERE id = ?");
    $stmt->execute([
        $_POST['document_type'],
        $_POST['document_number'],
        $_POST['status'],
        $_POST['id']
    ]);
    header("Location: dashboard_verification.php");
    exit();
}

// Fetch all verifications
$pdo = Config::getConnexion();
$verifications = $pdo->query("SELECT * FROM verification ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Dashboard</title>
    <link rel="stylesheet" href="dashboard_verification.css"> <!-- You can change the path -->
    <link rel="stylesheet" href="dashboard_verification.css">
</head>
<body>
    <div class="container">
        <h1>📋 Verification Dashboard</h1>

        <?php if (!empty($verifications)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document Type</th>
                        <th>Document Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifications as $doc): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                <td><?= $doc['id'] ?></td>
                                <td>
                                    <select name="document_type">
                                        <option value="CNI" <?= $doc['document_type'] == 'CNI' ? 'selected' : '' ?>>CNI</option>
                                        <option value="PASSEPORT" <?= $doc['document_type'] == 'PASSEPORT' ? 'selected' : '' ?>>PASSEPORT</option>
                                        <option value="PERMIS" <?= $doc['document_type'] == 'PERMIS' ? 'selected' : '' ?>>PERMIS</option>
                                    </select>
                                </td>
                                <td><input type="text" name="document_number" value="<?= htmlspecialchars($doc['document_number']) ?>"></td>
                                <td>
                                    <select name="status">
                                        <option value="PENDING" <?= $doc['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                        <option value="APPROVED" <?= $doc['status'] == 'APPROVED' ? 'selected' : '' ?>>APPROVED</option>
                                        <option value="REJECTED" <?= $doc['status'] == 'REJECTED' ? 'selected' : '' ?>>REJECTED</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn-update" type="submit" name="update">Update</button>
                                    <a class="btn-delete" href="?delete=<?= $doc['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No verification records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
