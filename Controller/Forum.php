<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/Forum.php');

class ForumController
{
    public function addForum($forum)
    {
        $sql = "INSERT INTO forum (Sujet, Contenu, Date_publication) VALUES (:sujet, :contenu, :date_pub)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'sujet' => $forum->getSujet(),
                'contenu' => $forum->getContenu(),
                'date_pub' => $forum->getDate()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getAllForums()
    {
        $sql = "SELECT * FROM forum";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
