<?php
session_start();
include 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = trim($_POST['login']);
    $haslo = $_POST['haslo'];

    /* WALIDACJA */

    if(strlen($login) < 3){

        $error = "❌ Login za krótki";

    } elseif(strlen($haslo) < 4){

        $error = "❌ Hasło za krótkie";

    } else {

        /* CZY LOGIN ISTNIEJE */

        $stmt = $conn->prepare("
            SELECT id FROM klienci
            WHERE login = ?
        ");

        $stmt->bind_param("s", $login);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $error = "❌ Login zajęty";

        } else {

            /* HASH HASŁA */

            $hash = password_hash(
                $haslo,
                PASSWORD_DEFAULT
            );

            /* INSERT */

            $stmt = $conn->prepare("
                INSERT INTO klienci
                (login, haslo, role)
                VALUES (?, ?, 'user')
            ");

            $stmt->bind_param(
                "ss",
                $login,
                $hash
            );

            $stmt->execute();

            $success =
                "✅ Konto utworzone";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="pl">

<head>

<meta charset="UTF-8">

<title>Rejestracja</title>

<style>

body{
margin:0;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:#0b1220;
font-family:Arial;
color:white;
}

.box{
width:360px;
background:#111827;
padding:30px;
border-radius:18px;
border:1px solid #1f2937;
animation:fade 0.4s ease;
}

h1{
text-align:center;
margin-bottom:25px;
color:#60a5fa;
}

input{
width:100%;
padding:14px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#1f2937;
color:white;
box-sizing:border-box;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:10px;
font-weight:bold;
cursor:pointer;
margin-top:10px;
}

.registerBtn{
background:#22c55e;
color:white;
}

.backBtn{
background:#374151;
color:white;
}

.error{
background:#7f1d1d;
padding:10px;
border-radius:10px;
margin-bottom:15px;
text-align:center;
}

.success{
background:#14532d;
padding:10px;
border-radius:10px;
margin-bottom:15px;
text-align:center;
}

.login{
margin-top:15px;
text-align:center;
}

.login a{
color:#60a5fa;
text-decoration:none;
}

@keyframes fade{
from{
opacity:0;
transform:translateY(20px);
}
to{
opacity:1;
transform:translateY(0);
}
}

</style>

</head>

<body>

<div class="box">

<h1>
📝 Rejestracja
</h1>

<?php if($error): ?>

<div class="error">

<?= $error ?>

</div>

<?php endif; ?>

<?php if($success): ?>

<div class="success">

<?= $success ?>

</div>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="login"
placeholder="Login"
required
>

<input
type="password"
name="haslo"
placeholder="Hasło"
required
>

<button class="registerBtn">

Utwórz konto

</button>

</form>

<button
class="backBtn"
onclick="location.href='index.php'">

⬅ Powrót do sklepu

</button>

<div class="login">

Masz już konto?

<a href="login.php">

Zaloguj się

</a>

</div>

</div>

</body>
</html>