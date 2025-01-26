<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        // Załaduj repozytorium użytkownika
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        // Włączenie buforowania wyjścia
        ob_start();

        // Rozpocznij sesję na początku
        session_start();

        // Sprawdź, czy użytkownik jest już zalogowany
        if (isset($_SESSION['user_id'])) {
            // Debugowanie sesji
            var_dump($_SESSION);  // Zobacz, czy sesja jest ustawiona
            exit();  // Zatrzymuje dalsze wykonywanie kodu

            // Jeśli użytkownik jest już zalogowany, przekieruj na dashboard
            header("Location: /dashboard");
            exit();  // Zakończ wykonywanie skryptu
        }

        // Sprawdź, czy to żądanie GET - jeśli tak, wyświetl formularz logowania
        if ($this->isGet()) {
            return $this->render("login");
        }

        // Pobierz dane z formularza
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

        // Jeśli logowanie się powiedzie, ustaw dane sesji
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role_id'] = $user->getRoleId(); // Zakładamy, że User ma metodę getRoleId()

        if ($user->getRoleId() == 2) { // 2 = Admin
            header("Location: /admin");
        } else {
            header("Location: /dashboard");
        }

        // Przekierowanie na stronę dashboard po poprawnym logowaniu
        /*header("Location: /dashboard");*/

        // Spuszczenie buforowania i wysłanie nagłówków
        ob_end_flush(); // Zakończenie buforowania wyjścia

        exit();  // Pamiętaj, aby zakończyć skrypt po przekierowaniu
    }
}
