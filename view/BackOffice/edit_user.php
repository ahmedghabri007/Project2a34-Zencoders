<?php require_once __DIR__ . '/header.php'; ?>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">Edit User</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/project-2a34/index.php?action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/project-2a34/index.php?action=users">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                    </ol>
                </nav>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit User Details</h4>
                            <?php if (isset($user) && $user): ?>
                                <form method="post" action="/project-2a34/index.php?action=updateUser" class="forms-sample" onsubmit="return validateUserForm()">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                                <div class="invalid-feedback" id="usernameError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">Role</label>
                                                <select class="form-control" id="role" name="role" required>
                                                    <option value="">Select Role</option>
                                                    <option value="investor" <?= $user['role'] === 'investor' ? 'selected' : '' ?>>Investor</option>
                                                    <option value="entrepreneur" <?= $user['role'] === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                                                </select>
                                                <div class="invalid-feedback" id="roleError"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="linkedin_url">LinkedIn URL</label>
                                                <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($user['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/username">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="instagram_url">Instagram URL</label>
                                                <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?= htmlspecialchars($user['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/username">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="facebook_url">Facebook URL</label>
                                                <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($user['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/username">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary me-2">Update User</button>
                                    <a href="/project-2a34/index.php?action=users" class="btn btn-light">Cancel</a>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">User not found.</div>
                                <a href="/project-2a34/index.php?action=users" class="btn btn-primary">Back to Users</a>
                            <?php endif; ?>
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
