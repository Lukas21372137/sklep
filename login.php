<?php session_start(); include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body {
            margin:0;
            font-family:Arial;
            background:#0b1220;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            color:white;
        }

        .box {
            width:320px;
            background:#111827;
            padding:24px;
            border-radius:14px;
            border:1px solid #1f2937;
            animation: pop 0.3s ease;
        }

        input {
            width:100%;
            padding:10px;
            margin:8px 0;
            border:none;
            border-radius:8px;
            background:#0f172a;
            color:white;
        }

        button {
            width:100%;
            padding:10px;
            border:none;
            border-radius:8px;
            background:#3b82f6;
            color:white;
            cursor:pointer;
        }

        button:hover {
            background:#2563eb;
        }

        @keyframes pop {
            from {transform:scale(0.9); opacity:0;}
            to {transform:scale(1); opacity:1;}
        }
    </style>
</head>

<body>

<div class="box">

<h2>🔐 Logowanie</h2>

<form method="POST">

<input name="login" placeholder="Login">
<input type="password" name="password" placeholder="Hasło">

<button>Zaloguj</button>

</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST['login'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE login='$login'");
    $u = $res->fetch_assoc();

    if ($u && password_verify($pass, $u['password'])) {

        $_SESSION['user_id'] = $u['id'];
        $_SESSION['login'] = $u['login'];

        header("Location: index.php");
        exit;

    } else {
        echo "<p style='color:red'>❌ Błędne dane</p>";
    }
}
?>

</div>

</body>
</html>