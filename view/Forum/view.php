<?php require_once __DIR__ . '/header.php'; ?>

<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/project-2a34/index.php">Forums</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($forum['sujet'] ?? 'View Forum') ?></li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title"><?= htmlspecialchars($forum['sujet'] ?? '') ?></h1>
            <p class="card-text"><?= nl2br(htmlspecialchars($forum['contenu'] ?? '')) ?></p>
            <?php if (isset($forum['date_publication']) && $forum['date_publication']): ?>
                <p class="text-muted">Posted on <?= date('F j, Y, g:i a', strtotime($forum['date_publication'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h2 class="h5 mb-0">Discussion</h2>
        </div>
        <?php
        // Set flag for comments.php
        define('FORUM_VIEW', true);
        // Include comments section
        include __DIR__ . '/comments.php';
        ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
