document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const userModal = document.getElementById('userModal');
    const deleteModal = document.getElementById('deleteModal');
    const profileModal = document.getElementById('profileModal');
    const userForm = document.getElementById('userForm');
    const profileForm = document.getElementById('profileForm');
    const addUserBtn = document.getElementById('addUserBtn');
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const usersTableBody = document.getElementById('usersTableBody');
    const editProfileBtn = document.getElementById('editProfileBtn');
    const profileImage = document.getElementById('profileImage');
    const profilePreview = document.getElementById('profilePreview');
    const toast = document.getElementById('toast');

    // Sample data (replace with actual data from your backend)
    let users = [
        {
            id: 1,
            full_name: "John Doe",
            email: "john@example.com",
            phone_number: "+1234567890",
            age: 30,
            gender: "male",
            life_status: "single",
            role: "admin"
        },
        {
            id: 2,
            full_name: "Jane Smith",
            email: "jane@example.com",
            phone_number: "+1987654321",
            age: 25,
            gender: "female",
            life_status: "married",
            role: "user"
        }
    ];

    // Initialize table with animation
    renderUsers(users);

    // Event Listeners
    addUserBtn.addEventListener('click', () => showModal('add'));
    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    statusFilter.addEventListener('change', filterUsers);
    userForm.addEventListener('submit', handleFormSubmit);
    profileForm.addEventListener('submit', handleProfileSubmit);
    editProfileBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showProfileModal();
    });

    // Profile image upload preview
    profileImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
                // Update all profile images in the UI
                document.querySelectorAll('.profile-image').forEach(img => {
                    img.src = e.target.result;
                });
                document.querySelector('.dropdown-profile-image').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === userModal || e.target === deleteModal || e.target === profileModal) {
            closeModals();
        }
    });

    // Close buttons in modals
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', closeModals);
    });

    document.getElementById('cancelBtn').addEventListener('click', closeModals);
    document.getElementById('cancelProfileBtn').addEventListener('click', closeModals);
    document.getElementById('cancelDeleteBtn').addEventListener('click', closeModals);
    document.getElementById('confirmDeleteBtn').addEventListener('click', handleDelete);

    // Functions
    function renderUsers(usersToRender) {
        usersTableBody.innerHTML = '';
        usersToRender.forEach((user, index) => {
            const row = document.createElement('tr');
            row.style.animationDelay = `${index * 0.1}s`;
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.full_name}</td>
                <td>${user.email}</td>
                <td>${user.phone_number}</td>
                <td>${user.age}</td>
                <td>${user.gender}</td>
                <td>${user.life_status}</td>
                <td>${user.role}</td>
                <td class="action-buttons">
                    <button class="action-btn view-btn" onclick="viewUser(${user.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn edit-btn" onclick="editUser(${user.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete-btn" onclick="showDeleteModal(${user.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            usersTableBody.appendChild(row);
        });
    }

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;

        const filteredUsers = users.filter(user => {
            const matchesSearch = user.full_name.toLowerCase().includes(searchTerm) ||
                                user.email.toLowerCase().includes(searchTerm);
            const matchesRole = !roleValue || user.role === roleValue;
            const matchesStatus = !statusValue || user.life_status === statusValue;

            return matchesSearch && matchesRole && matchesStatus;
        });

        renderUsers(filteredUsers);
    }

    function showModal(type, userId = null) {
        const modalTitle = document.getElementById('modalTitle');
        userModal.classList.add('active');
        
        if (type === 'add') {
            modalTitle.textContent = 'Add New User';
            userForm.reset();
        } else if (type === 'edit') {
            modalTitle.textContent = 'Edit User';
            const user = users.find(u => u.id === userId);
            if (user) {
                fillFormWithUserData(user);
            }
        }
        
        userForm.dataset.mode = type;
        userForm.dataset.userId = userId;
    }

    function showProfileModal() {
        profileModal.classList.add('active');
    }

    function fillFormWithUserData(user) {
        document.getElementById('fullName').value = user.full_name;
        document.getElementById('email').value = user.email;
        document.getElementById('phoneNumber').value = user.phone_number;
        document.getElementById('age').value = user.age;
        document.getElementById('gender').value = user.gender;
        document.getElementById('lifeStatus').value = user.life_status;
        document.getElementById('role').value = user.role;
        document.getElementById('password').value = '';
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = {
            full_name: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            phone_number: document.getElementById('phoneNumber').value,
            age: parseInt(document.getElementById('age').value),
            gender: document.getElementById('gender').value,
            life_status: document.getElementById('lifeStatus').value,
            role: document.getElementById('role').value,
            password: document.getElementById('password').value
        };

        const mode = userForm.dataset.mode;
        const userId = parseInt(userForm.dataset.userId);

        if (mode === 'add') {
            formData.id = users.length + 1;
            users.push(formData);
            showToast('User added successfully!', 'success');
        } else if (mode === 'edit') {
            const index = users.findIndex(u => u.id === userId);
            if (index !== -1) {
                users[index] = { ...users[index], ...formData };
            }
            showToast('User updated successfully!', 'success');
        }

        renderUsers(users);
        closeModals();
    }

    function handleProfileSubmit(e) {
        e.preventDefault();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword && newPassword !== confirmPassword) {
            showToast('Passwords do not match!', 'error');
            return;
        }

        showToast('Profile updated successfully!', 'success');
        closeModals();
    }

    function showDeleteModal(userId) {
        deleteModal.classList.add('active');
        document.getElementById('confirmDeleteBtn').dataset.userId = userId;
    }

    function handleDelete() {
        const userId = parseInt(document.getElementById('confirmDeleteBtn').dataset.userId);
        users = users.filter(user => user.id !== userId);
        renderUsers(users);
        closeModals();
        showToast('User deleted successfully!', 'success');
    }

    function closeModals() {
        userModal.classList.remove('active');
        deleteModal.classList.remove('active');
        profileModal.classList.remove('active');
        userForm.reset();
        profileForm.reset();
    }

    function showToast(message, type = 'success') {
        toast.textContent = message;
        toast.className = `toast ${type} show`;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Make functions available globally
    window.viewUser = function(userId) {
        const user = users.find(u => u.id === userId);
        if (user) {
            showToast(`Viewing user: ${user.full_name}`, 'success');
        }
    };

    window.editUser = function(userId) {
        showModal('edit', userId);
    };

    window.showDeleteModal = showDeleteModal;
});