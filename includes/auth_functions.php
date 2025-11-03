<?php
// includes/auth_functions.php
require_once __DIR__ . '/../config/database.php';
session_start();

function register_user($data) {
    $pdo = getPDO();
    $sql = "INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (:first_name,:last_name,:email,:password,:phone,:role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':first_name' => $data['first_name'],
        ':last_name' => $data['last_name'],
        ':email' => $data['email'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ':phone' => $data['phone'],
        ':role' => $data['role']
    ]);
    return $pdo->lastInsertId();
}

function login_user($email, $password) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        // regenerate session id
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function logout() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function current_user() {
    if (isset($_SESSION['user_id'])) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, role, created_at FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: connexion.php');
        exit;
    }
}
