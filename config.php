<?php
class config
{   
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "event";
            
            try {
                // Attempt to create a PDO instance
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                 
            } catch (Exception $e) {
                // Log the error message to a file (optional)
                file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
                die('❌ Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// Call the getConnexion method to test the connection
config::getConnexion();
?>

