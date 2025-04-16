<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = "USERS";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function register($name, $gender, $birthdate, $email, $password) {
        $sql = "INSERT INTO USERS (Name, Gender, BirthDate, Email, Password, RegDate, RoleID) 
                VALUES (:name, :gender, :birthdate, :email, :password, NOW(), 2)";
        $stmt = $this->conn->prepare($sql);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        return $stmt->execute([
            ':name' => $name,
            ':gender' => $gender,
            ':birthdate' => $birthdate,
            ':email' => $email,
            ':password' => $passwordHash
        ]);
    }
    

    public function login($email, $password) {
        $sql = "SELECT * FROM USERS WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }
        return false;
    }
}
