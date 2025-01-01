<?php

require_once 'AppController.php';

class RegistrationController extends AppController {
    public function register() {
        if ($this->isGet()) {
            // Renderujemy stronę rejestracji
            return $this->render("register");
        }

        // Zbieramy dane z formularza
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Walidacja
        if ($password === $confirm_password) {
            // TODO: Zapisz użytkownika do bazy danych

            // Przekierowanie po udanej rejestracji
            header('Location: /login');
            exit();
        } else {
            // Jeśli hasła nie pasują
            return $this->render("register", ['error' => 'Hasła nie pasują do siebie!']);
        }
    }
}
