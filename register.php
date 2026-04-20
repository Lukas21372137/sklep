<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>🆕 Rejestracja</h2>

<form method="POST">

    <label>Login</label>
    <input type="text" name="login" required>

    <label>Imię</label>
    <input type="text" name="imie" required>

    <label>Nazwisko</label>
    <input type="text" name="nazwisko" required>

    <label>Hasło</label>
    <input type="password" name="password" required>

    <button type="submit">Utwórz konto</button>

</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST['login'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $haslo = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // sprawdzenie czy login istnieje
    $check = $conn->query("SELECT id FROM users WHERE login='$login'");

    if ($check->num_rows > 0) {
        echo "❌ Login już istnieje";
    } else {

        $conn->query("
            INSERT INTO users (login, password, imie, nazwisko)
            VALUES ('$login', '$haslo', '$imie', '$nazwisko')
        ");

        echo "✅ Konto utworzone. Możesz się zalogować.";
    }
}
?>

<br><br>
<a href="login.php">⬅ Powrót do logowania</a>

</div>

</body>
</html>