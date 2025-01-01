<?php

require_once 'AppController.php';

class SecurityController extends AppController
{
    public function login()
    {
        if ($this->isGet()) {
            return $this->render("login");
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Walidacja logowania (to można rozszerzyć o sprawdzanie w bazie danych)
        if ($email === 'admin@admin.pl' && $password === 'password') {
            header("Location: /dashboard");
            exit();
        }

        // Jeśli logowanie nie powiedzie się, ponownie renderuj formularz
        return $this->render("login", ['error' => 'Nieprawidłowy e-mail lub hasło.']);
    }
}
