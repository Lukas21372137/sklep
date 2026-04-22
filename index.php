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

body{
margin:0;
font-family:Arial;
background:#0b1220;
color:white;
}

/* TOPBAR */

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 25px;
background:#111827;
border-bottom:1px solid #1f2937;
position:sticky;
top:0;
z-index:100;
}

.logo{
font-weight:bold;
color:#60a5fa;
}

/* NAV */

.nav{
display:flex;
align-items:center;
gap:15px;
position:relative;
}

.nav a{
color:white;
text-decoration:none;
}

/* CART BUTTON */

.cartBtn{
background:#1f2937;
padding:8px 14px;
border-radius:12px;
cursor:pointer;
color:#22c55e;
font-weight:bold;
transition:0.2s;
}

.cartBtn:hover{
transform:scale(1.05);
}

/* DROPDOWN */

.cartDropdown{
position:absolute;
top:50px;
right:0;
width:320px;
background:#111827;
border:1px solid #1f2937;
border-radius:14px;
padding:15px;
display:none;
animation:fade 0.25s ease;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.cartDropdown.show{
display:block;
}

.cartItem{
padding:10px 0;
border-bottom:1px solid #1f2937;
}

.cartItem:last-child{
border:none;
}

.cartName{
font-weight:bold;
}

.cartQty{
color:#9ca3af;
font-size:14px;
}

.goCart{
margin-top:15px;
width:100%;
padding:10px;
border:none;
border-radius:10px;
background:#3b82f6;
color:white;
cursor:pointer;
}

/* GRID */

.container{
padding:20px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
gap:15px;
}

/* CARD */

.card{
background:#111827;
padding:15px;
border-radius:14px;
border:1px solid #1f2937;
transition:0.2s;
}

.card:hover{
transform:translateY(-4px);
}

.price{
color:#22c55e;
font-weight:bold;
}

.stock{
color:#f59e0b;
font-size:14px;
}

input{
width:60px;
padding:5px;
margin-top:8px;
background:#1f2937;
border:none;
color:white;
}

button{
margin-top:10px;
width:100%;
padding:8px;
border:none;
border-radius:8px;
background:#3b82f6;
color:white;
cursor:pointer;
}

.lockBox{
margin-top:10px;
padding:10px;
border-radius:10px;
background:#1f2937;
color:#fbbf24;
text-align:center;
}

/* ANIMATIONS */

.pop{
animation:pop 0.3s ease;
}

@keyframes pop{
from{transform:scale(0.8);}
to{transform:scale(1);}
}

@keyframes fade{
from{opacity:0;transform:translateY(-10px);}
to{opacity:1;transform:translateY(0);}
}

</style>
</head>

<body>

<header class="topbar">

<div class="logo">
🛍️ SKLEP PRO
</div>

<div class="nav">

<div class="cartBtn" onclick="toggleCart()">
🛒 <span id="cartCount"><?= $cartCount ?></span>
</div>

<div class="cartDropdown" id="cartDropdown">

<div id="dropdownItems">

<?php

if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $id=>$qty){

$r = $conn->query("SELECT nazwa FROM produkty WHERE id=$id");
$p = $r->fetch_assoc();

echo "
<div class='cartItem'>
<div class='cartName'>{$p['nazwa']}</div>
<div class='cartQty'>Ilość: $qty</div>
</div>
";
}

}else{

echo "<p>Koszyk pusty</p>";

}
?>

</div>

<button class="goCart" onclick="location.href='koszyk.php'">
Przejdź do koszyka
</button>

</div>

<?php if(isset($_SESSION['user_id'])): ?>

<a href="historia.php">Historia</a>
<a href="logout.php">Logout</a>

<?php else: ?>

<a href="login.php">Login</a>
<a href="register.php">Rejestracja</a>

<?php endif; ?>

</div>

</header>

<div class="container">

<h2>🔥 Produkty</h2>

<div class="grid">

<?php

$res = $conn->query("SELECT * FROM produkty");

while($p = $res->fetch_assoc()):
?>

<div class="card">

<h3><?= htmlspecialchars($p['nazwa']) ?></h3>

<div class="price">
💰 <?= $p['cena'] ?> zł
</div>

<div class="stock">
📦 Stan: <?= $p['stan_magazynowy'] ?>
</div>

<?php if(isset($_SESSION['user_id'])): ?>

<input type="number"
id="qty<?= $p['id'] ?>"
value="1"
min="1">

<button onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nazwa']) ?>', this)">
🛒 Dodaj
</button>

<?php else: ?>

<div class="lockBox">
🔒 Zaloguj się aby kupić
</div>

<?php endif; ?>

</div>

<?php endwhile; ?>

</div>

</div>

<script>

function toggleCart(){

document.getElementById("cartDropdown")
.classList.toggle("show");

}

function addToCart(id, name, btn){

let qty = parseInt(
document.getElementById("qty"+id).value
);

fetch("add_to_cart.php", {

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"produkt_id="+id+"&ilosc="+qty

})

.then(r=>r.json())

.then(d=>{

if(!d.success){
alert("Zaloguj się");
return;
}

document.getElementById("cartCount")
.innerText = d.count;

/* DODAJ DO DROPDOWNU */

let items = document.getElementById("dropdownItems");

items.innerHTML += `
<div class="cartItem pop">
<div class="cartName">${name}</div>
<div class="cartQty">Ilość: ${qty}</div>
</div>
`;

/* KONFETTI */

confetti({
particleCount:120,
spread:70,
origin:{y:0.6}
});

/* ANIMACJA */

let card = btn.closest(".card");

card.classList.add("pop");

setTimeout(()=>{
card.classList.remove("pop");
},300);

});

}

</script>

</body>
</html>