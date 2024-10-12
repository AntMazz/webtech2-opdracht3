<?php
global $pdo;
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to delete a share.";
    $_SESSION['msg_type'] = "danger";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/setup-database.inc.php';
    $id = $_POST['id'];

    // Check if the logged-in user is the owner of the share
    $stmt = $pdo->prepare("SELECT user_id FROM shares WHERE id = ?");
    $stmt->execute([$id]);
    $share = $stmt->fetch();

    if ($share['user_id'] != $_SESSION['user_id']) {
        $_SESSION['message'] = "You are not authorized to delete this share.";
        $_SESSION['msg_type'] = "danger";
        header("Location: shares.php");
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM shares WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Share deleted successfully!";
    $_SESSION['msg_type'] = "success";
    header("Location: shares.php");
    exit();
}
?>
<form action="delete-share.php" method="post">
    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <button type="submit" class="btn btn-danger">Delete Share</button>
</form>