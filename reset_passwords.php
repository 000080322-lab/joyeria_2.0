<?php
// reset_passwords.php
// Ejecutar vía URL: https://<tu-app>/reset_passwords.php?token=SECRETO
// Requiere que configures la variable de entorno RESET_TOKEN en Railway.
// También puede ejecutarse desde CLI: php reset_passwords.php <token>

require_once __DIR__ . '/db.php';

$provided = null;
if (php_sapi_name() === 'cli') {
    $provided = $argv[1] ?? '';
} else {
    $provided = $_GET['token'] ?? '';
}

$secret = getenv('RESET_TOKEN') ?: '';
if (!$secret) {
    http_response_code(500);
    echo "ERROR: RESET_TOKEN no está configurado en el entorno.\n";
    exit(1);
}

if (!hash_equals($secret, $provided)) {
    http_response_code(403);
    echo "Forbidden: token inválido.\n";
    exit(1);
}

try {
    ensureSchema();
    $conn = getDbConnection();

    $newHash = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE users SET password_hash = ?');
    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('s', $newHash);
    $stmt->execute();

    $affected = $conn->affected_rows;

    echo "OK: Se actualizaron {$affected} usuarios.\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Nota: después de ejecutar este script, todos los usuarios podrán iniciar
// sesión usando la contraseña "admin123". Cambia o elimina el script cuando
// ya no lo necesites.
