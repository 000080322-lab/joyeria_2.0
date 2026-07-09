<?php
require_once __DIR__ . '/db.php';

try {
    ensureSchema();
    $conn = getDbConnection();

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $email = 'admin@brillojuvenil.com';
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $hash = hashPassword('admin123');
        $insert = $conn->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $insert->bind_param('ssss', $name, $email, $hash, $role);
        $name = 'Administrador';
        $role = 'admin';
        $insert->execute();
    }

    echo "Base de datos inicializada correctamente.\n";
    echo "Usuario admin: admin@brillojuvenil.com / admin123\n";
} catch (Throwable $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage()) . "\n";
}
