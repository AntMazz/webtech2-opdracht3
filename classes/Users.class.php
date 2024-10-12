<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new PDO('sqlite:shareboard.db');
    }

    public function register($voornaam, $achternaam, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (voornaam, achternaam, email, password) VALUES (?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$voornaam, $achternaam, $email, $hashedPassword]);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
}
?>