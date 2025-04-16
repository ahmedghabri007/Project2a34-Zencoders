<?php require_once __DIR__ . '/../../view/FrontOffice/header.php'; ?>

<!-- Page Header Start -->
<div class="container-fluid bg-primary py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-3 text-white animated slideInDown">Create New Thread</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white" href="/project-2a34/index.php">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Create Thread</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Create Thread Form Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8 offset-lg-2 wow slideInUp" data-wow-delay="0.3s">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="bg-light rounded p-5">
                    <form action="/project-2a34/index.php?action=createThread" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-white px-4" 
                                       placeholder="Thread Title" id="title" name="title" 
                                       value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" 
                                       style="height: 55px;" required minlength="5" maxlength="255">
                                <div class="form-text">Title must be between 5 and 255 characters.</div>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0 bg-white px-4 py-3" 
                                          rows="8" placeholder="Thread Content" 
                                          id="content" name="content" 
                                          required minlength="10" maxlength="1000"><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
                                <div class="form-text">Content must be between 10 and 1000 characters.</div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Create Thread</button>
                            </div>
                            <div class="col-12">
                                <a href="/project-2a34/index.php" class="btn btn-secondary w-100 py-3">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create Thread Form End -->

<?php require_once __DIR__ . '/../../view/FrontOffice/footer.php'; ?>
