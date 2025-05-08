<?php require_once __DIR__ . '/header.php'; ?>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">User Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/project-2a34/index.php?action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add New User</h4>
                            <form method="post" action="/project-2a34/index.php?action=addUser" class="forms-sample" onsubmit="return validateUserForm()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                            <div class="invalid-feedback" id="usernameError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="investor">Investor</option>
                                                <option value="entrepreneur">Entrepreneur</option>
                                            </select>
                                            <div class="invalid-feedback" id="roleError"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="linkedin_url">LinkedIn URL</label>
                                            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/in/username">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="instagram_url">Instagram URL</label>
                                            <input type="url" class="form-control" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/username">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="facebook_url">Facebook URL</label>
                                            <input type="url" class="form-control" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/username">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Users List</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Social Media</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($users)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No users found.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?= $user['id'] ?></td>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td>
                                                        <span class="badge <?= $user['role'] === 'investor' ? 'bg-success' : 'bg-info' ?>">
                                                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($user['linkedin_url'])): ?>
                                                            <a href="<?= htmlspecialchars($user['linkedin_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="mdi mdi-linkedin"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($user['instagram_url'])): ?>
                                                            <a href="<?= htmlspecialchars($user['instagram_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="mdi mdi-instagram"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($user['facebook_url'])): ?>
                                                            <a href="<?= htmlspecialchars($user['facebook_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="mdi mdi-facebook"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></td>
                                                    <td>
                                                        <a href="/project-2a34/index.php?action=editUser&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="mdi mdi-pencil"></i> Edit
                                                        </a>
                                                        <a href="/project-2a34/index.php?action=deleteUser&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <i class="mdi mdi-delete"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateUserForm() {
    var isValid = true;
    var username = document.getElementById('username');
    var role = document.getElementById('role');
    var usernameError = document.getElementById('usernameError');
    var roleError = document.getElementById('roleError');

    // Reset previous errors
    username.className = username.className.replace(" is-invalid", "");
    role.className = role.className.replace(" is-invalid", "");

    // Validate username
    if (username.value.length < 2) {
        username.className += " is-invalid";
        usernameError.innerHTML = 'Username must be at least 2 characters long';
        isValid = false;
    }

    // Validate role
    if (role.value === '') {
        role.className += " is-invalid";
        roleError.innerHTML = 'Please select a role';
        isValid = false;
    }

    return isValid;
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
