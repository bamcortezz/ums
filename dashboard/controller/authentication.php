<?php

require_once __DIR__ . ('/../../database/database.php');
include_once __DIR__ . ('/../../config/settings-config.php');
require_once __DIR__ . ('/../../src/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth
{
    private $conn;
    private $smtp_email;
    private $smtp_password;
    private $settings;

    public function __construct()
    {
        $this->settings = new SystemConfig();
        $this->smtp_email = $this->settings->getSmtpEmail();
        $this->smtp_password = $this->settings->getSmtpPassword();

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
            header('Location: ../../registration.php');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Email Already Exist.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../registration.php');
            exit;
        }

        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $_SESSION['message'] = 'Invalid CSRF Token.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../registration.php');
            exit;
        }

        unset($_SESSION['csrf_token']);


        if ($password !== $confirm_password) {
            $_SESSION['message'] = 'Passwords do not match.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../registration.php');
            exit;
        }

        $hash_password = md5($password);
        $stmt = $this->conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $exec = $stmt->execute(array(":name" => $name, ":email" => $email, ":password" => $hash_password));

        if ($exec) {
            $_SESSION['message'] = 'Admin added successfully.';
            $_SESSION['message_type'] = 'success';
            header('Location: ../../');
            exit;
        } else {
            $_SESSION['message'] = 'Error adding admin.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../registration.php');
            exit;
        }
    }

    public function login($csrf_token, $email, $password)
    {
        try {
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                $_SESSION['message'] = 'Invalid CSRF Token.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../../');
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
                        header('Location: ../../dashboard/admin/index.php');
                    } else {

                        $_SESSION['message'] = 'Incorrect Password.';
                        $_SESSION['message_type'] = 'error';
                        header('Location: ../../');
                        exit;
                    }
                } else {

                    $_SESSION['message'] = 'Inactive User.';
                    $_SESSION['message_type'] = 'error';
                    header('Location: ../../');
                    exit;
                }
            } else {
                $_SESSION['message'] = 'No account found.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../../');
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function forgotPassword($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(([":email" => $email]));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 0) {
            $_SESSION['message'] = 'Email not found.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../forgot-password.php');
        } else {
            $id = $userRow['id'];
            $token = bin2hex(random_bytes(32));

            $stmt = $this->conn->prepare("UPDATE users SET token = :token WHERE id = :id");
            $stmt->execute([":token" => $token, ":id" => $id]);

            $link = "http://localhost/ums/reset-password.php?token=$token&id=$id";
            $message = "Click the link to reset your password: $link";
            $subject = "Password Reset";

            $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);

            $_SESSION['message'] = 'Check your email to reset your password.';
            $_SESSION['message_type'] = 'success';
            header('Location: ../../forgot-password.php');
        }
    }

    public function validateToken($id, $token)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id AND token = :token");
        $stmt->execute([":id" => $id, ":token" => $token]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRow) {
            return true;
        }

        return false;
    }

    public function resetPassword($id, $token, $password, $confirm_password)
    {

        if ($this->validateToken($id, $token)) {

            if ($password !== $confirm_password) {
                $_SESSION['message'] = 'Passwords do not match.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../../reset-password.php?token=' . $token . '&id=' . $id);
                exit;
            }

            $hash_password = md5($password);
            $stmt = $this->conn->prepare("UPDATE users SET password = :password, token = :token WHERE id = :id");
            $exec = $stmt->execute([":password" => $hash_password, ":token" => null, ":id" => $id]);

            if ($exec) {
                $_SESSION['message'] = 'Password reset successful.';
                $_SESSION['message_type'] = 'success';
                header('Location: ../../');
                exit;
            } else {
                $_SESSION['message'] = 'Error resetting password.';
                $_SESSION['message_type'] = 'error';
                header('Location: ../../reset-password.php?token=' . $token . '&id=' . $id);
                exit;
            }
        } else {
            $_SESSION['message'] = 'Invalid Token.';
            $_SESSION['message_type'] = 'error';
            header('Location: ../../forgot-password.php');
            exit;
        }
    }

    public function send_email($email, $message, $subject, $smtp_email, $smtp_password)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->addAddress($email);
        $mail->Username = $smtp_email;
        $mail->Password = $smtp_password;
        $mail->setFrom($smtp_email, "no-reply");
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->Send();
    }

    public function onlineUser()
    {
        if (isset($_SESSION['session'])) {
            return true;
        }
    }

    public function signout()
    {
        unset($_SESSION['session']);
        $_SESSION['message'] = 'Sign out successful.';
        $_SESSION['message_type'] = 'success';
        header('Location: ../../');
        exit;
    }
}

//login
if (isset($_POST['btn-login'])) {
    $csrf_token = $_POST['csrf_token'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new Auth();
    $login->login($csrf_token, $email, $password);
}

//register
if (isset($_POST['btn-register'])) {
    $csrf_token = $_POST['csrf_token'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $register = new Auth();
    $register->register($csrf_token, $name, $email, $password, $confirm_password);
}

//forgot-password
if (isset($_POST['btn-forgot-password'])) {
    $email = trim($_POST['email']);

    $forgotPassword = new Auth();
    $forgotPassword->forgotPassword($email);
}

//reset-password
if (isset($_POST['btn-reset-password'])) {
    $token = $_POST['token'];
    $id = $_POST['id'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $resetPassword = new Auth();
    $resetPassword->resetPassword($id, $token, $password, $confirm_password);
}

//signout
if (isset($_GET['signout'])) {
    $signout = new Auth();
    $signout->signout();
}
