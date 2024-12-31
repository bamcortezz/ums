<?php

require_once __DIR__ . ('/../../database/database.php');
include_once __DIR__ . ('/../../config/settings-config.php');

class Auth
{
    private $conn;


    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function register($csrf_token, $username, $email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            echo "<script> alert ('Email Already Exist.'); window.location.href = '../../../'; </script>";
            exit;
        }

        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            echo "<script> alert ('Invalid CSRF Token.'); window.location.href = '../../../'; </script>";
            exit;
        }

        unset($_SESSION['csrf_token']);

        $hash_password = md5($password);
        $stmt = $this->conn->prepare('INSERT INTO user (username, email, password) VALUES (:username, :email, :password)');
        $exec = $stmt->execute(array(":username" => $username, ":email" => $email, ":password" => $hash_password));

        if ($exec) {
            echo "<script> alert ('Admin added successfully.'); window.location.href = '../../'; </script>";
            exit;
        } else {
            echo "<script> alert ('Error adding admin.'); window.location.href = '../../'; </script>";
            exit;
        }
    }
}
