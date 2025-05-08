<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';
$error = '';
$success = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $documentType = $_POST['document_type'] ?? '';
    $documentNumber = strtoupper(trim($_POST['document_number'] ?? ''));
    // ✅ Only validate if the user filled something
    if ($documentType === '' && $documentNumber === '') {
        // Skip processing if both are empty (assume page is just loading)
    } else {
        if (empty($documentType) || empty($documentNumber)) {
            $error = "All fields are required.";
        } elseif (!preg_match('/^[A-Z0-9]{8,12}$/', $documentNumber)) {
            $error = "Invalid document number format.";
        }

        if (empty($error)) {
            try {
                $pdo = Config::getConnexion();

                $stmt = $pdo->prepare("INSERT INTO badge_upgrade (new_document_type, new_document_number, request_status) VALUES (:type, :number, 'PENDING')");
                $stmt->execute([
                    ':type' => $documentType,
                    ':number' => $documentNumber
                ]);

                if ($stmt->rowCount() > 0) {
                    $id = $pdo->lastInsertId(); 
                   header("Location: ../Front_Office/formver.html?success=1&id=$id");
                    exit();
                } else {
                    $error = "Failed to insert data.";
                }

            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Badge Upgrade</title>
  <link rel="stylesheet" href="../Front_Office/formth.css">
  <link rel="stylesheet" href="../Front_Office/badge-upgrade.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>
<body>
    <script>document.addEventListener('DOMContentLoaded', () => {
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
    </script>
  <div class="form-container">

    <!-- Current Badge Info -->
    <section>
    <h2><i class="fas fa-file-upload"></i> Request a Badge Upgrade</h2>
    <form
      id="documentForm"
      class="document-form"
      method="POST"
      action="add_badge_upgrade.php"
      enctype="multipart/form-data"
    >
      <input type="hidden" name="trustbadge_id" value="<?= htmlspecialchars($currentBadge['id']) ?>">

      <div class="form-group">
        <label for="document_type">
          <i class="fas fa-file-alt"></i> Document Type:
        </label>
        <select id="document_type" name="document_type" >
          <option value="">Select…</option>
          <option value="Passport">Passport</option>
          <option value="National ID">National ID</option>
          <option value="Driver License">Driver License</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="document_number">
          <i class="fas fa-hashtag"></i> Document Number:
        </label>
        <input
          type="text"
          id="document_number"
          name="document_number"
          pattern="[A-Z0-9]{8,12}"
          placeholder="Ex: AB12345678"
        />
      </div>

      <div class="form-group">
        <label for="document_file">
          <i class="fas fa-upload"></i> Upload Document:
        </label>
        <input
          type="file"
          id="document_file"
          name="document_file"
          accept=".jpg,.jpeg,.png,.pdf"
        />
      </div>

      <button type="submit" class="submit-btn">
        <i class="fas fa-paper-plane"></i> Submit
      </button>
    </form>
  </section>

  </div>
</body>
</html>
