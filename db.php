<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getDbConnection()
{
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: 3306;
    $database = getenv('DB_NAME') ?: 'joyeria_db';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';

    $conn = new mysqli($host, $username, $password, $database, (int) $port);
    $conn->set_charset('utf8mb4');
    return $conn;
}

function ensureSchema()
{
    $conn = getDbConnection();
    $conn->query(
        "CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(180) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM('admin','user') NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $conn->query(
        "CREATE TABLE IF NOT EXISTS sales (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            sale_date DATE NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            description VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}

function isLoggedIn()
{
    return !empty($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin()
{
    requireLogin();
    if (($_SESSION['role'] ?? 'user') !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

function currentUserName()
{
    return $_SESSION['user_name'] ?? 'visitante';
}
