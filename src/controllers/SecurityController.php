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
        // Sprawdź, czy to żądanie GET - jeśli tak, wyświetl formularz logowania
        if ($this->isGet()) {
            return $this->render("login");
        }
    
        $emailOrLogin = $_POST['email']; // Może być email lub login
        $password = $_POST['password'];
    
        // Pobierz użytkownika z bazy danych
        $user = $this->userRepository->getUserByEmailOrLogin($emailOrLogin);
    
        // Upewnij się, że użytkownik istnieje
        if (!$user) {
            return $this->render("login", ['error' => 'Użytkownik nie został znaleziony.']);
        }
    
        // Użyj password_verify() aby sprawdzić, czy wprowadzone hasło jest poprawne
        if (empty($user->getPassword()) || !password_verify($password, $user->getPassword())) {
            return $this->render("login", ['error' => 'Nieprawidłowy e-mail/login lub hasło.']);
        }
    
        // Jeśli logowanie się powiedzie, przekieruj do dashboard
        header("Location: /dashboard");
        exit();  // Pamiętaj, aby zakończyć skrypt po przekierowaniu, żeby uniknąć dalszego wykonywania kodu
    }
    
    
    
}
