<?php
require_once 'auth_functions.php';

// Обработка AJAX-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    if (isset($_POST['action'])) {
        $response = [];
        
        switch ($_POST['action']) {
            case 'register':
                if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm'])) {
                    if ($_POST['password'] === $_POST['confirm']) {
                        $response = registerUser($_POST['username'], $_POST['email'], $_POST['password']);
                    } else {
                        $response = ['success' => false, 'message' => 'Пароли не совпадают'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Все поля обязательны для заполнения'];
                }
                break;
                
            case 'login':
                if (!empty($_POST['email']) && !empty($_POST['password'])) {
                    $response = loginUser($_POST['email'], $_POST['password']);
                } else {
                    $response = ['success' => false, 'message' => 'Email и пароль обязательны'];
                }
                break;
                
            case 'logout':
                $response = logoutUser();
                break;
                
            default:
                $response = ['success' => false, 'message' => 'Неизвестное действие'];
        }
        
        echo json_encode($response);
        exit;
    }
}

// Перенаправляем, если запрос не AJAX
header("Location: index.html");
?>