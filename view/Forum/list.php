<?php require_once __DIR__ . '/header.php'; ?>

<!-- Page Header Start -->
<div class="container-fluid bg-primary py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-3 text-white animated slideInDown">Discussion Forum</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white" href="/project-2a34/index.php">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Forum</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Forum List Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title position-relative pb-3 mb-5">
                <span class="position-relative text-primary ps-4">Latest Discussions</span>
            </h2>
            <a href="/project-2a34/index.php?action=createThread" class="btn btn-primary py-2 px-4">
                <i class="bi bi-plus-circle me-2"></i>Create New Thread
            </a>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="/project-2a34/index.php" method="GET" class="row g-3">
                    <input type="hidden" name="action" value="list">
                    
                    <!-- Search Input -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search by title or content" name="search" value="<?= htmlspecialchars($filterParams['search'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="" <?= ($filterParams['status'] ?? '') === '' ? 'selected' : '' ?>>All Status</option>
                            <option value="active" <?= ($filterParams['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($filterParams['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <!-- Sort By -->
                    <div class="col-md-2">
                        <select class="form-select" name="sort">
                            <option value="date_publication" <?= ($filterParams['sort'] ?? '') === 'date_publication' ? 'selected' : '' ?>>Date</option>
                            <option value="comment_count" <?= ($filterParams['sort'] ?? '') === 'comment_count' ? 'selected' : '' ?>>Most Commented</option>
                            <option value="upvote_count" <?= ($filterParams['sort'] ?? '') === 'upvote_count' ? 'selected' : '' ?>>Most Upvoted</option>
                        </select>
                    </div>
                    
                    <!-- Sort Order -->
                    <div class="col-md-1">
                        <select class="form-select" name="order">
                            <option value="DESC" <?= ($filterParams['order'] ?? '') === 'DESC' ? 'selected' : '' ?>>Desc</option>
                            <option value="ASC" <?= ($filterParams['order'] ?? '') === 'ASC' ? 'selected' : '' ?>>Asc</option>
                        </select>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($forums)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No threads found. Be the first to create one!
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($forums as $forum): ?>
                    <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">
                        <div class="blog-item bg-light rounded overflow-hidden">
                            <div class="p-4">
                                <div class="d-flex mb-3">
                                    <small class="me-3"><i class="far fa-calendar-alt text-primary me-2"></i><?= date('F j, Y', strtotime($forum['date_publication'])) ?></small>
                                    <small class="me-3"><i class="far fa-comments text-primary me-2"></i><?= (int)$forum['comment_count'] ?> Comments</small>
                                    <small class="me-3"><i class="far fa-thumbs-up text-primary me-2"></i><?= (int)$forum['upvote_count'] ?> Upvotes</small>
                                </div>
                                <h4 class="mb-3">
                                    <a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="text-dark">
                                        <?= htmlspecialchars($forum['sujet']) ?>
                                    </a>
                                </h4>
                                <p class="text-muted mb-4"><?= htmlspecialchars(substr($forum['contenu'], 0, 150)) ?>...</p>
                                <div class="d-flex justify-content-between">
                                    <a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-primary py-2 px-4">View Discussion</a>
                                    <div>
                                        <a href="/project-2a34/index.php?action=edit&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-outline-primary py-2 px-4 me-2">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="/project-2a34/index.php?action=delete&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-outline-danger py-2 px-4" onclick="return confirm('Are you sure you want to delete this thread?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Forum List End -->

<?php require_once __DIR__ . '/footer.php'; ?>
