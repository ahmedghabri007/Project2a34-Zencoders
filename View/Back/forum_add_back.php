<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Forum</title>
</head>
<body>
    <h2>Ajouter une discussion au forum</h2>
    <form action="../../controller/ForumController.php?action=add" method="POST">
        <label for="sujet">Sujet:</label><br>
        <input type="text" id="sujet" name="sujet" required><br><br>

        <label for="contenu">Contenu:</label><br>
        <textarea id="contenu" name="contenu" required></textarea><br><br>

        <label for="date">Date de publication:</label><br>
        <input type="date" id="date" name="date" required><br><br>

        <input type="submit" value="Ajouter">
    </form>
</body>
</html>