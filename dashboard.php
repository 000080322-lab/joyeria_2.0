<?php
require_once __DIR__ . '/db.php';
requireAdmin();

ensureSchema();
$conn = getDbConnection();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] === 'admin' ? 'admin' : 'user';

        if ($name && $email && $password) {
            $hash = hashPassword($password);
            $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $name, $email, $hash, $role);
            $stmt->execute();
            $message = 'Usuario creado correctamente.';
            $messageType = 'success';
        } else {
            $message = 'Completa todos los campos del nuevo usuario.';
            $messageType = 'error';
        }
    } elseif ($action === 'update_user') {
        $userId = (int) ($_POST['user_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
        $password = $_POST['password'] ?? '';

        if ($userId > 0 && $name && $email) {
            if ($password !== '') {
                $hash = hashPassword($password);
                $stmt = $conn->prepare('UPDATE users SET name = ?, email = ?, role = ?, password_hash = ? WHERE id = ?');
                $stmt->bind_param('ssssi', $name, $email, $role, $hash, $userId);
            } else {
                $stmt = $conn->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?');
                $stmt->bind_param('sssi', $name, $email, $role, $userId);
            }
            $stmt->execute();
            $message = 'Usuario actualizado correctamente.';
            $messageType = 'success';
        } else {
            $message = 'No se pudo actualizar el usuario.';
            $messageType = 'error';
        }
    }
}

$usersResult = $conn->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC');
$users = $usersResult ? $usersResult->fetch_all(MYSQLI_ASSOC) : [];

$dailySalesResult = $conn->query('SELECT sale_date, SUM(total_amount) AS total FROM sales GROUP BY sale_date ORDER BY sale_date DESC');
$dailySales = $dailySalesResult ? $dailySalesResult->fetch_all(MYSQLI_ASSOC) : [];

$totals = $conn->query('SELECT COUNT(*) AS total_users FROM users');
$totalUsers = $totals ? $totals->fetch_assoc()['total_users'] : 0;

$sumSales = $conn->query('SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM sales');
$totalSales = $sumSales ? $sumSales->fetch_assoc()['total_sales'] : 0;
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard | Brillo Juvenil</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body class="dashboard-page">
    <div class="dashboard-shell">
      <aside class="sidebar">
        <div>
          <h2>Brillo Juvenil</h2>
          <p>Panel administrativo</p>
        </div>
        <nav>
          <a href="dashboard.php">Inicio</a>
          <a href="index.php">Landing</a>
          <a href="logout.php">Cerrar sesión</a>
        </nav>
      </aside>

      <main class="dashboard-main">
        <header class="dashboard-header">
          <div>
            <h1>Bienvenido, <?= htmlspecialchars(currentUserName()) ?></h1>
            <p>Gestiona usuarios y revisa las ventas del negocio.</p>
          </div>
        </header>

        <?php if ($message): ?>
          <div class="message <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="stats-grid">
          <article class="stat-card">
            <h3>Usuarios</h3>
            <p><?= (int) $totalUsers ?></p>
          </article>
          <article class="stat-card">
            <h3>Ventas totales</h3>
            <p>$<?= number_format((float) $totalSales, 2) ?></p>
          </article>
        </section>

        <section class="panel-card">
          <h3>Agregar nuevo usuario</h3>
          <form class="dashboard-form" method="post">
            <input type="hidden" name="action" value="create_user" />
            <input type="text" name="name" placeholder="Nombre completo" required />
            <input type="email" name="email" placeholder="Correo" required />
            <input type="password" name="password" placeholder="Contraseña" required />
            <select name="role">
              <option value="user">Usuario</option>
              <option value="admin">Administrador</option>
            </select>
            <button type="submit">Crear usuario</button>
          </form>
        </section>

        <section class="panel-card">
          <h3>Usuarios registrados</h3>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Rol</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                      <form method="post" class="inline-form">
                        <input type="hidden" name="action" value="update_user" />
                        <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>" />
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required />
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
                        <select name="role">
                          <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Usuario</option>
                          <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                        <input type="password" name="password" placeholder="Nueva contraseña" />
                        <button type="submit">Guardar</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>

        <section class="panel-card">
          <h3>Ventas diarias</h3>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Total</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dailySales as $sale): ?>
                  <tr>
                    <td><?= htmlspecialchars($sale['sale_date']) ?></td>
                    <td>$<?= number_format((float) $sale['total'], 2) ?></td>
                    <td>Venta registrada</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
