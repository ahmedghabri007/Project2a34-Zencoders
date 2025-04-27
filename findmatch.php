<?php
include '../../config/config.php';

// Fetch all profiles for the dropdown
try {
    $pdo = Config::getConnexion();
    $stmtProfiles = $pdo->query("SELECT idprofile, fullname FROM profile");
    $profiles = $stmtProfiles->fetchAll();
} catch (PDOException $e) {
    echo "Erreur chargement profils : " . $e->getMessage();
    $profiles = [];
}

// Search filter logic
$profession = isset($_GET['profession']) ? trim($_GET['profession']) : '';
$interests = isset($_GET['interests']) ? trim($_GET['interests']) : '';
$age_min = isset($_GET['age_min']) ? intval($_GET['age_min']) : 18;
$age_max = isset($_GET['age_max']) ? intval($_GET['age_max']) : 100;
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$biography = isset($_GET['biography']) ? trim($_GET['biography']) : '';

$sql = "SELECT * FROM profile WHERE age BETWEEN :age_min AND :age_max";
$params = [':age_min' => $age_min, ':age_max' => $age_max];

if ($profession) {
    $sql .= " AND profession LIKE :profession";
    $params[':profession'] = "%$profession%";
}
if ($interests) {
    $sql .= " AND interests LIKE :interests";
    $params[':interests'] = "%$interests%";
}
if ($gender) {
    $sql .= " AND gender = :gender";
    $params[':gender'] = $gender;
}
if ($biography) {
    $sql .= " AND biography LIKE :biography";
    $params[':biography'] = "%$biography%";
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $matches = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $matches = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats - Find Your Match</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .matches-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
        }

        .form-container select,
        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-container button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .match-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .match-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        .match-btn:hover {
            background-color: #218838;
        }

        .input-error {
        border-color: red;
        }

        .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    display: none;
}

    </style>
</head>
<body>

<!-- ... tout le code PHP au début reste inchangé ... -->

<div class="matches-container">

    <!-- Add Match Form -->
    <div class="form-container">
        <h1>Add a Match</h1>
        <form id="add-match-form">
            <label>Matched Profile:</label>
            <select name="idprofile">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($profiles as $profile): ?>
                    <option value="<?= $profile['idprofile'] ?>"><?= htmlspecialchars($profile['fullname']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="error-message" id="error-idprofile"></div>

            <label>Searcher Name:</label>
            <input type="text" name="searcher_name">
            <div class="error-message" id="error-searcher_name"></div>

            <label>Description:</label>
            <textarea name="description"></textarea>
            <div class="error-message" id="error-description"></div>

            <button type="submit">Add Match</button>
            <div id="success-message" style="color: green; margin-top: 10px; display: none;"></div>
        </form>
    </div>

    <!-- Profiles Section -->
    <h1>Profils Correspondants</h1>
    <?php if (count($matches) > 0): ?>
        <?php foreach ($matches as $match): ?>
            <div class="match-card" data-profile='<?= json_encode($match, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                <h2><?= htmlspecialchars($match['fullname']) ?> (<?= $match['age'] ?> ans)</h2>
                <p><strong>Profession:</strong> <?= htmlspecialchars($match['profession']) ?></p>
                <p><strong>Interests:</strong> <?= htmlspecialchars($match['interests']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($match['gender']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($match['location']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($match['phone']) ?></p>
                <p><strong>Biography:</strong> <?= htmlspecialchars($match['biography']) ?></p>
                <button class="match-btn" onclick="matchProfile(this)">Match</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun profil trouvé avec les critères donnés.</p>
    <?php endif; ?>
</div>

<script>
function matchProfile(button) {
    const card = button.closest('.match-card');
    const profile = JSON.parse(card.dataset.profile);

    const matchData = {
        recherche_genre: profile.gender,
        tranche_age_min: profile.age - 2,
        tranche_age_max: profile.age + 2,
        distance_max: 50,
        localisation_preferee: profile.location,
        personnalite_recherchee: "Ouvert",
        criteres_personnalises: "Ambitieux",
        interets_communs_souhaites: profile.interests,
        compatibilite_min_score: 70
    };

    fetch("create_match.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(matchData)
    })
    .then(() => {
        alert("✔ Match enregistré avec succès !\n📩 Un SMS sera envoyé sur votre numéro de téléphone pour la confirmation du match.");
    })
    .catch(err => {
        alert("❌ Erreur réseau !");
        console.error(err);
    });
}
</script>

<script>
document.getElementById('add-match-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const form = event.target;

    const idprofile = form.querySelector('[name="idprofile"]').value.trim();
    const searcher_name = form.querySelector('[name="searcher_name"]').value.trim();
    const description = form.querySelector('[name="description"]').value.trim();

    // Réinitialiser les erreurs
    document.getElementById("error-idprofile").style.display = "none";
    document.getElementById("error-searcher_name").style.display = "none";
    document.getElementById("error-description").style.display = "none";
    document.getElementById("success-message").style.display = "none";

    let hasError = false;

    if (!idprofile) {
        document.getElementById("error-idprofile").textContent = "❌ Veuillez sélectionner un profil.";
        document.getElementById("error-idprofile").style.display = "block";
        hasError = true;
    }

    if (!searcher_name) {
        document.getElementById("error-searcher_name").textContent = "❌ Le nom du chercheur est requis.";
        document.getElementById("error-searcher_name").style.display = "block";
        hasError = true;
    }

    if (!description) {
        document.getElementById("error-description").textContent = "❌ La description est requise.";
        document.getElementById("error-description").style.display = "block";
        hasError = true;
    }

    if (hasError) return;

    const formData = new FormData(form);

    fetch('addmatch.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur serveur');
        return response.text();
    })
    .then(data => {
        document.getElementById("success-message").textContent = "✅ Match ajouté avec succès !";
        document.getElementById("success-message").style.display = "block";
        form.reset();
    })
    .catch(error => {
        console.error("Erreur : ", error);
        alert("❌ Une erreur est survenue lors de l'ajout.");
    });
});
</script>

<style>
.error-message {
    color: red;
    font-size: 14px;
    margin-top: 5px;
    display: none;
}
</style>
