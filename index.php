<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<header class="topbar">

    <div class="logo">
        🛍️ Premium Shop
    </div>

    <nav class="nav">

        <?php if (isset($_SESSION['user_id'])): ?>

            <span>👤 <?= htmlspecialchars($_SESSION['login']) ?></span>

            <a href="koszyk.php">🛒 Koszyk</a>
            <a href="historia.php">📜 Historia</a>
            <a href="logout.php">🚪 Wyloguj</a>

        <?php else: ?>

            <a href="login.php">🔐 Login</a>
            <a href="register.php">🆕 Rejestracja</a>

        <?php endif; ?>

    </nav>

</header>


<main class="container">

<h2>📦 Produkty</h2>

<div class="grid">

<?php
$produkty = $conn->query("SELECT * FROM produkty");

while ($p = $produkty->fetch_assoc()):
?>

<div class="card">

    <h3><?= htmlspecialchars($p['nazwa']) ?></h3>

    <p class="price">💰 <?= $p['cena'] ?> zł</p>
    <p class="stock">📦 Stan: <?= $p['stan_magazynowy'] ?></p>

    <?php if (isset($_SESSION['user_id'])): ?>

        <form action="add_to_cart.php" method="POST">

            <input type="hidden" name="produkt_id" value="<?= $p['id'] ?>">

            <label>Ilość</label>
            <input type="number" name="ilosc" value="1" min="1">

            <button type="submit">🛒 Dodaj do koszyka</button>

        </form>

    <?php else: ?>

        <p style="color:#aaa;">🔐 Zaloguj się, aby kupować</p>

    <?php endif; ?>

</div>

<?php endwhile; ?>

</div>

</main>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</body>
</html>