<?php
class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            $servername = "localhost";
            $dbname = "accounts";
            $username = "root";
            $password = "";

            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // ✅ Message de succès
                echo "<div style='color: green;'>✅ Connexion à la base de données réussie !</div>";
            } catch (PDOException $e) {
                die("<div style='color: red;'>❌ Erreur de connexion : " . $e->getMessage() . "</div>");
            }
        }
        return self::$pdo;
    }
}

// Appel de la méthode pour tester la connexion
config::getConnexion();
?>
