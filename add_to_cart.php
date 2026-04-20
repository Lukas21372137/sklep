<?php
session_start();

$id = (int)$_POST['produkt_id'];
$ilosc = (int)$_POST['ilosc'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] += $ilosc;
} else {
    $_SESSION['cart'][$id] = $ilosc;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dodano do koszyka</title>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background:#0b1220;
            color:white;
            font-family:Arial;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            flex-direction:column;
        }

        .box {
            background:#111827;
            padding:20px;
            border-radius:12px;
            text-align:center;
            animation: pop 0.3s ease;
        }

        @keyframes pop {
            from { transform: scale(0.8); opacity:0; }
            to { transform: scale(1); opacity:1; }
        }
    </style>
</head>

<body>

<div class="box">
    <h2>🛒 Dodano do koszyka</h2>
    <p>Przekierowanie...</p>
</div>

<script>
confetti({
    particleCount: 140,
    spread: 80,
    origin: { y: 0.6 }
});

setTimeout(() => {
    window.location.href = "index.php";
}, 900);
</script>

</body>
</html>