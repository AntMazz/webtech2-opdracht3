<?php
global $pdo;
session_start();
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to update a share.";
    $_SESSION['msg_type'] = "danger";
    header("Location: login.php");
    exit();
}

include 'includes/setup-database.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $link = $_POST['link'];

    // Check if the logged-in user is the owner of the share
    $stmt = $pdo->prepare("SELECT user_id FROM shares WHERE id = ?");
    $stmt->execute([$id]);
    $share = $stmt->fetch();

    if ($share['user_id'] != $_SESSION['user_id']) {
        $_SESSION['message'] = "You are not authorized to update this share.";
        $_SESSION['msg_type'] = "danger";
        header("Location: shares.php");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE shares SET title = ?, body = ?, link = ? WHERE id = ?");
    $stmt->execute([$title, $body, $link, $id]);

    $_SESSION['message'] = "Share updated successfully!";
    $_SESSION['msg_type'] = "success";
    header("Location: shares.php");
    exit();
} else {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM shares WHERE id = ?");
    $stmt->execute([$id]);
    $share = $stmt->fetch();

    // Check if the logged-in user is the owner of the share
    if ($share['user_id'] != $_SESSION['user_id']) {
        $_SESSION['message'] = "You are not authorized to update this share.";
        $_SESSION['msg_type'] = "danger";
        header("Location: shares.php");
        exit();
    }
}
?>
    <h2>Update Share</h2>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
    </div>
<?php endif ?>
    <form action="update-share.php" method="post">
        <input type="hidden" name="id" value="<?php echo $share['id']; ?>">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $share['title']; ?>" required>
        </div>
        <div class="form-group">
            <label for="body">Body:</label>
            <textarea class="form-control" id="body" name="body" required><?php echo $share['body']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="link">Link:</label>
            <input type="url" class="form-control" id="link" name="link" value="<?php echo $share['link']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Share</button>
    </form>
<?php
include 'includes/footer.php';
?>