<?php
require_once 'Config.php';

class ModelClient
{
    private $id;
    private $full_name;
    private $email;
    private $phone_number;
    private $age;
    private $gender;
    private $life_status;
    private $role;
    private $password;
    private $created_at;

    protected static $table = 'users';

    public function __construct(
        $full_name = null,
        $email = null,
        $phone_number = null,
        $age = null,
        $gender = null,
        $life_status = null,
        $role = null,
        $password = null
    ) {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->age = $age;
        $this->gender = $gender;
        $this->life_status = $life_status;
        $this->role = $role;
        
        if ($password !== null) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getFullName() { return $this->full_name; }
    public function getEmail() { return $this->email; }
    public function getPhoneNumber() { return $this->phone_number; }
    public function getAge() { return $this->age; }
    public function getGender() { return $this->gender; }
    public function getLifeStatus() { return $this->life_status; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setFullName($full_name) { $this->full_name = $full_name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPhoneNumber($phone_number) { $this->phone_number = $phone_number; }
    public function setAge($age) { $this->age = $age; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setLifeStatus($life_status) { $this->life_status = $life_status; }
    public function setRole($role) { $this->role = $role; }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }

    // Database methods
    public function save() {
        $db = Config::getConnexion();
        $query = "INSERT INTO " . self::$table . " 
                 (full_name, email, phone_number, age, gender, life_status, role, password, created_at) 
                 VALUES (:full_name, :email, :phone_number, :age, :gender, :life_status, :role, :password, NOW())";
        
        try {
            $stmt = $db->prepare($query);
            $success = $stmt->execute([
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'age' => $this->age,
                'gender' => $this->gender,
                'life_status' => $this->life_status,
                'role' => $this->role,
                'password' => $this->password
            ]);

            if ($success) {
                $this->id = $db->lastInsertId();
                return $this->id;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Save error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUserByEmail($email) {
        $db = Config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table . ' WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table . ' WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $db = Config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update() {
        $db = Config::getConnexion();
        $query = "UPDATE " . self::$table . " SET 
                 full_name = :full_name,
                 email = :email,
                 phone_number = :phone_number,
                 age = :age,
                 gender = :gender,
                 life_status = :life_status,
                 role = :role
                 WHERE id = :id";
        
        try {
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'age' => $this->age,
                'gender' => $this->gender,
                'life_status' => $this->life_status,
                'role' => $this->role,
                'id' => $this->id
            ]);
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        $db = Config::getConnexion();
        try {
            $stmt = $db->prepare('DELETE FROM ' . self::$table . ' WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            return false;
        }
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
?>