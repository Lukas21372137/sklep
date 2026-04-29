<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Historia zakupów</title>

<style>

body{
margin:0;
font-family:Arial;
background:#0b1220;
color:white;
padding:30px;
}

.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h1{
margin:0;
}

.actions{
display:flex;
gap:10px;
}

.btn{
padding:10px 15px;
border:none;
border-radius:10px;
cursor:pointer;
font-weight:bold;
}

.back{
background:#3b82f6;
color:white;
}

.clear{
background:#dc2626;
color:white;
}

.card{
background:#111827;
padding:15px;
border-radius:12px;
margin-bottom:15px;
border:1px solid #1f2937;
animation:fade 0.3s ease;
}

.name{
color:#60a5fa;
font-weight:bold;
}

.product{
color:#22c55e;
}

.price{
color:#f59e0b;
}

.empty{
background:#111827;
padding:20px;
border-radius:12px;
text-align:center;
}

@keyframes fade{
from{
opacity:0;
transform:translateY(10px);
}
to{
opacity:1;
transform:translateY(0);
}
}

</style>
</head>

<body>

<div class="top">

<h1>📜 Historia zakupów</h1>

<div class="actions">

<button class="btn back"
onclick="location.href='index.php'">

⬅ Powrót

</button>

<button class="btn clear"
onclick="clearHistory()">

🗑 Wyczyść historię

</button>

</div>

</div>

<?php

$result = $conn->query("

SELECT
k.imie,
k.nazwisko,
p.nazwa,
p.cena,
z.ilosc

FROM zakupy z

JOIN klienci k
ON z.klient_id = k.id

JOIN produkty p
ON z.produkt_id = p.id

ORDER BY z.id DESC

");

if($result->num_rows > 0):

while($r = $result->fetch_assoc()):
?>

<div class="card">

<div class="name">
👤 <?= $r['imie'] ?> <?= $r['nazwisko'] ?>
</div>

<br>

<div class="product">
🛒 <?= $r['nazwa'] ?>
</div>

<br>

<div>
📦 Ilość: <?= $r['ilosc'] ?>
</div>

<div class="price">
💰 <?= $r['cena'] ?> zł
</div>

</div>

<?php
endwhile;

else:
?>

<div class="empty">

📭 Historia jest pusta

</div>

<?php endif; ?>

<script>

function clearHistory(){

let confirmDelete = confirm(
"Czy na pewno chcesz usunąć CAŁĄ historię zakupów?"
);

if(confirmDelete){

window.location.href = "clear_history.php";

}

}

</script>

</body>
</html>