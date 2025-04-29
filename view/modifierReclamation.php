<?php
require_once __DIR__ . '/../controller/ReclamationController.php';
require_once __DIR__ . '/../model/ReclamationModel.php';

if (isset($_GET['id_reclamation'])) {
    $id_reclamation = $_GET['id_reclamation'];

    $controller = new ReclamationController();
    $reclamation = $controller->getReclamationById($id_reclamation);



    $reclamationModel = new ReclamationModel();
    $emails = $reclamationModel->getAllEmails();



    // Vérifie si la réclamation a été trouvée
    if (!$reclamation) {
        echo "Réclamation non trouvée.";
        exit;
    }
} else {
    echo "Aucun ID de réclamation spécifié.";
    exit;
}
?>

<h2>Modifier la réclamation</h2>

<form action="traitementModifierReclamation.php" method="POST">
    <input type="hidden" name="id_reclamation" value="<?= $reclamation['id_reclamation'] ?>">

    <label for="email">Email:</label>
    <select name="email" required>
        <?php foreach ($emails as $email): ?>
            <option value="<?= htmlspecialchars($email['email']) ?>" <?= ($reclamation['email'] ?? '') === $email['email'] ? 'selected' : '' ?>><?= htmlspecialchars($email['email']) ?></option>
        <?php endforeach; ?>
    </select><br>

  

    <label for="description">Description:</label>
    <textarea name="description"><?= htmlspecialchars($reclamation['Description'] ?? '') ?></textarea><br>
    <span id="description-error" style="color: red;"></span>

    <label>Type de réclamation :</label>
<select name="type_reclamation" required>
    <option value="Technique" <?= ($reclamation['type_reclamation'] ?? '') === 'Technique' ? 'selected' : '' ?>>Technique</option>
    <option value="Support client" <?= ($reclamation['type_reclamation'] ?? '') === 'Support client' ? 'selected' : '' ?>>Support client</option>
    <option value="Amélioration" <?= ($reclamation['type_reclamation'] ?? '') === 'Amélioration' ? 'selected' : '' ?>>Amélioration</option>
</select>
<br><br>


    <input type="submit" value="Mettre à jour" class="btn-ajouter">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Fonction de validation
        function validateForm() {
            let isValid = true;
            const description = $('textarea[name="description"]').val().trim();
            const typeReclamation = $('select[name="type_reclamation"]').val();
            const email = $('select[name="email"]').val();

            // Validation de la description
            if (description === '') {
                $('#description-error').text('La description est obligatoire');
                isValid = false;
            } else if (description.length < 10) {
                $('#description-error').text('La description doit contenir au moins 10 caractères');
                isValid = false;
            } else {
                $('#description-error').text('');
            }

            // Validation du type de réclamation
            if (typeReclamation === '') {
                alert('Veuillez sélectionner un type de réclamation');
                isValid = false;
            }

            // Validation de l'email
            if (email === '') {
                alert('Veuillez sélectionner un email');
                isValid = false;
            }

            return isValid;
        }

        $('form').on('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            if (!validateForm()) {
                return false;
            }

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'traitementModifierReclamation.php',
                data: formData,
                success: function(response) {
                    alert('Réclamation mise à jour avec succès !');
                    window.location.href = 'afficherReclamation.php';
                },
                error: function() {
                    alert('Une erreur est survenue lors de la mise à jour de la réclamation.');
                }
            });
        });

        // Validation en temps réel pour la description
        $('textarea[name="description"]').on('input', function() {
            const description = $(this).val().trim();
            if (description === '') {
                $('#description-error').text('La description est obligatoire');
            } else if (description.length < 10) {
                $('#description-error').text('La description doit contenir au moins 10 caractères');
            } else {
                $('#description-error').text('');
            }
        });
    });
</script>

<style>
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form h2 {
    text-align: center;
    color: #333;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

form select,
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

form input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

form input[type="submit"]:hover {
    background-color: #1976d2;
}

.btn-ajouter {
    margin-top: 20px;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    background-color: #2196f3;
    color: white;
    text-decoration: none;
    transition: background-color 0.3s;
}

.btn-ajouter:hover {
    background-color: #1976d2;
}
</style>
