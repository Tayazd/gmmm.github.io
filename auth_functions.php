<?php
require_once 'config.php';
function registerUser($username, $email, $password) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Пользователь с таким email или именем уже существует'];
    }
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $success = $stmt->execute([$username, $email, $hashedPassword]);
    
    if ($success) {
        return ['success' => true, 'message' => 'Регистрация прошла успешно'];
    } else {
        return ['success' => false, 'message' => 'Ошибка при регистрации'];
    }
}
function loginUser($email, $password) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return ['success' => false, 'message' => 'Пользователь с таким email не найден'];
    }
    
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        return ['success' => true, 'message' => 'Вход выполнен успешно'];
    } else {
        return ['success' => false, 'message' => 'Неверный пароль'];
    }
}

function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    return ['success' => true, 'message' => 'Вы успешно вышли'];
}
?>