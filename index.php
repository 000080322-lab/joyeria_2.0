<?php
require_once __DIR__ . '/db.php';

$displayName = 'visitante';
if (!empty($_SESSION['user_name'])) {
    $displayName = $_SESSION['user_name'];
} elseif (!empty($_GET['name'])) {
    $displayName = $_GET['name'];
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Joyas | Brillo Juvenil</title>
    <meta name="description" content="Landing page de joyería con estilo moderno y elegante para Brillo Juvenil." />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-inner">
        <a class="brand-link" href="index.php">
          <span class="brand-mark">✦</span>
          <h1 class="brand">Brillo Juvenil</h1>
        </a>
        <div class="header-actions">
          <div class="greeting-pill">Hola, <?= htmlspecialchars($displayName) ?></div>
          <?php if (isLoggedIn()): ?>
            <a class="login-link" href="<?= $_SESSION['role'] === 'admin' ? 'dashboard.php' : 'index.php' ?>">Mi cuenta</a>
            <a class="login-link secondary" href="logout.php">Salir</a>
          <?php else: ?>
            <a class="login-link" href="login.php">Iniciar sesión</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <main>
      <section class="hero">
        <div class="container hero-inner">
          <div class="hero-text">
            <p class="eyebrow">Joyas con personalidad</p>
            <h2>Elegancia joven, diseño sofisticado</h2>
            <p>Descubre piezas únicas que combinan brillo, comodidad y estilo para cada ocasión. Desde anillos delicados hasta sets de fiesta impactantes.</p>
            <div class="hero-actions">
              <a class="btn-cta" href="#coleccion">Explora la colección</a>
              <a class="btn-secondary hero-btn-secondary" href="#contacto">Habla con nosotras</a>
            </div>
            <ul class="hero-highlights">
              <li>Envíos rápidos</li>
              <li>Diseños exclusivos</li>
              <li>Calidad premium</li>
            </ul>
          </div>
          <div class="hero-image">
            <img src="https://source.unsplash.com/900x900/?jewelry,necklace" alt="Collar elegante" />
          </div>
        </div>
      </section>

      <section id="beneficios" class="benefits container">
        <div class="section-heading">
          <h3>Por qué elegir Brillo Juvenil</h3>
          <p>Estilo, calidad y atención personalizada en cada detalle.</p>
        </div>
        <div class="benefits-grid">
          <article class="benefit-card">
            <h4>Diseño exclusivo</h4>
            <p>Piezas modernas y sofisticadas pensadas para destacar con sutileza.</p>
          </article>
          <article class="benefit-card">
            <h4>Calidad premium</h4>
            <p>Materiales con acabado luminoso y excelente presencia visual.</p>
          </article>
          <article class="benefit-card">
            <h4>Atención cercana</h4>
            <p>Te guiamos para encontrar el look perfecto según tu estilo y ocasión.</p>
          </article>
        </div>
      </section>

      <section id="coleccion" class="gallery container">
        <div class="section-heading">
          <h3>Destacados</h3>
          <p>Lo más buscado para lucir elegante todos los días.</p>
        </div>
        <div class="grid">
          <div class="card"><img src="https://source.unsplash.com/800x800/?ring,jewelry" alt="Anillo moderno" /><span>Anillos</span></div>
          <div class="card"><img src="https://source.unsplash.com/800x800/?necklace,jewelry" alt="Collar brillante" /><span>Collares</span></div>
          <div class="card"><img src="https://source.unsplash.com/800x800/?bracelet,jewelry" alt="Pulsera delicada" /><span>Pulseras</span></div>
          <div class="card"><img src="https://source.unsplash.com/800x800/?jewelry,earrings" alt="Set de fiesta" /><span>Sets de fiesta</span></div>
        </div>
      </section>

      <section id="promocion" class="promo">
        <div class="container promo-inner">
          <div class="promo-text">
            <h3>Promoción limitada</h3>
            <p>20% de descuento en sets de fiesta por tiempo limitado. Encuentra tu look perfecto para destacar en cada evento.</p>
            <a class="btn-secondary" href="#contacto">Aprovechar promoción</a>
          </div>
          <div class="promo-image">
            <img src="https://source.unsplash.com/800x600/?jewelry,party" alt="Set de fiesta brillante" />
          </div>
        </div>
      </section>

      <section class="full-gallery container">
        <div class="section-heading">
          <h3>Galería</h3>
          <p>Inspiración para tus próximos looks.</p>
        </div>
        <div class="masonry">
          <img src="https://source.unsplash.com/900x700/?gold,jewelry" alt="Joyas doradas" />
          <img src="https://source.unsplash.com/900x900/?fashion,jewelry" alt="Joyas moda" />
          <img src="https://source.unsplash.com/1200x800/?minimal,jewelry" alt="Diseño minimalista" />
          <img src="https://source.unsplash.com/1000x1000/?luxury,jewelry" alt="Lujo moderno" />
        </div>
      </section>

      <section id="contacto" class="contact container">
        <div class="contact-card">
          <div>
            <h3>Contacto</h3>
            <p>¿Tienes dudas o quieres un pedido personalizado? Escríbenos y te responderemos con gusto.</p>
            <ul class="contact-list">
              <li>Email: <a href="mailto:ventas@brillojuvenil.com">ventas@brillojuvenil.com</a></li>
              <li>Instagram: <a href="https://www.instagram.com/ed.garcialop?igsh=ZzRtY2hkZmljNGdp&utm_source=qr" target="_blank" rel="noopener noreferrer">@brillojuvenil</a></li>
              <li>Teléfono: <a href="tel:+1234567890">+1 234 567 890</a></li>
            </ul>
          </div>
          <div class="contact-actions">
            <a class="btn-cta" href="mailto:ventas@brillojuvenil.com">Escríbenos</a>
            <a class="btn-secondary" href="https://www.instagram.com/ed.garcialop?igsh=ZzRtY2hkZmljNGdp&utm_source=qr" target="_blank" rel="noopener noreferrer">Ver Instagram</a>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-inner">
        <p>Brillo Juvenil — Joyas con estilo sofisticado</p>
        <p>&copy; <span id="year"></span></p>
      </div>
    </footer>

    <script>
      document.getElementById('year').textContent = new Date().getFullYear();
    </script>
  </body>
</html>
