<?php
require_once __DIR__ . '/../models/User.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $gender = $_POST['gender'];
            $birthdate = $_POST['birthdate'];
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            if ($this->userModel->register($name, $gender, $birthdate, $email, $password)) {
                header("Location: login.php");
            } else {
                echo "Registration failed.";
            }
        }
    }
    

    public function login() {
        $error = '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
    
            if (empty($email) || empty($password)) {
                $error = "Please fill in both email and password.";
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user'] = $user;
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
    
        return $error; // return error for view
    }
    
    
    public function logout() {
        session_destroy();
        header("Location: login.php");
    }
}
