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

    public function register($csrf_token, $name, $email, $password, $confirm_password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(array(":name" => $name));

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Username Already Exist.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../ums/registration.php');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Email Already Exist.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../ums/registration.php');
            exit;
        }

        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $_SESSION['message'] = 'Invalid CSRF Token.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../ums/registration.php');
            exit;
        }

        unset($_SESSION['csrf_token']);


        if ($password !== $confirm_password) {
            $_SESSION['message'] = 'Passwords do not match.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../ums/registration.php');
            exit;
        }

        $hash_password = md5($password);
        $stmt = $this->conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $exec = $stmt->execute(array(":name" => $name, ":email" => $email, ":password" => $hash_password));

        if ($exec) {
            $_SESSION['message'] = 'Admin added successfully.';
            $_SESSION['message_type'] = 'success';
            header('Location: ../ums/');
            exit;
        } else {
            $_SESSION['message'] = 'Error adding admin.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../ums/registration.php');
            exit;
        }
    }

    public function login($csrf_token, $email, $password)
    {
        try {
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                $_SESSION['message'] = 'Invalid CSRF Token.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../ums/');
                exit;
            }

            unset($_SESSION['csrf_token']);

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND status = :status");
            $stmt->execute([":email" => $email, ":status" => "active"]);
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {
                if ($userRow['status'] == "active") {
                    if ($userRow['password'] == md5($password)) {

                        $_SESSION['session'];
                        header('Location: ../ums/dashboard/admin/');
                    } else {

                        $_SESSION['message'] = 'Incorrect Password.';
                        $_SESSION['message_type'] = 'error';
                        header('Location: ../ums/');
                        exit;
                    }
                } else {

                    $_SESSION['message'] = 'Inactive User.';
                    $_SESSION['message_type'] = 'error';
                    header('Location: ../ums/');
                    exit;
                }
            } else {
                $_SESSION['message'] = 'No account found.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../ums/');
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
