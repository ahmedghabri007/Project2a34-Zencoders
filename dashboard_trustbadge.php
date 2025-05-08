<?php
// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/config.php';

// DELETE logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM trustbadge WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard_trustbadge.php");
    exit();
}

// UPDATE logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Ensure verification_id is provided and valid
    if (empty($_POST['verification_id'])) {
        die('Verification ID is required.');
    }
    $rejected_reason = empty($_POST['rejected_reason']) ? NULL : $_POST['rejected_reason'];
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("
    UPDATE trustbadge
    SET 
        type_badge = ?, 
        niveau_confiance = ?, 
        date_attribution = ?, 
        status = ?, 
        rejected_reason = ?, 
        last_reviewed = ?, 
        notes = ?, 
        user_report = ?, 
        report_status = ?, 
        report_reviewed_at = ?
    WHERE id = ?
");
$stmt->execute([
    $_POST['type_badge'],
    $_POST['niveau_confiance'],
    $_POST['date_attribution'] ?? date('Y-m-d H:i:s'),
    $_POST['status'],
    $rejected_reason,
    $_POST['last_reviewed'] ?? date('Y-m-d H:i:s'),
    $_POST['notes'],
    $_POST['user_report'],
    $_POST['report_status'],
    $_POST['report_reviewed_at'] ?? NULL,
    $_POST['id']
]);
    header("Location: dashboard_trustbadge.php");
    exit();
}

// Fetch all trustbadge records
$pdo = Config::getConnexion();
$trustbadges = $pdo->query("SELECT * FROM trustbadge ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Trustbadge Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table td, .table th { vertical-align: middle; }
        form { margin: 0; }
        .btn { padding: 4px 10px; }
        textarea { resize: vertical; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Trustbadge Dashboard</h1>

        <?php if (!empty($trustbadges)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Verification ID</th>
                        <th>Badge Type</th>
                        <th>Confidence Level</th>
                        <th>Date Attribution</th>
                        <th>Status</th>
                        <th>Rejected Reason</th>
                        <th>Last Reviewed</th>
                        <th>Notes</th>
                        <th>User Report</th>
                        <th>Report Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trustbadges as $badge): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $badge['id'] ?>">
                                <td><?= $badge['id'] ?></td>
                                <td>
                                    <input type="text" name="verification_id" value="<?= htmlspecialchars($badge['verification_id']) ?>" required>
                                </td>
                                <td>
                                    <select name="type_badge">
                                        <option value="bronze" <?= $badge['type_badge'] == 'bronze' ? 'selected' : '' ?>>Bronze</option>
                                        <option value="silver" <?= $badge['type_badge'] == 'silver' ? 'selected' : '' ?>>Silver</option>
                                        <option value="gold" <?= $badge['type_badge'] == 'gold' ? 'selected' : '' ?>>Gold</option>
                                    </select>
                                </td>
                                <td><input type="number" name="niveau_confiance" value="<?= htmlspecialchars($badge['niveau_confiance']) ?>" required></td>
                                <td><input type="datetime-local" name="date_attribution" value="<?= htmlspecialchars($badge['date_attribution']) ?>" required></td>
                                <td>
                                    <select name="status">
                                        <option value="approved" <?= $badge['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $badge['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </td>
                                <td><input type="text" name="rejected_reason" value="<?= htmlspecialchars($badge['rejected_reason']) ?>"></td>
                                <td><input type="datetime-local" name="last_reviewed" value="<?= htmlspecialchars($badge['last_reviewed']) ?>"></td>
                                <td><textarea name="notes"><?= htmlspecialchars($badge['notes']) ?></textarea></td>
                                <td><textarea name="user_report"><?= htmlspecialchars($badge['user_report']) ?></textarea></td>
                                <td>
                                    <select name="report_status">
                                        <option value="pending" <?= $badge['report_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="reviewed" <?= $badge['report_status'] == 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn-update" type="submit" name="update">Update</button>
                                    <a class="btn-delete" href="?delete=<?= $badge['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No trustbadge records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
