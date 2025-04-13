<?php
require_once '../../Model/Forum.php';

if (isset($_GET['id'])) {
    $forum = Forum::getForumById($_GET['id']); // Assume this static method exists
}

if (!$forum) {
    echo "Forum introuvable.";
    exit;
}
?>

<h2>Modifier un Forum</h2>
<form action="../../Controller/ForumController.php?action=update" method="POST">
    <input type="hidden" name="id" value="<?= $forum['id'] ?>">
    <label>Sujet:</label>
    <input type="text" name="sujet" value="<?= htmlspecialchars($forum['sujet']) ?>"><br>
    
    <label>Contenu:</label>
    <textarea name="contenu"><?= htmlspecialchars($forum['contenu']) ?></textarea><br>
    
    <label>Date publication:</label>
    <input type="text" name="date_publication" value="<?= $forum['date_publication'] ?>"><br>
    
    <button type="submit">Mettre à jour</button>
</form>
