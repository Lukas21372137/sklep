<?php
session_start();
include 'db.php';

/* opcjonalnie — tylko dla zalogowanych */

if(!isset($_SESSION['user_id'])){
    die("Brak dostępu");
}

/* usuwanie historii */

$conn->query("DELETE FROM zakupy");

/* reset AUTO_INCREMENT */

$conn->query("ALTER TABLE zakupy AUTO_INCREMENT = 1");

header("Location: historia.php");
exit;