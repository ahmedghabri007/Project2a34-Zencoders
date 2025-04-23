document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('documentForm');
    const docTypeSelect = form.querySelector('[name="document_type"]');
    const docNumberInput = form.querySelector('[name="document_number"]');
    
    // Création des conteneurs d'erreur
    const errorMessages = {
        document_type: createErrorContainer(docTypeSelect.parentNode),
        document_number: createErrorContainer(docNumberInput.parentNode)
    };

    // Configuration de la validation
    const validationRules = {
        document_type: {
            validate: value => value !== '',
            message: 'Veuillez sélectionner un type de document'
        },
        document_number: {
            validate: value => /^[A-Z0-9]{8,12}$/i.test(value),
            message: 'Le numéro doit contenir 8 à 12 caractères alphanumériques'
        }
    };

    // Validation des champs lorsque l'utilisateur interagit avec les inputs
    docTypeSelect.addEventListener('change', () => validateField('document_type'));
    docNumberInput.addEventListener('input', debounce(() => validateField('document_number'), 300));
    
    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();  // Empêcher la soumission par défaut du formulaire

        const isFormValid = Object.keys(validationRules).every(field => validateField(field));
        
        if (isFormValid) {
            handleFormSubmission();
            this.submit();  // Si tout est valide, soumettre le formulaire
        }
    });

    // Fonction de validation des champs
    function validateField(field) {
        const value = form.elements[field].value.trim();
        const { validate, message } = validationRules[field];
        const isValid = field === 'document_number' ? 
            value !== '' && validate(value) : 
            validate(value);

        if(!isValid) {
            showError(field, message);
            return false;
        }
        clearError(field);
        return true;
    }

    // Afficher un message d'erreur
    function showError(field, message) {
        errorMessages[field].textContent = message;
        form.elements[field].classList.add('invalid');
    }

    // Effacer un message d'erreur
    function clearError(field) {
        errorMessages[field].textContent = '';
        form.elements[field].classList.remove('invalid');
    }

    // Effacer tous les messages d'erreur
    function clearAllErrors() {
        Object.keys(errorMessages).forEach(field => {
            errorMessages[field].textContent = '';
            form.elements[field].classList.remove('invalid');
        });
    }

    // Créer un conteneur d'erreur
    function createErrorContainer(parent) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        parent.appendChild(errorDiv);
        return errorDiv;
    }

    // Gestion de la soumission du formulaire
    function handleFormSubmission() {
        const submission = {
            type: docTypeSelect.value,
            number: docNumberInput.value.toUpperCase(),
            timestamp: new Date().toISOString()
        };

        // Feedback utilisateur
        showSuccessMessage('Document soumis avec succès !');
    }

    // Afficher un message de succès
    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.textContent = message;
        form.parentNode.insertBefore(successDiv, form.nextSibling);
        
        setTimeout(() => successDiv.remove(), 3000);
    }

    // Débouncing pour éviter les appels excessifs à la validation
    function debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
});
    