<?php include 'db.php'; ?>
<link rel="stylesheet" href="style.css">

<div style="padding:20px;">

<h2>📜 Historia zamówień</h2>

<?php
$result = $conn->query("
SELECT 
    z.id as zamowienie_id,
    z.data,
    k.imie,
    k.nazwisko,
    p.nazwa,
    p.cena,
    zp.ilosc
FROM zamowienia z
JOIN klienci k ON z.klient_id = k.id
JOIN zamowienia_pozycje zp ON zp.zamowienie_id = z.id
JOIN produkty p ON zp.produkt_id = p.id
ORDER BY z.id DESC
");

$current = null;

while($r = $result->fetch_assoc()) {

    if ($current != $r['zamowienie_id']) {
        echo "<hr><div class='card'>
            <h3>🧾 Zamówienie #{$r['zamowienie_id']}</h3>
            <b>{$r['imie']} {$r['nazwisko']}</b><br>
            📅 {$r['data']}<br><br>
        ";
        $current = $r['zamowienie_id'];
    }

    echo "• {$r['nazwa']} x {$r['ilosc']} ({$r['cena']} zł)<br>";

}
echo "</div>";
?>

<br>

<a href="index.php">
    <button style="
        padding:12px 18px;
        border:none;
        border-radius:10px;
        background:#3b82f6;
        color:white;
        cursor:pointer;
    ">
    ⬅ Powrót do strony głównej
    </button>
</a>

</div>