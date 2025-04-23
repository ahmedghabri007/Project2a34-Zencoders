<?php require_once __DIR__ . '/header.php'; ?>

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
                    <form action="/project-2a34/index.php?action=createThread" method="POST" onsubmit="return validateCreateForm();">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-white px-4" 
                                       placeholder="Thread Title" id="title" name="title" 
                                       value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" 
                                       style="height: 55px;">
                                <div class="form-text">Title must be between 5 and 255 characters.</div>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0 bg-white px-4 py-3" 
                                          rows="8" placeholder="Thread Content" 
                                          id="content" name="content" 
                                          ><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
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

<script>
function validateCreateForm() {
    var isValid = true;
    var title = document.getElementById('title');
    var content = document.getElementById('content');
    
    // Basic validation
    if (title.value.length < 5) {
        alert('Title must be at least 5 characters long');
        isValid = false;
    } else if (title.value.length > 255) {
        alert('Title must not exceed 255 characters');
        isValid = false;
    } else if (!/^[A-Za-z0-9\s\-_.,!?()]+$/.test(title.value)) {
        alert('Title contains invalid characters');
        isValid = false;
    }
    
    if (content.value.length < 10) {
        alert('Content must be at least 10 characters long');
        isValid = false;
    } else if (content.value.length > 1000) {
        alert('Content must not exceed 1000 characters');
        isValid = false;
    }
    
    return isValid;
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
