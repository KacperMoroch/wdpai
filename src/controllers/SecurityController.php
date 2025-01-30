<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        // Włączenie buforowania wyjścia
        ob_start();

        // Rozpoczynamy sesję na początku
        session_start();

        // Sprawdzamy, czy użytkownik jest już zalogowany
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit();  // Zakończ wykonywanie skryptu
        }

        // Sprawdzamy, czy to żądanie GET - jeśli tak, wyświetl formularz logowania
        if ($this->isGet()) {
            return $this->render("login");
        }

        // Pobieramy dane z formularza
        $emailOrLogin = $_POST['email']; // Może być email lub login
        $password = $_POST['password'];

        // Pobieramy użytkownika z bazy danych
        $user = $this->userRepository->getUserByEmailOrLogin($emailOrLogin);

        // Upewniamy się, że użytkownik istnieje
        if (!$user) {
            return $this->render("login", ['error' => 'Użytkownik nie został znaleziony.']);
        }

        // Użyj password_verify() aby sprawdzić, czy wprowadzone hasło jest poprawne
        if (empty($user->getPassword()) || !password_verify($password, $user->getPassword())) {
            return $this->render("login", ['error' => 'Nieprawidłowy e-mail/login lub hasło.']);
        }

        // Jeśli logowanie się powiedzie, ustaw dane sesji
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role_id'] = $user->getRoleId(); 

        if ($user->getRoleId() == 2) { // 2 = Admin
            header("Location: /admin");
        } else {
            header("Location: /dashboard");
        }

        // Spuszczenie buforowania i wysłanie nagłówków
        ob_end_flush(); // Zakończenie buforowania wyjścia

        exit(); 
    }
}
