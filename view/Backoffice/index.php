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
                            <!-- Table content will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Add/Edit User -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New User</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="userForm">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="18" max="100" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="lifeStatus">Life Status</label>
                    <select id="lifeStatus" name="lifeStatus" required>
                        <option value="">Select Status</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small>Leave blank to keep current password when editing</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Deletion</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <button class="btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

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

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script src="script.js"></script>
</body>
</html>