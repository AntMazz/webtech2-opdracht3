<?php
global $pdo;
session_start();
include 'includes/header.php';
include 'includes/setup-database.inc.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to view your shares.";
    $_SESSION['msg_type'] = "danger";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM shares WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$shares = $stmt->fetchAll();
?>
    <h2>My Shares</h2>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
    </div>
<?php endif ?>
<?php foreach ($shares as $share): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $share['title']; ?></h5>
            <p class="card-text"><?php echo $share['body']; ?></p>
            <a href="<?php echo $share['link']; ?>" class="btn btn-primary">Read More</a>
            <a href="update-share.php?id=<?php echo $share['id']; ?>" class="btn btn-warning">Update</a>
            <form action="delete-share.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $share['id']; ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php
include 'includes/footer.php';
?>