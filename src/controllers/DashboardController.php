<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/PlayerRepository.php';

class DashboardController extends AppController
{
    private UserRepository $userRepository;
    private PlayerRepository $playerRepository;
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userRepository = new UserRepository();
        $this->playerRepository = new PlayerRepository();
    }
    public function dashboard() {


        $userId = $this->getLoggedUserId();
        if (!$userId) {
            header("Location: /login");
            exit();
        }

        $users = $this->userRepository->getAllUsers();


        $this->render("dashboard", ['name' => "Kacper", "users" => $users]);
    }
    public function logout() {
    
        // Niszczymy wszystkie dane sesji
        session_unset(); // Usuń zmienne sesji
        session_destroy(); // Zniszcz sesję
    
        // Przekierowujemy użytkownika na stronę logowania
        header("Location: /login");
        exit(); // Zatrzymuje dalsze wykonywanie skryptu
    }

    public function profile() {
        $userId = $this->getLoggedUserId();
        if (!$userId) {
            header("Location: /login");
            exit();
        }
    
        $user = $this->userRepository->getUserById($userId);
    
        if (!$user) {
            die("Nie znaleziono użytkownika.");
        }
    
        // Liczymy punkty z dwóch tabel
        $pointsFromUserGuessLog = $this->userRepository->countPointsFromUserGuessLog($userId);
        $pointsFromUserGuessLogTransfer = $this->userRepository->countPointsFromUserGuessLogTransfer($userId);
    
        // Łączna liczba punktów
        $totalPoints = $pointsFromUserGuessLog + $pointsFromUserGuessLogTransfer;
    
        // Przekzujemy dane do widoku
        $this->render('profile', [
            'user' => $user,
            'pointsFromUserGuessLog' => $pointsFromUserGuessLog,
            'pointsFromUserGuessLogTransfer' => $pointsFromUserGuessLogTransfer,
            'totalPoints' => $totalPoints
        ]);
    }
    public function settings($data = []) {

    
        $userId = $this->getLoggedUserId();
        if (!$userId) {
            header("Location: /login");
            exit();
        }

        $user = $this->userRepository->getUserSettings($userId);
    
        // Nadpisujemy dane, jeśli są przekazane w $data
        if (isset($data['user'])) {
            $user = array_merge($user, $data['user']);
        }
    
        $this->render('settings', array_merge($data, ['user' => $user]));
    }
    public function deleteAccount() {

    
        $userId = $this->getLoggedUserId();
        if (!$userId) {
            header("Location: /login");
            exit();
        }
        if ($this->userRepository->deleteUser($userId)) {
            session_unset();
            session_destroy();
            header("Location: /login");
            exit();
        } else {
            $_SESSION['error_message'] = 'Wystąpił błąd podczas usuwania konta.';
            header("Location: /settings");
            exit();
        }
    }
    public function updateAccount() {

        $userId = $this->getLoggedUserId();
        if (!$userId) {
            header("Location: /login");
            exit();
        }
        $connector = new DatabaseConnector();
        $db = $connector->connect();
    
        $newLogin = $_POST['login'];
        $newEmail = $_POST['email'];
    
        // Walidacja danych
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->settings(['error' => 'Podany e-mail jest niepoprawny!']);
        }
    
        if (strlen($newLogin) < 3) {
            return $this->settings(['error' => 'Login musi mieć co najmniej 3 znaki!']);
        }
    
        try {
            // Sprawdzamy, czy login już istnieje
            $stmt = $db->prepare('SELECT COUNT(*) FROM public.user_account WHERE login = :login AND id_user != :userId');
            $stmt->execute(['login' => $newLogin, 'userId' => $userId]);
            if ($stmt->fetchColumn() > 0) {
                return $this->settings(['error' => 'Podany login jest już zajęty!']);
            }
    
            // Sprawdzamy, czy e-mail już istnieje
            $stmt = $db->prepare('SELECT COUNT(*) FROM public.user_account WHERE email = :email AND id_user != :userId');
            $stmt->execute(['email' => $newEmail, 'userId' => $userId]);
            if ($stmt->fetchColumn() > 0) {
                return $this->settings(['error' => 'Podany e-mail jest już zajęty!']);
            }
    
            // Aktualizacja danych
            $stmt = $db->prepare('UPDATE public.user_account SET login = :login, email = :email WHERE id_user = :userId');
            $stmt->execute([
                'login' => $newLogin,
                'email' => $newEmail,
                'userId' => $userId
            ]);
    
            return $this->render("settings", ['success' => 'Dane zostały pomyślnie zaktualizowane!', 'user' => ['login' => $newLogin, 'email' => $newEmail]]);
        } catch (Exception $e) {
            return $this->settings(['error' => 'Wystąpił błąd podczas aktualizacji danych!']);
        }
    }
    private function getLoggedUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

}