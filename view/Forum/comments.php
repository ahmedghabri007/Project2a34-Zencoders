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
                        required 
                        minlength="2"
                        maxlength="1000"
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
                                <p class="mb-1"><?= nl2br(htmlspecialchars($post['comment'])) ?></p>
                                <small class="text-muted">Score: <?= ($post['upvote'] ?? 0) - ($post['downvote'] ?? 0) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../view/FrontOffice/footer.php'; ?>
</div>

<script>
function validateCommentForm() {
    let isValid = true;
    const comment = document.getElementById('comment');
    const commentError = document.getElementById('commentError');

    // Reset previous errors
    comment.classList.remove('is-invalid');

    // Validate comment
    if (comment.value.length < 2) {
        comment.classList.add('is-invalid');
        commentError.textContent = 'Comment must be at least 2 characters long';
        isValid = false;
    } else if (comment.value.length > 1000) {
        comment.classList.add('is-invalid');
        commentError.textContent = 'Comment must not exceed 1000 characters';
        isValid = false;
    }

    return isValid;
}

function votePost(postId, voteType) {
    fetch(`/project-2a34/index.php?action=vote&id=${postId}&type=${voteType}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to vote. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while voting.');
    });
}
</script>
