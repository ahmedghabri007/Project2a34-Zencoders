<?php
include_once(__DIR__ . '/../../../Controller/ForumController.php');

if (isset($_POST['sujet'], $_POST['contenu'], $_POST['date_publication'])) {
    $forum = new Forum($_POST['sujet'], $_POST['contenu'], $_POST['date_publication']);
    $controller = new ForumController();
    $controller->addForum($forum);

    header('Location: index.php');
    exit();
} else {
    echo "Erreur: Champs manquants.";
}
?>
