<?php
require_once '../../Controller/BackOfficeController.php';
require_once '../../Controller/AuthController.php';

$backOffice = new BackOfficeController();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'toggleStatus':
                if (isset($_POST['id'])) {
                    $backOffice->toggleThreadStatus($_POST['id']);
                }
                break;
            case 'delete':
                if (isset($_POST['id'])) {
                    $backOffice->deleteThread($_POST['id']);
                }
                break;
        }
    }
    // Redirect back to the same page
    header('Location: threads.php');
    exit;
}

$threads = $backOffice->getAllThreads();
include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ti ti-messages text-primary"></i>
            Threads Management
        </div>
    </div>
    <div class="card-body">
        <table class="table" id="threadsTable">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($threads as $thread): ?>
                <tr>
                    <td>
                        <div class="thread-title">
                            <i class="ti ti-message-2 text-primary"></i>
                            <?php echo htmlspecialchars($thread['sujet']); ?>
                        </div>
                    </td>
                    <td>
                        <div class="date-info">
                            <i class="ti ti-calendar text-muted"></i>
                            <?php echo date('Y-m-d H:i', strtotime($thread['date_publication'])); ?>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $status = isset($thread['status']) ? $thread['status'] : 'active';
                        $statusClass = $status === 'active' ? 'success' : 'danger';
                        $statusIcon = $status === 'active' ? 'check' : 'x';
                        ?>
                        <span class="badge badge-<?php echo $statusClass; ?>">
                            <i class="ti ti-<?php echo $statusIcon; ?>"></i>
                            <?php echo ucfirst($status); ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $thread['id_forum']; ?>">
                            <input type="hidden" name="action" value="toggleStatus">
                            <button type="submit" class="btn btn-warning btn-sm" 
                                    onclick="return confirm('Are you sure you want to <?php echo $status === 'active' ? 'deactivate' : 'activate'; ?> this thread?');">
                                <i class="ti ti-toggle-right"></i>
                            </button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $thread['id_forum']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to delete this thread? All comments in this thread will also be deleted. This action cannot be undone.');">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#threadsTable').DataTable({
        order: [[1, 'desc']], // Sort by date by default
        pageLength: 10
    });
});
</script>

<?php include 'footer.php'; ?>
