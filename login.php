<?php
require_once __DIR__ . '/db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        ensureSchema();
        $conn = getDbConnection();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $conn->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && verifyPassword($password, $user['password_hash'])) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $redirect = $user['role'] === 'admin' ? 'dashboard.php' : 'index.php?name=' . urlencode($user['name']);
            header('Location: ' . $redirect);
            exit;
        }

        $message = 'Correo o contraseña incorrectos.';
        $messageType = 'error';
    } catch (Throwable $e) {
        $message = 'No se pudo conectar a la base de datos: ' . $e->getMessage();
        $messageType = 'error';
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Brillo Juvenil</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body class="auth-page">
    <div class="auth-shell">
      <div class="auth-card">
        <div class="auth-card__header">
          <a class="brand-link" href="index.php">
            <span class="brand-mark">✦</span>
            <h1 class="brand">Brillo Juvenil</h1>
          </a>
          <p>Accede con tu correo y contraseña para entrar al panel correcto.</p>
        </div>

        <?php if ($message): ?>
          <div class="message <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post">
          <label>
            Correo electrónico
            <input type="email" name="email" required placeholder="tu@correo.com" />
          </label>
          <label>
            Contraseña
            <input type="password" name="password" required placeholder="••••••••" />
          </label>
          <button type="submit">Ingresar</button>
        </form>

        <p class="auth-tip">Si eres administrador, entrarás al dashboard. Si eres usuario, volverás a la landing page.</p>
      </div>
    </div>
  </body>
</html>
