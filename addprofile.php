<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/config.php';

$pdo = config::getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requiredFields = ['fullname', 'age', 'gender', 'location', 'profession', 'interests', 'biography'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo "⚠️ The field '$field' is required.";
            exit();
        }
    }

    $fullname = $_POST['fullname'];
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $interests = $_POST['interests'];
    $biography = $_POST['biography'];
    $profession = $_POST['profession'];

    if ($age < 18) {
        echo "❌ Age must be greater than 18.";
        exit();
    }

    try {
        $sql = "INSERT INTO profile (fullname, age, gender, location, profession, interests, biography)
                VALUES (:fullname, :age, :gender, :location, :profession, :interests, :biography)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':fullname' => $fullname,
            ':age' => $age,
            ':gender' => $gender,
            ':location' => $location,
            ':profession' => $profession,
            ':interests' => $interests,
            ':biography' => $biography
        ]);

        echo "✅ Profile added successfully.<br><br>";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
        exit();
    }
}

// At this point, whether GET or POST, we display the profile list:

try {
    $stmt = $pdo->prepare("SELECT * FROM profile");
    $stmt->execute();
    $profiles = $stmt->fetchAll();
} catch (PDOException $e) {
    die("❌ Error fetching profiles: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Back Office - Profile List</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Profile List</h2>

        <?php if (!empty($profiles)): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Location</th>
                        <th>Profession</th>
                        <th>Interests</th>
                        <th>Biography</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td><?= htmlspecialchars($profile['idprofile']) ?></td>
                            <td><?= htmlspecialchars($profile['fullname']) ?></td>
                            <td><?= htmlspecialchars($profile['age']) ?></td>
                            <td><?= htmlspecialchars($profile['gender']) ?></td>
                            <td><?= htmlspecialchars($profile['location']) ?></td>
                            <td><?= htmlspecialchars($profile['profession']) ?></td>
                            <td><?= htmlspecialchars($profile['interests']) ?></td>
                            <td><?= htmlspecialchars($profile['biography']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No profiles found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
