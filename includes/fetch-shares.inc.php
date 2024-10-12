<?php
function fetchLatestShares($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM shares ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>