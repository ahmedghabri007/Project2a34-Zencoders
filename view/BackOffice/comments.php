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
                    $backOffice->toggleCommentStatus($_POST['id']);
                }
                break;
            case 'delete':
                if (isset($_POST['id'])) {
                    $backOffice->deleteComment($_POST['id']);
                }
                break;
        }
    }
    // Redirect back to the same page
    header('Location: comments.php');
    exit;
}

$comments = $backOffice->getAllComments();
include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ti ti-message-dots text-primary"></i>
            Comments Management
        </div>
    </div>
    <div class="card-body">
        <table class="table" id="commentsTable">
            <thead>
                <tr>
                    <th>Thread</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td>
                        <div class="thread-title">
                            <i class="ti ti-message-2 text-primary"></i>
                            <?php echo htmlspecialchars($comment['thread_title']); ?>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $preview = strlen($comment['comment']) > 30 
                            ? htmlspecialchars(substr($comment['comment'], 0, 30)) . '...' 
                            : htmlspecialchars($comment['comment']);
                        ?>
                        <div class="comment-preview">
                            <i class="ti ti-message text-muted"></i>
                            <?php echo $preview; ?>
                        </div>
                    </td>
                    <td>
                        <div class="date-info">
                            <i class="ti ti-calendar text-muted"></i>
                            <?php echo date('Y-m-d H:i', strtotime($comment['date_publication'])); ?>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $status = isset($comment['status']) ? $comment['status'] : 'active';
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
                            <input type="hidden" name="id" value="<?php echo $comment['id_post']; ?>">
                            <input type="hidden" name="action" value="toggleStatus">
                            <button type="submit" class="btn btn-warning btn-sm" 
                                    onclick="return confirm('Are you sure you want to <?php echo $status === 'active' ? 'deactivate' : 'activate'; ?> this comment?');">
                                <i class="ti ti-toggle-right"></i>
                            </button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $comment['id_post']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to delete this comment? This action cannot be undone.');">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-info btn-sm view-comment" 
                                data-id="<?php echo $comment['id_post']; ?>"
                                data-thread="<?php echo htmlspecialchars($comment['thread_title']); ?>"
                                data-comment="<?php echo htmlspecialchars($comment['comment']); ?>"
                                data-date="<?php echo date('Y-m-d H:i', strtotime($comment['date_publication'])); ?>">
                            <i class="ti ti-eye"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- View Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Thread</label>
                    <div id="commentThread" class="form-control-plaintext"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <div id="commentText" class="form-control-plaintext"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <div id="commentDate" class="form-control-plaintext"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#commentsTable').DataTable({
        order: [[2, 'desc']], // Sort by date by default
        pageLength: 10
    });

    // View Comment
    $('.view-comment').on('click', function() {
        const thread = $(this).data('thread');
        const comment = $(this).data('comment');
        const date = $(this).data('date');

        $('#commentThread').text(thread);
        $('#commentText').text(comment);
        $('#commentDate').text(date);
        
        new bootstrap.Modal(document.getElementById('commentModal')).show();
    });
});
</script>

<?php include 'footer.php'; ?>
