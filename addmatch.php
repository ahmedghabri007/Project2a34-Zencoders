<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Match</title>
    <link rel="stylesheet" href="findyourmatch.css">
</head>
<body>
    <div class="form-container">
        <h1>Match Done</h1>
        <form method="POST" action="addmatch.php">
            <label>Matched Profile:</label>
            <select name="idprofile">
                <?php
                // Fetch all profiles from the profile table
                $pdo = Config::getConnexion();
                $stmt = $pdo->query("SELECT idprofile, fullname FROM profile");
                while ($row = $stmt->fetch()) {
                    echo "<option value='{$row['idprofile']}'>{$row['fullname']}</option>";
                }
                ?>
            </select>
            <label>Match Type:</label>
            <input type="text" name="match_type">
            <label>Description:</label>
            <textarea name="description"></textarea>
            <button type="submit">Add Match</button>
        </form>
    </div>
</body>
</html>
