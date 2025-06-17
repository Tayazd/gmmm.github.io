<?php
// config.php - конфигурация базы данных

define('DB_HOST', 'localhost'); // Хост базы данных
define('DB_USER', 'root');      // Имя пользователя MySQL
define('DB_PASS', '1234');          // Пароль MySQL
define('DB_NAME', 'auth_system'); // Имя базы данных

// Подключение к базе данных
function getDBConnection() {
    try {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}
?>