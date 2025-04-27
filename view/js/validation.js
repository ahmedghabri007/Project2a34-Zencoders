// Fonctions de validation de base
function validateRequiredField(field, errorMessage) {
    const value = field.value.trim();
    const errorElement = document.getElementById(field.id + '-error');
    
    if (!value) {
        errorElement.textContent = errorMessage;
        field.classList.add('is-invalid');
        return false;
    }
    
    errorElement.textContent = '';
    field.classList.remove('is-invalid');
    return true;
}

function validateTextarea(textarea, minLength) {
    const value = textarea.value.trim();
    const errorElement = document.getElementById(textarea.id + '-error');
    
    if (value.length < minLength) {
        errorElement.textContent = `Le texte doit contenir au moins ${minLength} caractères`;
        textarea.classList.add('is-invalid');
        return false;
    }
    
    errorElement.textContent = '';
    textarea.classList.remove('is-invalid');
    return true;
}

// Validation du formulaire de réponse
function validateReponseForm() {
    let isValid = true;
    
    // Validation du contenu de la réponse
    const content = document.getElementById('contenu');
    if (!validateRequiredField(content, 'Le contenu de la réponse est requis')) {
        isValid = false;
    } else if (!validateTextarea(content, 10)) {
        isValid = false;
    }

    return isValid;
}

// Ajout des écouteurs d'événements pour le formulaire de réponse
document.addEventListener('DOMContentLoaded', function() {
    const reponseForm = document.getElementById('reponseForm');
    if (reponseForm) {
        // Validation lors de la soumission du formulaire
        reponseForm.addEventListener('submit', function(event) {
            if (!validateReponseForm()) {
                event.preventDefault();
            }
        });

        // Validation en temps réel pour le champ de contenu
        const contentField = document.getElementById('contenu');
        if (contentField) {
            contentField.addEventListener('input', function() {
                validateRequiredField(this, 'Le contenu de la réponse est requis');
                validateTextarea(this, 10);
            });
        }
    }
}); 