<?php
// Inclure le contrôleur
include '../../controller/controlleradmin.php';

$users = showUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="#"><i class="fas fa-users"></i> Users</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-chart-bar"></i> Statistics</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search users...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="top-bar-actions">
                    <button id="addUserBtn" class="btn-primary">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                    <div class="profile-bubble" id="profileBubble">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=4a90e2&color=fff" alt="Profile" class="profile-image">
                        <div class="profile-dropdown">
                            <div class="profile-info">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=4a90e2&color=fff" alt="Profile" class="dropdown-profile-image">
                                <div class="profile-details">
                                    <h4>Admin User</h4>
                                    <p>admin@example.com</p>
                                </div>
                            </div>
                            <ul>
                                <li><a href="#" id="editProfileBtn"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                                <li><a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                <div class="content-header">
                    <h1>User Management</h1>
                    <div class="filters">
                        <select id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <select id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                        </select>
                    </div>
                </div>

                <div class="table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Life Status</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php if (!empty($users)) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['phone']) ?></td>
                                        <td><?= htmlspecialchars($user['age']) ?></td>
                                        <td><?= htmlspecialchars($user['gender']) ?></td>
                                        <td><?= htmlspecialchars($user['life_status']) ?></td>
                                        <td><?= htmlspecialchars($user['role']) ?></td>
                                        <td>

                                            <a href="?edit_user=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>



                                            <a href="?confirm_delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="9">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Modal for Add/Edit User -->
    <?php if (isset($_GET['edit_user'])):
        $editUser = ModelClient::getById(intval($_GET['edit_user']));
        if ($editUser): ?>
            <div class="modal" style="display:block;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Modifier l'utilisateur</h2>
                    </div>
                    <form method="POST" action="../../controller/controlleradmin.php?action=update&id=<?= $editUser->getId() ?>">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" name="fullname" value="<?= htmlspecialchars($editUser->getFullname()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($editUser->getEmail()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($editUser->getPhone()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Âge</label>
                            <input type="number" name="age" value="<?= htmlspecialchars($editUser->getAge()) ?>" min="18" max="100" required>
                        </div>
                        <div class="form-group">
                            <label>Genre</label>
                            <select name="gender" required>
                                <option value="male" <?= $editUser->getGender() === 'male' ? 'selected' : '' ?>>Homme</option>
                                <option value="female" <?= $editUser->getGender() === 'female' ? 'selected' : '' ?>>Femme</option>
                                <option value="other" <?= $editUser->getGender() === 'other' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="life_status" required>
                                <option value="single" <?= $editUser->getLifeStatus() === 'single' ? 'selected' : '' ?>>Célibataire</option>
                                <option value="married" <?= $editUser->getLifeStatus() === 'married' ? 'selected' : '' ?>>Marié</option>
                                <option value="divorced" <?= $editUser->getLifeStatus() === 'divorced' ? 'selected' : '' ?>>Divorcé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rôle</label>
                            <select name="role" required>
                                <option value="student" <?= $editUser->getRole() === 'student' ? 'selected' : '' ?>>student</option>
                                <option value="businessman" <?= $editUser->getRole() === 'businessman' ? 'selected' : '' ?>>business man</option>
                                <option value="professional" <?= $editUser->getRole() === 'professional' ? 'selected' : '' ?>>professional</option>
                                <option value="entrepreneur" <?= $editUser->getRole() === 'entrepreneur' ? 'selected' : '' ?>>entrepreneur</option>

                            </select>
                        </div>
                        <div class="modal-footer">
                            <a href="index.php" class="btn-secondary">Annuler</a>
                            <button type="submit" class="btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
    <?php endif;
    endif; ?>

    <?php if (isset($_GET['confirm_delete'])): ?>
        <div class="modal" style="display:block;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Confirmation</h2>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
                </div>
                <div class="modal-footer">
                    <a href="../../controller/controlleradmin.php?action=supprimer&id=<?= intval($_GET['confirm_delete']) ?>" class="btn-danger">Oui, Supprimer</a>
                    <a href="index.php" class="btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- Profile Edit Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="profileForm">
                <div class="profile-image-upload">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=4a90e2&color=fff" alt="Profile" id="profilePreview">
                    <label for="profileImage" class="upload-btn">
                        <i class="fas fa-camera"></i>
                        Change Photo
                    </label>
                    <input type="file" id="profileImage" accept="image/*" hidden>
                </div>

                <div class="form-group">
                    <label for="profileName">Full Name</label>
                    <input type="text" id="profileName" name="profileName" value="Admin User" required>
                </div>

                <div class="form-group">
                    <label for="profileEmail">Email</label>
                    <input type="email" id="profileEmail" name="profileEmail" value="admin@example.com" required>
                </div>

                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword">
                </div>

                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword">
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelProfileBtn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>



</body>

</html>