<?php
include '../../config/config.php';

$pdo = Config::getConnexion();

$matches = [];
$profiles = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searcherName = trim($_POST['searcher_name']);
    $profileName = trim($_POST['fullname']);

    try {
        if (!empty($searcherName)) {
            // Search for matches by searcher_name
            $stmt = $pdo->prepare("SELECT * FROM matches WHERE searcher_name LIKE ?");
            $stmt->execute(["%$searcherName%"]);
            $matches = $stmt->fetchAll();
            if (empty($matches)) {
                $message = "No matches found for searcher name '$searcherName'.";
            }
        } elseif (!empty($profileName)) {
            // Search for profiles by fullname
            $stmt = $pdo->prepare("SELECT * FROM profile WHERE fullname LIKE ?");
            $stmt->execute(["%$profileName%"]);
            $profiles = $stmt->fetchAll();
            if (empty($profiles)) {
                $message = "No profiles found for full name '$profileName'.";
            }
        } else {
            $message = "Please enter a search term.";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Search Matches or Profiles</title>
    <link rel="stylesheet" href="match.css">
</head>
<body>
    <div class="form-container">
        <center><h1>Search for a Match or Profile</h1><center>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label>Search by Searcher Name (Match):</label>
            <input type="text" name="searcher_name" placeholder="Enter searcher name">

            <label>OR Search by Profile Full Name:</label>
            <input type="text" name="fullname" placeholder="Enter full name">

            <button type="submit">Search</button>
        </form>

        <div class="results">
            <?php if (!empty($message)): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if (!empty($matches)): ?>
                <h2>Match Results:</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Match</th>
                            <th>ID Profile</th>
                            <th>Searcher Name</th>
                            <th>Description</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matches as $match): ?>
                            <tr>
                                <td><?= htmlspecialchars($match['idmatch']) ?></td>
                                <td><?= htmlspecialchars($match['idprofile']) ?></td>
                                <td><?= htmlspecialchars($match['searcher_name']) ?></td>
                                <td><?= htmlspecialchars($match['description']) ?></td>
                                <td><?= htmlspecialchars($match['date_created']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (!empty($profiles)): ?>
                <h2>Profile Results:</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Profile</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Location</th>
                            <th>Profession</th>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    form {
        max-width: 500px;
        margin: 0 auto 30px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: bold;
    }

    select, input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    button {
        background-color: #3498db; /* Blue */
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2980b9; /* Darker Blue on hover */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #3498db; /* Blue */
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .no-results {
        text-align: center;
        color: #888;
        margin-top: 30px;
    }
</style>
