<?php require_once __DIR__ . '/header.php'; ?>

<div class="container mt-4">
    <h1>Edit Comment</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/project-2a34/index.php?action=list">Forums</a></li>
            <li class="breadcrumb-item"><a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>"><?= htmlspecialchars($forum['sujet'] ?? 'Thread') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Comment</li>
        </ol>
    </nav>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="/project-2a34/index.php?action=editComment&id=<?= htmlspecialchars($comment['id_post']) ?>&thread=<?= htmlspecialchars($forum['id_forum']) ?>" onsubmit="return validateCommentForm()">
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="5"><?= htmlspecialchars($comment['comment'] ?? '') ?></textarea>
                    <div class="invalid-feedback" id="commentError"></div>
                </div>
                <button type="submit" class="btn btn-primary">Update Comment</button>
                <a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

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
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
