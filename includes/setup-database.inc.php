<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../shareboard.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the created_at column exists
    $result = $pdo->query("PRAGMA table_info(shares)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    $columnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] == 'created_at') {
            $columnExists = true;
            break;
        }
    }

    // Add the created_at column if it does not exist
    if (!$columnExists) {
        $pdo->exec("ALTER TABLE shares ADD COLUMN created_at TIMESTAMP");
        // Update existing rows with the current timestamp
        $pdo->exec("UPDATE shares SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL");
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Database connection error: " . $e->getMessage());
}
?>