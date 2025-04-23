<?php
require_once '../../Controller/BackOfficeController.php';
require_once '../../Controller/AuthController.php';

$controller = new BackOfficeController();
$categories = $controller->getAllCategories();

include 'header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Category Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="ti ti-plus"></i> Add Category
        </button>
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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">
                <i class="ti ti-category"></i> Categories List
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Thread Count</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['id']) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-folder text-primary me-2"></i>
                                    <?= htmlspecialchars($category['name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($category['description']) ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="ti ti-message-2"></i>
                                    <?= htmlspecialchars($category['thread_count'] ?? 0) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary edit-category" 
                                        data-id="<?= htmlspecialchars($category['id']) ?>"
                                        data-name="<?= htmlspecialchars($category['name']) ?>"
                                        data-description="<?= htmlspecialchars($category['description']) ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editCategoryModal">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-category"
                                        data-id="<?= htmlspecialchars($category['id']) ?>"
                                        data-name="<?= htmlspecialchars($category['name']) ?>">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../Controller/CategoryController.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="action" value="add">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../Controller/CategoryController.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category "<span id="delete_category_name"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form action="../../Controller/CategoryController.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="delete_category_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#categoriesTable').DataTable({
        order: [[0, 'desc']]
    });

    // Edit Category Modal
    $('.edit-category').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
    });

    // Delete Category Modal
    $('.delete-category').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#delete_category_id').val(id);
        $('#delete_category_name').text(name);
        $('#deleteCategoryModal').modal('show');
    });
});
</script>

<?php include 'footer.php'; ?>
