<?php require_once __DIR__ . '/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Analytics Dashboard</h1>

    <div class="row">
        <!-- Top Hits Today -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Top Hits Today</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($topHits)): ?>
                        <div class="alert alert-info">No posts viewed today.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($topHits as $post): ?>
                                <a href="/project-2a34/index.php?action=view&id=<?= $post['id_forum'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?= htmlspecialchars($post['sujet']) ?></h5>
                                        <span class="badge bg-primary rounded-pill"><?= number_format($post['views']) ?> views</span>
                                    </div>
                                    <p class="mb-1"><?= mb_substr(htmlspecialchars($post['contenu']), 0, 100) ?>...</p>
                                    
                                    <?php if (isset($post['username'])): ?>
                                    <div class="d-flex align-items-center mt-2">
                                        <small class="text-muted me-2">Posted by:</small>
                                        <span class="badge <?= $post['role'] === 'investor' ? 'bg-success' : 'bg-info' ?> me-2">
                                            <?= ucfirst(htmlspecialchars($post['role'] ?? '')) ?>
                                        </span>
                                        <span class="me-2"><?= htmlspecialchars($post['username'] ?? '') ?></span>
                                        
                                        <!-- Social media links -->
                                        <?php if (!empty($post['linkedin_url'])): ?>
                                            <a href="<?= htmlspecialchars($post['linkedin_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($post['instagram_url'])): ?>
                                            <a href="<?= htmlspecialchars($post['instagram_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($post['facebook_url'])): ?>
                                            <a href="<?= htmlspecialchars($post['facebook_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Comments Today -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Top Comments Today</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($topComments)): ?>
                        <div class="alert alert-info">No comments posted today.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($topComments as $comment): ?>
                                <a href="/project-2a34/index.php?action=view&id=<?= $comment['thread'] ?>#comment-<?= $comment['id_post'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Comment on: <?= htmlspecialchars($comment['thread_title']) ?></h5>
                                        <span class="badge bg-success rounded-pill">Score: <?= ($comment['upvote'] - $comment['downvote']) ?></span>
                                    </div>
                                    <p class="mb-1"><?= mb_substr(htmlspecialchars($comment['comment']), 0, 100) ?>...</p>
                                    
                                    <?php if (isset($comment['username'])): ?>
                                    <div class="d-flex align-items-center mt-2">
                                        <small class="text-muted me-2">Posted by:</small>
                                        <span class="badge <?= $comment['role'] === 'investor' ? 'bg-success' : 'bg-info' ?> me-2">
                                            <?= ucfirst(htmlspecialchars($comment['role'] ?? '')) ?>
                                        </span>
                                        <span class="me-2"><?= htmlspecialchars($comment['username'] ?? '') ?></span>
                                        
                                        <!-- Social media links -->
                                        <?php if (!empty($comment['linkedin_url'])): ?>
                                            <a href="<?= htmlspecialchars($comment['linkedin_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($comment['instagram_url'])): ?>
                                            <a href="<?= htmlspecialchars($comment['instagram_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($comment['facebook_url'])): ?>
                                            <a href="<?= htmlspecialchars($comment['facebook_url']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
