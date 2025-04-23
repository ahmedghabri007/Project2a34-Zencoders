<?php
require_once '../../Controller/BackOfficeController.php';
require_once 'header.php';

$controller = new BackOfficeController();
$users = $controller->getAllUsers();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">User Management</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars($user['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-<?= $user['status'] === 'active' ? 'warning' : 'success' ?>"
                                            onclick="updateUserStatus(<?= $user['id'] ?>, '<?= $user['status'] === 'active' ? 'inactive' : 'active' ?>')">
                                        <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteUser(<?= $user['id'] ?>)">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateUserStatus(userId, status) {
    if (confirm('Are you sure you want to ' + (status === 'active' ? 'activate' : 'deactivate') + ' this user?')) {
        window.location.href = '/project-2a34/index.php?action=updateUserStatus&id=' + userId + '&status=' + status;
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = '/project-2a34/index.php?action=deleteUser&id=' + userId;
    }
}

$(document).ready(function() {
    $('#usersTable').DataTable({
        "order": [[4, "desc"]]
    });
});
</script>

<?php require_once 'footer.php'; ?>
