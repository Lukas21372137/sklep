<?php
session_start();
include 'db.php';

$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Sklep PRO</title>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
body {
    margin:0;
    font-family: Arial;
    background:#0b1220;
    color:#e5e7eb;
}

/* TOPBAR */
.topbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 25px;
    background:#111827;
    border-bottom:1px solid #1f2937;
    position:sticky;
    top:0;
}

.logo {
    font-weight:bold;
    color:#60a5fa;
}

.nav a {
    color:#e5e7eb;
    margin-left:15px;
    text-decoration:none;
}

#cartCount {
    background:#1f2937;
    padding:6px 12px;
    border-radius:12px;
    color:#22c55e;
    font-weight:bold;
}

/* LAYOUT */
.container {
    padding:20px;
}

.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:15px;
}

/* CARD */
.card {
    background:#111827;
    padding:15px;
    border-radius:14px;
    border:1px solid #1f2937;
    transition:0.2s;
}

.card:hover {
    transform:translateY(-4px);
}

/* TEKSTY */
h3 {
    margin:0;
}

.price {
    color:#22c55e;
    font-weight:bold;
}

.stock {
    color:#f59e0b;
    font-size:14px;
}

/* INPUT */
input {
    width:60px;
    margin-top:8px;
    padding:5px;
    background:#1f2937;
    border:none;
    color:white;
}

/* BUTTON */
button {
    margin-top:10px;
    width:100%;
    padding:8px;
    border:none;
    border-radius:8px;
    background:#3b82f6;
    color:white;
    cursor:pointer;
}

button:hover {
    background:#2563eb;
}

/* LOCK */
.lockBox {
    margin-top:10px;
    padding:10px;
    border-radius:10px;
    background:#1f2937;
    color:#fbbf24;
    text-align:center;
    border:1px solid #374151;
}

/* ANIMACJE */
.pop { animation:pop 0.3s ease; }

@keyframes pop {
    from {transform:scale(0.8); opacity:0;}
    to {transform:scale(1); opacity:1;}
}

.bounce { animation:bounce 0.4s ease; }

@keyframes bounce {
    0% {transform:scale(1);}
    50% {transform:scale(1.3);}
    100% {transform:scale(1);}
}
</style>
</head>

<body>

<header class="topbar">
    <div class="logo">🛍️ SKLEP PRO</div>

    <div class="nav">
        <span id="cartCount">🛒 <?= $cartCount ?></span>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="koszyk.php">Koszyk</a>
            <a href="historia.php">Historia</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Rejestracja</a>
        <?php endif; ?>
    </div>
</header>

<div class="container">
<h2>Produkty</h2>

<div class="grid">

<?php
$res = $conn->query("SELECT * FROM produkty");

while($p = $res->fetch_assoc()):
?>

<div class="card">

<h3><?= htmlspecialchars($p['nazwa']) ?></h3>

<div class="price">💰 <?= $p['cena'] ?> zł</div>
<div class="stock">📦 Stan: <?= $p['stan_magazynowy'] ?></div>

<?php if(isset($_SESSION['user_id'])): ?>

<input type="number" id="qty<?= $p['id'] ?>" value="1" min="1">

<button onclick="addToCart(<?= $p['id'] ?>, this)">
🛒 Dodaj do koszyka
</button>

<?php else: ?>

<div class="lockBox">
🔒 Zaloguj się, aby kupić
</div>

<?php endif; ?>

</div>

<?php endwhile; ?>

</div>
</div>

<script>
function addToCart(id, btn){

let qty = parseInt(document.getElementById("qty"+id).value);

fetch("add_to_cart.php", {
    method:"POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body:"produkt_id="+id+"&ilosc="+qty
})
.then(r=>r.json())
.then(d=>{

    if(!d.success){
        alert("Zaloguj się, aby kupować");
        window.location.href="login.php";
        return;
    }

    let counter = document.getElementById("cartCount");
    counter.innerText = "🛒 " + d.count;
    counter.classList.add("bounce");

    setTimeout(()=>counter.classList.remove("bounce"), 400);

    confetti({
        particleCount: 150,
        spread: 80,
        origin: { y: 0.6 }
    });

    let card = btn.closest(".card");
    card.classList.add("pop");

    setTimeout(()=>card.classList.remove("pop"), 300);

});
}
</script>

</body>
</html>