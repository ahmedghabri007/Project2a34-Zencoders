<?php require_once __DIR__ . '/../../view/FrontOffice/header.php'; ?>

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
                                </div>
                                <h4 class="mb-3">
                                    <a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="text-dark">
                                        <?= htmlspecialchars($forum['sujet']) ?>
                                    </a>
                                </h4>
                                <p class="text-muted mb-4"><?= htmlspecialchars(substr($forum['contenu'], 0, 150)) ?>...</p>
                                <div class="d-flex justify-content-between">
                                    <a href="/project-2a34/index.php?action=view&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-primary py-2 px-4">View Discussion</a>
                                    <a href="/project-2a34/index.php?action=delete&id=<?= htmlspecialchars($forum['id_forum']) ?>" class="btn btn-outline-danger py-2 px-4" onclick="return confirm('Are you sure you want to delete this thread?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
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

<?php require_once __DIR__ . '/../../view/FrontOffice/footer.php'; ?>
