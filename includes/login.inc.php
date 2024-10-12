<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'class-autoload.inc.php';
    include 'setup-database.inc.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../shareboard.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Set user_id in session
            $_SESSION['message'] = "Login successful.";
            $_SESSION['msg_type'] = "success";
            header("Location: ../index.php"); // Redirect to a protected page
            exit();
        } else {
            $_SESSION['message'] = "Invalid email or password.";
            $_SESSION['msg_type'] = "danger";
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: ../login.php");
    exit();
} else {
    header("Location: ../login.php");
    exit();
}