<?php
// Dashboard management similar to `list_badge_upgrade.php`
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Badge Upgrade Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="sb-admin-2.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <h1>📋 Badge Upgrade Dashboard</h1>

        <?php if (!empty($badgeUpgrades)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>New Document Type</th>
                        <th>New Document Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($badgeUpgrades as $upgrade): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $upgrade['id'] ?>">
                                <td><?= $upgrade['id'] ?></td>
                                <td><input type="text" name="new_document_type" value="<?= htmlspecialchars($upgrade['new_document_type']) ?>"></td>
                                <td><input type="text" name="new_document_number" value="<?= htmlspecialchars($upgrade['new_document_number']) ?>"></td>
                                <td>
                                    <select name="request_status">
                                        <option value="pending" <?= $upgrade['request_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $upgrade['request_status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $upgrade['request_status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" name="update">Update</button>
                                    <a href="?delete=<?= $upgrade['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No badge upgrade requests found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
