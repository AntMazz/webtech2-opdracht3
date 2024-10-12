<?php
class Dbh {
    private $host = 'localhost';
    private $user = 'root';
    private $pwd = '';
    private $dbName = 'shareboard';

    protected function connect() {
        try {
            $dsn = 'sqlite:' . __DIR__ . '/../shareboard.db';
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            die();
        }
    }
}
?>