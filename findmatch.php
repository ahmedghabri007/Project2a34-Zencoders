<?php
include '../../config/config.php';

// Initialize search variables
$profession = isset($_GET['profession']) ? trim($_GET['profession']) : '';
$interests = isset($_GET['interests']) ? trim($_GET['interests']) : '';
$age_min = isset($_GET['age_min']) ? intval($_GET['age_min']) : 18;
$age_max = isset($_GET['age_max']) ? intval($_GET['age_max']) : 100;
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$biography = isset($_GET['biography']) ? trim($_GET['biography']) : '';

// Base query
$sql = "SELECT * FROM profile WHERE age BETWEEN :age_min AND :age_max";
$params = [
    ':age_min' => $age_min,
    ':age_max' => $age_max
];

if ($profession) {
    $sql .= " AND profession LIKE :profession";
    $params[':profession'] = "%" . $profession . "%";
}
if ($interests) {
    $sql .= " AND interests LIKE :interests";
    $params[':interests'] = "%" . $interests . "%";
}
if ($gender) {
    $sql .= " AND gender = :gender";
    $params[':gender'] = $gender;
}
if ($biography) {
    $sql .= " AND biography LIKE :biography";
    $params[':biography'] = "%" . $biography . "%";
}

try {
    $pdo = Config::getConnexion();
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
        .matches-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .match-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
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
    </style>
</head>
<body>
<div class="matches-container">
    <h1>Profils Correspondants</h1>

    <?php if (count($matches) > 0): ?>
        <?php foreach ($matches as $match): ?>
            <div class="match-card" data-profile='<?= json_encode($match, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                <h2><?= htmlspecialchars($match['fullname']) ?> (<?= $match['age'] ?> ans)</h2>
                <p><strong>Profession:</strong> <?= htmlspecialchars($match['profession']) ?></p>
                <p><strong>Intérêts:</strong> <?= htmlspecialchars($match['interests']) ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($match['gender']) ?></p>
                <p><strong>Localisation:</strong> <?= htmlspecialchars($match['location']) ?></p>
                <p><strong>Biographie:</strong> <?= htmlspecialchars($match['biography']) ?></p>
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
</body>
</html>
