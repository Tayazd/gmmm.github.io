<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Валидация данных
if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Email и пароль обязательны']);
    exit;
}

// Поиск пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

// Проверка пароля
if (!password_verify($data['password'], $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
    exit;
}

// Старт сессии и сохранение данных пользователя
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];

echo json_encode([
    'success' => true,
    'message' => 'Вход выполнен успешно',
    'user' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email']
    ]
]);
?>