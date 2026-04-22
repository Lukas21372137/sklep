<?php
session_start();
$id = $_GET['id'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Zamówienie</title>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
body {
    margin:0;
    background:#0b1220;
    color:white;
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box {
    background:#111827;
    padding:30px;
    border-radius:16px;
    text-align:center;
    animation: pop .4s ease;
}

button {
    margin-top:15px;
    padding:10px;
    border:none;
    border-radius:10px;
    background:#3b82f6;
    color:white;
    cursor:pointer;
}

@keyframes pop {
    from {transform:scale(0.8); opacity:0;}
    to {transform:scale(1); opacity:1;}
}
</style>
</head>

<body>

<div class="box">

<h1>🎉 Zamówienie złożone!</h1>

<p>ID zamówienia: <b>#<?= $id ?></b></p>

<button onclick="location.href='index.php'">
🏠 Wróć do sklepu
</button>

</div>

<script>
confetti({
    particleCount: 200,
    spread: 90,
    origin: { y: 0.6 }
});
</script>

</body>
</html>