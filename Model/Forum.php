<?php
require_once '../config.php'; // Make sure your config connection is loaded

class Forum
{
    private $sujet;
    private $contenu;
    private $date_publication;

    public function __construct($sujet, $contenu, $date_publication)
    {
        $this->sujet = $sujet;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
    }

    // ---------- GETTERS ----------
    public function getSujet() {
        return $this->sujet;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getDatePublication() {
        return $this->date_publication;
    }

    // ---------- STATIC CRUD METHODS ----------

    public static function addForum($forum)
    {
        $db = config::getConnexion();
        $sql = "INSERT INTO forum (sujet, contenu, date_publication) 
                VALUES (:sujet, :contenu, :date_publication)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'sujet' => $forum['Sujet'],
                'contenu' => $forum['Contenu'],
                'date_publication' => $forum['Date_publication']
            ]);
        } catch (Exception $e) {
            die('Erreur ajout forum: ' . $e->getMessage());
        }
    }

    public static function deleteForum($id)
    {
        $db = config::getConnexion();
        $sql = "DELETE FROM forum WHERE id = ?";
        try {
            $query = $db->prepare($sql);
            $query->execute([$id]);
        } catch (Exception $e) {
            die('Erreur suppression forum: ' . $e->getMessage());
        }
    }

    public static function getForumById($id)
    {
        $db = config::getConnexion();
        $query = $db->prepare("SELECT * FROM forum WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch();
    }

    public static function updateForum($id, $sujet, $contenu, $date_publication)
    {
        $db = config::getConnexion();
        $query = $db->prepare("UPDATE forum SET sujet = ?, contenu = ?, date_publication = ? WHERE id = ?");
        $query->execute([$sujet, $contenu, $date_publication, $id]);
    }

    public static function getAllForums()
    {
        $db = config::getConnexion();
        $query = $db->query("SELECT * FROM forum ORDER BY date_publication_*
