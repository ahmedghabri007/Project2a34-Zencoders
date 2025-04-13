<?php
include('../../controller/ForumController.php');

if (isset($_POST['sujet'], $_POST['contenu'], $_POST['date_publication'])) {
    $forum = new Forum($_POST['sujet'], $_POST['contenu'], $_POST['date_publication']);
    $ctrl = new ForumController();
    $ctrl->addForum($forum);
    header("Location: forumList.php");
}
?>
