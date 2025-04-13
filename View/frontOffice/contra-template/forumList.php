<?php
include('../../controller/ForumController.php');
$ctrl = new ForumController();
$forums = $ctrl->listForums();
?>

<a href="addForum.php">Add Forum</a>
<a href="edit_forum.php?id=<?= $forum['id'] ?>">Modifier</a>

<table border="1">
<tr><th>ID</th><th>Sujet</th><th>Contenu</th><th>Date</th><th>Action</th></tr>
<?php foreach ($forums as $forum): ?>
<tr>
    <td><?= $forum['ID_FORUM'] ?></td>
    <td><?= htmlspecialchars($forum['Sujet']) ?></td>
    <td><?= htmlspecialchars($forum['Contenu']) ?></td>
    <td><?= $forum['Date_publication'] ?></td>
    <td><a href="../../controller/deleteForum.php?id=<?= $forum['ID_FORUM'] ?>">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
