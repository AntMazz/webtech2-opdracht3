// includes/shares.inc.php
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to add a share.";
    $_SESSION['msg_type'] = "danger";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'class-autoload.inc.php';
    include 'setup-database.inc.php';

    $title = $_POST['title'];
    $body = $_POST['body'];

    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../shareboard.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the new share
        $sql = "INSERT INTO shares (title, body, user_id) VALUES (:title, :body, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title' => $title, 'body' => $body, 'user_id' => $_SESSION['user_id']]);
        $_SESSION['message'] = "Share added successfully.";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: ../shares.php");
    exit();
} else {
    header("Location: ../shares.php");
    exit();
}
?>