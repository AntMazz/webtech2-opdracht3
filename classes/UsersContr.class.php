<?php
class UsersContr extends UsersView {
    public function createUser($voornaam, $achternaam, $email, $password) {
        $user = new User();
        return $user->register($voornaam, $achternaam, $email, $password);
    }

    public function loginUser($email, $password) {
        $user = new User();
        return $user->login($email, $password);
    }
}
?>