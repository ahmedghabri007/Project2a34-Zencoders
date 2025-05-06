<?php
include '../../config/config.php';
require __DIR__ . '/vendor/autoload.php'; // Twilio SDK

use Twilio\Rest\Client;

// Twilio configuration (replace with your actual credentials)
$twilioSid = 'AC6451aa375229df821acd8cf4ea0b9f37';
$twilioToken = '83f0b7af792c3ecef10f3a28401a6f65';
$twilioNumber = '+15076657534';

$pdo = Config::getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['matched_id'])) {
        $matched_id = intval($_POST['matched_id']);

        $stmt = $pdo->prepare("SELECT fullname, phone FROM profile WHERE idprofile = :id");
        $stmt->execute([':id' => $matched_id]);
        $profile = $stmt->fetch();

        if ($profile && !empty($profile['phone'])) {
            $phone = $profile['phone'];
            $name = $profile['fullname'];

            try {
                $twilio = new Client($twilioSid, $twilioToken);
                $twilio->messages->create(
                    $phone,
                    [
                        'from' => $twilioNumber,
                        'body' => "Bonjour $name ! Quelqu’un s’est intéressé à votre profil 💌 sur notre site."
                    ]
                );
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Numéro invalide']);
        }
        exit;
    } elseif (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Here you would normally insert the match data to a database
        echo json_encode(['status' => 'match_created']);
        exit;
    }
}

// Fetch all profiles
$stmtProfiles = $pdo->query("SELECT idprofile, fullname FROM profile");
$profiles = $stmtProfiles->fetchAll();

// Search filter logic
$profession = $_GET['profession'] ?? '';
$interests = $_GET['interests'] ?? '';
$age_min = $_GET['age_min'] ?? 18;
$age_max = $_GET['age_max'] ?? 100;
$gender = $_GET['gender'] ?? '';
$biography = $_GET['biography'] ?? '';

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
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$matches = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Match</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .matches-container { max-width: 800px; margin: 30px auto; padding: 20px; }
        .form-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-container h1 { text-align: center; margin-bottom: 20px; }
        .form-container label { display: block; margin-top: 10px; }
        .form-container select, .form-container input[type="text"], .form-container textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;
        }
        .form-container button {
            margin-top: 15px; padding: 10px 20px; background-color: #007bff;
            color: white; border: none; border-radius: 6px; cursor: pointer;
        }
        .form-container button:hover { background-color: #007bff; }
        .match-card {
            border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;
            border-radius: 10px; background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .match-card img {
            max-width: 150px; max-height: 150px;
            border-radius: 8px; margin-bottom: 10px;
        }
        .match-btn {
            background-color: #007bff; color: white; padding: 10px 20px;
            border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;
        }
        .match-btn:hover { background-color: #007bff; }
        .error-message {
            color: red; font-size: 14px; margin-top: 5px; display: none;
        }
    </style>
    <script>
        function matchProfile(id) {
            fetch('findmatch.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'matched_id=' + encodeURIComponent(id)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('📩 Message envoyé avec succès !');
                } else {
                    alert('❌ Erreur : ' + data.message);
                }
            })
            .catch(err => {
                alert('❌ Erreur réseau');
                console.error(err);
            });
        }

        function matchProfileAdvanced(button) {
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

            fetch("findmatch.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(matchData)
            })
            .then(() => {
                alert("✔ Match enregistré avec succès !\n📩 Un SMS sera envoyé.");
            })
            .catch(err => {
                alert("❌ Erreur réseau !");
                console.error(err);
            });
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Add a Match</h2>
        <form id="add-match-form">
            <label>Matched Profile :</label>
            <select name="idprofile">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($profiles as $profile): ?>
                    <option value="<?= $profile['idprofile'] ?>"><?= htmlspecialchars($profile['fullname']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Searcher Name :</label>
            <input type="text" name="searcher_name">
            <label>Description :</label>
            <textarea name="description"></textarea>
            <button type="submit">Add match</button>
        </form>
    </div>

    <div class="container">
        <h1>Profiles</h1>
        <?php foreach ($matches as $profile):
            $pic = $profile['profile_picture'] ?? '';
            $picPath = file_exists($_SERVER['DOCUMENT_ROOT'] . "/Elev8Talent/" . $pic) ? "/Elev8Talent/" . $pic : "/Elev8Talent/uploads/default.png";
        ?>
            <div class="match-card" data-profile='<?= json_encode($profile, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                <img src="<?= $picPath ?>" alt="Photo de profil">
                <h2><?= htmlspecialchars($profile['fullname']) ?> (<?= $profile['age'] ?> ans)</h2>
                <p><strong>Gender:</strong> <?= htmlspecialchars($profile['gender']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($profile['location']) ?></p>
                <p><strong>Profession:</strong> <?= htmlspecialchars($profile['profession']) ?></p>
                <p><strong>Biography:</strong> <?= htmlspecialchars($profile['biography']) ?></p>
                <p><strong>Interests:</strong> <?= htmlspecialchars($profile['interests']) ?></p>
                <p><strong>Phone Number:</strong> <?= htmlspecialchars($profile['phone']) ?></p>
                <button class="match-btn" onclick="matchProfile(<?= $profile['idprofile'] ?>)">Send an SMS</button>
                <button class="match-btn" onclick="matchProfileAdvanced(this)">Create a match</button>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
