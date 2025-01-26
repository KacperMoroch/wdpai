<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
//require_once __DIR__.'/../models/User.php';
//require_once __DIR__.'/../repository/UserRepository.php';
class DashboardController extends AppController
{
    /*
<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class DashboardController extends AppController {

    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function dashboard() {
        $this->render("dashboard", ['name' => "Adrian", "users" => $this->userRepository->getUsers()]);
    }

    public function userEndpoint()
    {
    // bylo na zajeciach 16.01
    //trzeba sobie zserializowac i dodac json'a
    }

    public function deleteUserEndpoint()
    {
    //bylo na zajeciach 16.01
    }

    public function addUser() {

        if($this->isPost()) {
            // TODO $_POST["name"], $_POST["email"]
            
            var_dump($_POST);
            $this->userRepository->addUser(
                new User(
                    $_POST["name"],
                    $_POST["email"],
                    //... itd
                )
            );
            $this->render("dashboard", ['name' => "Adrian", "users" => $this->userRepository->getUsers()]);
        }

        $this->render("add-user");
    }
}


    */
    public function dashboard() {
        // Rozpocznij sesję
        // Inicjalizowanie sesji, jeśli nie zostało to zrobione wcześniej
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Sprawdź, czy użytkownik jest zalogowany
        if (!isset($_SESSION['user_id'])) {
            // Zamiast echo, zapisz komunikat w sesji lub przekieruj
            $_SESSION['error_message'] = "Nie jesteś zalogowany!";
            header("Location: /login");
            exit(); // Zatrzymuje dalsze wykonywanie skryptu
        }

        // Jeśli użytkownik jest zalogowany, wyświetl dashboard
        /*echo "Witaj na dashboardzie, użytkowniku o ID: " . $_SESSION['user_id'];*/

        $this->checkLogin();
        $connector = new DatabaseConnector();
        $stmt = $connector->connect()->prepare('SELECT * FROM public.user_account');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // TODO return object, you can yse FETCH_CLASS to avoid iterator wit foreach
        /*
        #usersResponse = []
        foreach($users as $user) {
            $usersResponse[] = new User(
                $user['id'],
                $user['email'],
                $user['name'],
                $user['surname'],
                $user['avatar_url']
            );
        }
        
        */
        $this->render("dashboard", ['name' => "Adrian", "users" => $users]);
    }


    public function logout() {
        // Rozpocznij sesję, jeśli jeszcze nie została rozpoczęta
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Zniszcz wszystkie dane sesji
        session_unset(); // Usuń zmienne sesji
        session_destroy(); // Zniszcz sesję
    
        // Przekieruj użytkownika na stronę logowania
        header("Location: /login");
        exit(); // Zatrzymuje dalsze wykonywanie skryptu
    }

    public function profile() {
        // Rozpocznij sesję, jeśli jeszcze nie została rozpoczęta
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Sprawdź, czy użytkownik jest zalogowany
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    
        $userId = $_SESSION['user_id'];
        $connector = new DatabaseConnector();
        $db = $connector->connect();
    
        // Pobierz dane użytkownika
        $stmt = $db->prepare('SELECT login, created_at FROM user_account WHERE id_user = :userId');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            die("Nie znaleziono użytkownika.");
        }
    
        // Oblicz liczbę punktów
        $stmt = $db->prepare('SELECT COUNT(*) AS correct_guesses FROM user_guess_log WHERE id_user = :userId AND guessed_correctly = true');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $points = $result['correct_guesses'] * 100;
    
        // Przekaż dane do widoku
        $this->render('profile', [
            'user' => $user,
            'points' => $points
        ]);
    }
    



}