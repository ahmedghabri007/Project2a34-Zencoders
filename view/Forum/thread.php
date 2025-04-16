<?php
require_once __DIR__ . '/../../view/FrontOffice/header.php';
?>

<div class="container mt-4">
    <?php if (isset($thread)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($thread['sujet']); ?></h2>
                <small class="text-muted">Posted on: <?php echo date('F j, Y', strtotime($thread['date_publication'])); ?></small>
            </div>
            <div class="card-body">
                <div class="thread-content mb-4">
                    <?php echo nl2br(htmlspecialchars($thread['contenu'])); ?>
                </div>
                
                <hr>
                
                <!-- Comments section -->
                <h4 class="mb-4">Comments</h4>
                
                <!-- Comment form -->
                <form action="/project-2a34/index.php?action=addComment" method="POST" class="mb-4">
                    <input type="hidden" name="thread" value="<?php echo $thread['id_forum']; ?>">
                    <div class="form-group">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Write your comment here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Add Comment</button>
                </form>

                <!-- Display comments -->
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="card mb-2" id="comment-<?php echo $comment['id_post']; ?>">
                            <div class="card-body">
                                <p class="mb-1"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Posted on: <?php echo date('F j, Y', strtotime($comment['date_comment'])); ?></small>
                                    <div class="btn-group">
                                        <a href="/project-2a34/index.php?action=vote&id=<?php echo $comment['id_post']; ?>&type=up&thread=<?php echo $thread['id_forum']; ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-up"></i> <?php echo $comment['upvote']; ?>
                                        </a>
                                        <a href="/project-2a34/index.php?action=vote&id=<?php echo $comment['id_post']; ?>&type=down&thread=<?php echo $thread['id_forum']; ?>" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-arrow-down"></i> <?php echo $comment['downvote']; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No comments yet. Be the first to comment!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            Thread not found.
        </div>
    <?php endif; ?>
    
    <a href="/project-2a34/index.php" class="btn btn-secondary">Back to Threads</a>
</div>

<?php
require_once __DIR__ . '/../../view/FrontOffice/footer.php';
?>
