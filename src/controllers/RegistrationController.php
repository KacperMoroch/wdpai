<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class RegistrationController extends AppController {
    public function register() {
        // Sprawdzamy, czy to zapytanie GET
        if ($this->isGet()) {
            return $this->render("register");
        }

        // Zbieramy dane z formularza
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Walidacja danych
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render("register", ['error' => 'Podany e-mail jest niepoprawny!']);
        }

        if (!preg_match('/^\d{9}$/', $phone)) {
            return $this->render("register", ['error' => 'Numer telefonu musi zawierać dokładnie 9 cyfr!']);
        }

        if (strlen($password) < 6 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/\d/', $password)) {
            return $this->render("register", ['error' => 'Hasło musi mieć co najmniej 6 znaków, zawierać jedną dużą literę i cyfrę!']);
        }

        if ($password !== $confirm_password) {
            return $this->render("register", ['error' => 'Hasła nie pasują do siebie!']);
        }

        $userRepository = new UserRepository();

        // Sprawdzamy, czy użytkownik o podanym emailu już istnieje
        $existingEmailUser = $userRepository->getUserByEmailOrLogin($email);
        if ($existingEmailUser) {
            return $this->render("register", ['error' => 'Podany e-mail jest już zajęty!']);
        }

        // Sprawdzamy, czy użytkownik o podanym loginie już istnieje
        $existingLoginUser = $userRepository->getUserByEmailOrLogin($nickname);
        if ($existingLoginUser) {
            return $this->render("register", ['error' => 'Podany login jest już zajęty!']);
        }

        // Szyfrowanie hasła
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Tworzymy nowego użytkownika
        $user = new User(
            0, // ID
            $email,
            $nickname, 
            'Nieznane', // Tymczasowo "Nieznane" jako nazwisko
            null, // Avatar jest opcjonalny
            $hashed_password,
            1, // Domyślnie przypisujemy rolę "User" "1"
            $nickname, // Nickname
            'User', // Rolename
            null // CreatedAt 
        );

        try {
            // Dodajemy użytkownika do bazy
            $userRepository->addUser($user);

            // Przekierowujemy po udanej rejestracji
            header('Location: /login');
            exit();
        } catch (Exception $e) {
            // Logujemy błąd
            $logFile = __DIR__ . '/../../logs/error_log.txt';
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        
            // Wyświetlamy błąd użytkownikowi
            return $this->render("register", ['error' => 'Wystąpił błąd podczas rejestracji.']);
        }
    }
}
