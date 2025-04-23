<?php
// This file should only be included from view.php
defined('FORUM_VIEW') or die('Direct access not permitted');
?>

<div class="container mt-4">
    <h3>Comments</h3>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Add Comment Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="/project-2a34/index.php?action=addComment" onsubmit="return validateCommentForm()">
                <div class="mb-3">
                    <textarea class="form-control" id="comment" name="comment" 
                        rows="3" 

                        placeholder="Write your comment here..."></textarea>
                    <div class="invalid-feedback" id="commentError"></div>
                </div>
                <input type="hidden" name="thread" value="<?= htmlspecialchars($forum['id_forum']) ?>">
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>
    </div>

    <!-- List of Comments -->
    <div class="comments-list">
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">No comments yet. Be the first to comment!</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3" id="comment-<?= $post['id_post'] ?>">
                    <div class="card-body">
                        <div class="d-flex">
                            <!-- Vote buttons -->
                            <div class="me-3 text-center">
                                <a href="/project-2a34/index.php?action=vote&id=<?= $post['id_post'] ?>&type=up&thread=<?= $forum['id_forum'] ?>" 
                                   class="btn btn-sm btn-outline-success d-block mb-1">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    <span class="badge bg-secondary"><?= $post['upvote'] ?? 0 ?></span>
                                </a>
                                <a href="/project-2a34/index.php?action=vote&id=<?= $post['id_post'] ?>&type=down&thread=<?= $forum['id_forum'] ?>" 
                                   class="btn btn-sm btn-outline-danger d-block">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    <span class="badge bg-secondary"><?= $post['downvote'] ?? 0 ?></span>
                                </a>
                            </div>
                            <!-- Comment content -->
                            <div class="flex-grow-1">
                                <!-- Normal view mode -->
                                <div id="comment-view-<?= $post['id_post'] ?>">
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($post['comment'])) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Score: <?= ($post['upvote'] ?? 0) - ($post['downvote'] ?? 0) ?></small>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showEditForm(<?= $post['id_post'] ?>)">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <a href="/project-2a34/index.php?action=deleteComment&id=<?= $post['id_post'] ?>&thread=<?= $forum['id_forum'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Edit form (hidden by default) -->
                                <div id="comment-edit-<?= $post['id_post'] ?>" style="display: none;">
                                    <form method="post" action="/project-2a34/index.php?action=editComment&id=<?= $post['id_post'] ?>&thread=<?= $forum['id_forum'] ?>" onsubmit="return validateEditForm(<?= $post['id_post'] ?>)">
                                        <div class="mb-3">
                                            <textarea class="form-control" id="edit-comment-<?= $post['id_post'] ?>" name="comment" rows="3"><?= htmlspecialchars($post['comment']) ?></textarea>
                                            <div class="invalid-feedback" id="edit-comment-error-<?= $post['id_post'] ?>"></div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-sm btn-secondary me-2" onclick="hideEditForm(<?= $post['id_post'] ?>)">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- End of Comments Section -->

<script>
function validateCommentForm() {
    var isValid = true;
    var comment = document.getElementById('comment');
    var commentError = document.getElementById('commentError');

    // Reset previous errors
    comment.className = comment.className.replace(" is-invalid", "");

    // Validate comment
    if (comment.value.length < 2) {
        comment.className += " is-invalid";
        commentError.innerHTML = 'Comment must be at least 2 characters long';
        isValid = false;
    } else if (comment.value.length > 1000) {
        comment.className += " is-invalid";
        commentError.innerHTML = 'Comment must not exceed 1000 characters';
        isValid = false;
    }

    return isValid;
}

function validateEditForm(commentId) {
    var isValid = true;
    var comment = document.getElementById('edit-comment-' + commentId);
    var commentError = document.getElementById('edit-comment-error-' + commentId);

    // Reset previous errors
    comment.className = comment.className.replace(" is-invalid", "");

    // Validate comment
    if (comment.value.length < 2) {
        comment.className += " is-invalid";
        commentError.innerHTML = 'Comment must be at least 2 characters long';
        isValid = false;
    } else if (comment.value.length > 1000) {
        comment.className += " is-invalid";
        commentError.innerHTML = 'Comment must not exceed 1000 characters';
        isValid = false;
    }

    return isValid;
}

function showEditForm(commentId) {
    // Hide the view div and show the edit form
    document.getElementById('comment-view-' + commentId).style.display = 'none';
    document.getElementById('comment-edit-' + commentId).style.display = 'block';
}

function hideEditForm(commentId) {
    // Hide the edit form and show the view div
    document.getElementById('comment-edit-' + commentId).style.display = 'none';
    document.getElementById('comment-view-' + commentId).style.display = 'block';
}
</script>
