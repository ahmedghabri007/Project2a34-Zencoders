<?php
require_once __DIR__ . '/../config.php';

class ModelClient
{
    private $id;
    private $fullname;
    private $email;
    private $phone;
    private $age;
    private $gender;
    private $life_status;
    private $role;
    private $password;
    private $created_at;
    private $admin;

    protected static $table = 'accounts';

    // Constructeur avec valeur par défaut pour admin
    public function __construct(
        $fullname = null,
        $email = null,
        $phone = null,
        $age = null,
        $gender = null,
        $life_status = null,
        $role = null,
        $password = null,
        $admin = '0' // Valeur par défaut pour 'admin'
    )
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phone = $phone;
        $this->age = $age;
        $this->gender = $gender;
        $this->life_status = $life_status;
        $this->role = $role;
        if ($password !== null) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->admin = $admin; // Attribuer la valeur par défaut '0' à admin
    }

    // Getters
    public function setId($id)
{
    $this->id = $id;
}
    public function getId() { return $this->id; }
    public function getFullname() { return $this->fullname; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getAge() { return $this->age; }
    public function getGender() { return $this->gender; }
    public function getLifeStatus() { return $this->life_status; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }
    public function getAdmin() { return $this->admin; } // Getter pour admin

    // Setters4
    
    public function setFullname($fullname) { $this->fullname = $fullname; }
    public function setEmail($email) { $this->email = $email; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setAge($age) { $this->age = $age; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setLifeStatus($life_status) { $this->life_status = $life_status; }
    public function setRole($role) { $this->role = $role; }
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Save (Insert) avec ajout de admin dans la requête SQL
    public function save()
    {
        $db = Config::getConnexion();
        $query = "INSERT INTO " . self::$table . " 
                 (fullname, email, phone, age, gender, life_status, role, password, admin, created_at) 
                 VALUES (:fullname, :email, :phone, :age, :gender, :life_status, :role, :password, :admin, NOW())";

        try {
            $stmt = $db->prepare($query);
            $success = $stmt->execute([
                'fullname' => $this->fullname,
                'email' => $this->email,
                'phone' => $this->phone,
                'age' => $this->age,
                'gender' => $this->gender,
                'life_status' => $this->life_status,
                'role' => $this->role,
                'password' => $this->password,
                'admin' => $this->admin // Ajout de admin à l'insertion
            ]);

            if ($success) {
                $this->id = $db->lastInsertId();
                return $this->id;
            } else {
                echo "<div style='color: red;'>❌ Insertion échouée !</div>";
                return false;
            }
        } catch (PDOException $e) {
            echo "<div style='color: red;'>❌ Erreur PDO : " . $e->getMessage() . "</div>";
            return false;
        }
    }

    // Read (by email)
    public static function getUserByEmail($email)
    {
        $db = Config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table . ' WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Read (by ID)
    public static function getById($id)
{
    $db = Config::getConnexion();
    $stmt = $db->prepare('SELECT * FROM ' . self::$table . ' WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $client = new ModelClient(
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['age'],
            $data['gender'],
            $data['life_status'],
            $data['role'],
            null, // password pas nécessaire ici
            $data['admin']
        );
        $client->setId($data['id']); // important !
        return $client;
    }
    return null;
}


    // Read All
    public static function getAll()
    {
        $db = Config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function update()
    {
        $db = Config::getConnexion();
        $query = "UPDATE " . self::$table . " SET 
                 fullname = :fullname,
                 email = :email,
                 phone = :phone,
                 age = :age,
                 gender = :gender,
                 life_status = :life_status,
                 role = :role,
                 admin = :admin
                 WHERE id = :id";

        try {
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'fullname' => $this->fullname,
                'email' => $this->email,
                'phone' => $this->phone,
                'age' => $this->age,
                'gender' => $this->gender,
                'life_status' => $this->life_status,
                'role' => $this->role,
                'admin' => $this->admin, // Mise à jour de admin
                'id' => $this->id
            ]);
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    // Delete
    public static function delete($id)
    {
        $db = Config::getConnexion();
        try {
            $stmt = $db->prepare('DELETE FROM ' . self::$table . ' WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            return false;
        }
    }

    // Verify password
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}
