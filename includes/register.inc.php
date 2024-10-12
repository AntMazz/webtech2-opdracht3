<?php
session_start();
include 'setup-database.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (voornaam, achternaam, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$voornaam, $achternaam, $email, $password])) {
        $user_id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $email; // Assuming email is used as the username
        header("Location: ../my-share.php");
        exit();
    } else {
        $_SESSION['message'] = "Registration failed.";
        $_SESSION['msg_type'] = "danger";
        header("Location: ../register.php");
        exit();
    }
}
?>