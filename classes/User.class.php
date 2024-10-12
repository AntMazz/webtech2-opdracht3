<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new PDO('sqlite:shareboard.db');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function executeWithRetry($sql, $params = [], $retries = 5) {
        $attempt = 0;
        while ($attempt < $retries) {
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                return $stmt;
            } catch (PDOException $e) {
                if ($e->getCode() == 'HY000' && strpos($e->getMessage(), 'database is locked') !== false) {
                    $attempt++;
                    usleep(500000); // Wait for 0.5 seconds before retrying
                } else {
                    throw $e;
                }
            }
        }
        throw new Exception('Database is locked after multiple attempts');
    }

    public function register($voornaam, $achternaam, $email, $password) {
        $sql = "INSERT INTO users (voornaam, achternaam, email, password) VALUES (?, ?, ?, ?)";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->executeWithRetry($sql, [$voornaam, $achternaam, $email, $hashedPassword]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->executeWithRetry($sql, [$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
}
?>