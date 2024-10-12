<?php
session_start();
include 'includes/header.php';
include 'includes/setup-database.inc.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to create a share.";
    $_SESSION['msg_type'] = "danger";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $link = $_POST['link'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO shares (title, body, link, user_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$title, $body, $link, $user_id])) {
        $_SESSION['message'] = "Share created successfully.";
        $_SESSION['msg_type'] = "success";
        header("Location: my-share.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to create share.";
        $_SESSION['msg_type'] = "danger";
    }
}
?>

<h2>Create a New Share</h2>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
    </div>
<?php endif ?>
<form action="create-share.php" method="post">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="body">Body:</label>
        <textarea class="form-control" id="body" name="body" required></textarea>
    </div>
    <div class="form-group">
        <label for="link">Link:</label>
        <input type="url" class="form-control" id="link" name="link" required>
    </div>
    <button type="submit" class="btn btn-primary">Create Share</button>
</form>

<?php
include 'includes/footer.php';
?>
