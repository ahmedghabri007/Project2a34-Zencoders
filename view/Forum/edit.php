<?php require_once __DIR__ . '/header.php'; ?>

<div class="container mt-4">
    <h1>Edit Forum</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="/project-2a34/index.php?action=edit&id=<?= $forum['id_forum'] ?>" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="sujet" class="form-label">Subject</label>
            <input type="text" class="form-control" id="sujet" name="sujet" 
                value="<?= htmlspecialchars($forum['sujet']) ?>" 

                title="Subject must be between 5 and 255 characters and can only contain letters, numbers, spaces, and basic punctuation">
            <div class="invalid-feedback" id="sujetError"></div>
        </div>
        <div class="mb-3">
            <label for="contenu" class="form-label">Content</label>
            <textarea class="form-control" id="contenu" name="contenu" 
                rows="5" 
><?= htmlspecialchars($forum['contenu']) ?></textarea>
            <div class="invalid-feedback" id="contenuError"></div>
        </div>
        <button type="submit" class="btn btn-primary">Update Forum</button>
        <a href="/project-2a34/index.php" class="btn btn-secondary">Cancel</a>

        <script>
        function validateForm() {
            var isValid = true;
            var sujet = document.getElementById('sujet');
            var contenu = document.getElementById('contenu');
            var sujetError = document.getElementById('sujetError');
            var contenuError = document.getElementById('contenuError');

            // Reset previous errors
            sujet.className = sujet.className.replace(" is-invalid", "");
            contenu.className = contenu.className.replace(" is-invalid", "");

            // Validate subject
            if (sujet.value.length < 5) {
                sujet.className += " is-invalid";
                sujetError.innerHTML = 'Subject must be at least 5 characters long';
                isValid = false;
            } else if (sujet.value.length > 255) {
                sujet.className += " is-invalid";
                sujetError.innerHTML = 'Subject must not exceed 255 characters';
                isValid = false;
            } else if (!/^[A-Za-z0-9\s\-_.,!?()]+$/.test(sujet.value)) {
                sujet.className += " is-invalid";
                sujetError.innerHTML = 'Subject contains invalid characters';
                isValid = false;
            }

            // Validate content
            if (contenu.value.length < 10) {
                contenu.className += " is-invalid";
                contenuError.innerHTML = 'Content must be at least 10 characters long';
                isValid = false;
            } else if (contenu.value.length > 1000) {
                contenu.className += " is-invalid";
                contenuError.innerHTML = 'Content must not exceed 1000 characters';
                isValid = false;
            }

            return isValid;
        }
        </script>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
