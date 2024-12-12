<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_SESSION['email'] = null;
    $_SESSION['username'] = null;
    $_SESSION['isLogged'] = null;
    $_SESSION['user_id'] = null;

    header("Location: http://localhost:801/RPG-Character-Management-System/index.php");
    exit();
}
