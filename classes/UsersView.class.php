<?php
class UsersView extends Dbh {
    public function showUser($name) {
        $sql = "SELECT * FROM users WHERE voornaam = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$name]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            echo "No user found with the name: $name";
        } else {
            echo "User(s) found: ";
        }

        return $results;
    }
}
?>