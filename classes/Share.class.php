<?php
class Share {
    private $db;

    public function __construct() {
        $this->db = new PDO('sqlite:shareboard.db');
    }

    public function createShare($userId, $title, $body, $link) {
        $stmt = $this->db->prepare("INSERT INTO shares (user_id, title, body, link) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $body, $link]);
    }

    public function getShares() {
        $stmt = $this->db->query("SELECT * FROM shares");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
