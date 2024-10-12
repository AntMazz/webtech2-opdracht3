<?php
session_start();
include 'includes/header.php';
include 'includes/setup-database.inc.php';
include 'includes/fetch-shares.inc.php';

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/shareboard.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $shares = fetchLatestShares($pdo);
    if (empty($shares)) {
        echo "<p class='alert alert-warning'>No shares found in the database.</p>";
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $shares = [];
    echo "<p class='alert alert-danger'>Database connection error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

    <div class="jumbotron text-center">
        <h1 class="display-4">Welcome to AM~Blog!</h1>
        <p class="lead">On this blog, you can see posts from other users. To start sharing, click on the register or login button below.</p>
        <hr class="my-4">
        <p>
            <a class="btn btn-primary btn-lg" href="register.php" role="button">Register</a>
            <a class="btn btn-secondary btn-lg" href="login.php" role="button">Login</a>
        </p>
    </div>

    <h2 class="my-4">Latest Shares</h2>
<?php if (!empty($shares)): ?>
    <div class="row">
        <?php foreach ($shares as $share): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($share['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($share['body']); ?></p>
                        <p class="card-text"><small class="text-muted">Posted on: <?php echo htmlspecialchars($share['share_date']); ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="alert alert-info">No shares available.</p>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>