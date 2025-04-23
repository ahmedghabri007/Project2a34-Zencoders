<?php require_once __DIR__ . '/../../view/FrontOffice/header.php'; ?>

<div class="container mt-4">
    <h1>Create New Forum</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="/project-2a34/index.php?action=add" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="sujet" class="form-label">Subject</label>
            <input type="text" class="form-control" id="sujet" name="sujet" 

                title="Subject must be between 5 and 255 characters and can only contain letters, numbers, spaces, and basic punctuation">
            <div class="invalid-feedback" id="sujetError"></div>
        </div>
        <div class="mb-3">
            <label for="contenu" class="form-label">Content</label>
            <textarea class="form-control" id="contenu" name="contenu" 
                rows="5" 
></textarea>
            <div class="invalid-feedback" id="contenuError"></div>
        </div>
        <button type="submit" class="btn btn-primary">Create Forum</button>
        <a href="/project-2a34/index.php" class="btn btn-secondary">Cancel</a>

        <script>
        function validateForm() {
            let isValid = true;
            const sujet = document.getElementById('sujet');
            const contenu = document.getElementById('contenu');
            const sujetError = document.getElementById('sujetError');
            const contenuError = document.getElementById('contenuError');

            // Reset previous errors
            sujet.classList.remove('is-invalid');
            contenu.classList.remove('is-invalid');

            // Validate subject
            if (sujet.value.length < 5) {
                sujet.classList.add('is-invalid');
                sujetError.textContent = 'Subject must be at least 5 characters long';
                isValid = false;
            } else if (sujet.value.length > 255) {
                sujet.classList.add('is-invalid');
                sujetError.textContent = 'Subject must not exceed 255 characters';
                isValid = false;
            } else if (!/^[A-Za-z0-9\s\-_.,!?()]+$/.test(sujet.value)) {
                sujet.classList.add('is-invalid');
                sujetError.textContent = 'Subject contains invalid characters';
                isValid = false;
            }

            // Validate content
            if (contenu.value.length < 10) {
                contenu.classList.add('is-invalid');
                contenuError.textContent = 'Content must be at least 10 characters long';
                isValid = false;
            } else if (contenu.value.length > 1000) {
                contenu.classList.add('is-invalid');
                contenuError.textContent = 'Content must not exceed 1000 characters';
                isValid = false;
            }

            return isValid;
        }
        </script>
    </form>
</div>

<?php require_once __DIR__ . '/../../view/FrontOffice/footer.php'; ?>
