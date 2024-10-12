<?php
session_start();
include 'includes/header.php';
?>
    <h2>Login</h2>
<?php
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
    </div>
<?php endif ?>
    <form action="includes/login.inc.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Wachtwoord:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
<?php
include 'includes/footer.php';
?>