<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) {
    die("
    <div style='color:white;background:#0b1220;height:100vh;display:flex;justify-content:center;align-items:center;flex-direction:column;'>
        🛒 Koszyk pusty<br><br>
        <a href='index.php' style='color:#3b82f6'>⬅ Wróć do sklepu</a>
    </div>");
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Koszyk</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#0b1220;
    color:white;
}

.container {
    padding:20px;
}

.card {
    background:#111827;
    padding:16px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid #1f2937;
    animation: fade 0.3s ease;
}

button {
    padding:12px;
    border:none;
    border-radius:10px;
    background:#22c55e;
    color:white;
    cursor:pointer;
}

a {
    color:#60a5fa;
}

@keyframes fade {
    from {opacity:0; transform:translateY(10px);}
    to {opacity:1; transform:translateY(0);}
}
</style>

</head>

<body>

<div class="container">

<h2>🛒 Koszyk</h2>

<?php
foreach ($_SESSION['cart'] as $id => $ilosc) {

    $p = $conn->query("SELECT * FROM produkty WHERE id=$id")->fetch_assoc();

    $suma = $p['cena'] * $ilosc;
    $total += $suma;

    echo "<div class='card'>
        <b>{$p['nazwa']}</b><br>
        Ilość: $ilosc<br>
        Suma: $suma zł
    </div>";
}
?>

<h3>💰 Razem: <?= $total ?> zł</h3>

<form action="checkout.php" method="POST">
    <button>✔ Zamów</button>
</form>

<br><br>

<a href="index.php">⬅ Powrót do sklepu</a>

</div>

</body>
</html>