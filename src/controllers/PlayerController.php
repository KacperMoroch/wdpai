<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';


class PlayerController extends AppController 
{
    public function startGame() {
        

        // Inicjalizowanie sesji, jeśli nie zostało to zrobione wcześniej
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Sprawdzenie, czy użytkownik jest zalogowany
        $this->checkLogin(); // Ta metoda sprawdza sesję i przekierowuje, jeśli użytkownik nie jest zalogowany


        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Nie jesteś zalogowany!";
            header("Location: /login");
            exit();
        }
        
        echo "Użytkownik ID: " . $_SESSION['user_id'];
        // Usuwanie stanu 'game_over', jeśli istnieje
        unset($_SESSION['game_over']);
        
        // Połączenie z bazą danych
        $db = new DatabaseConnector();
        $conn = $db->connect();
    
        // Losowanie piłkarza
        $stmt = $conn->prepare("
            SELECT p.*, 
                   c.name AS country, 
                   l.name AS league, 
                   cl.name AS club, 
                   pos.name AS position, 
                   a.value AS age, 
                   sn.number AS shirt_number
            FROM players p
            JOIN countries c ON p.country_id = c.id
            JOIN leagues l ON p.league_id = l.id
            JOIN clubs cl ON p.club_id = cl.id
            JOIN positions pos ON p.position_id = pos.id
            JOIN ages a ON p.age_id = a.id
            JOIN shirt_numbers sn ON p.shirt_number_id = sn.id
            ORDER BY RANDOM() LIMIT 1
        ");
        $stmt->execute();
        $player = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Sprawdzanie, czy udało się wylosować piłkarza
        if (!$player) {
            die("Błąd: Nie udało się wylosować piłkarza."); // Możesz to zamienić na bardziej elegancki komunikat.
        }

        // Przechowywanie danych losowego piłkarza w sesji
        $_SESSION['target_player'] = $player;
    
        // Załadowanie widoku "start-game"
        $this->render("FindPlayer");
    }



    
    public function checkGuess() {
        header('Content-Type: application/json');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    
        // Odczytanie danych JSON z ciała żądania
        $inputData = json_decode(file_get_contents('php://input'), true);
    
        // Walidacja danych wejściowych
        if (!isset($inputData['player_name']) || empty(trim($inputData['player_name']))) {
            echo json_encode(['error' => 'Brak nazwy piłkarza lub nieprawidłowe dane.']);
            return;
        }
    
        $inputName = htmlspecialchars(trim($inputData['player_name']), ENT_QUOTES, 'UTF-8');
    
        // Sprawdzenie, czy użytkownik jest zalogowany
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Użytkownik nie jest zalogowany.']);
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d'); // Aktualna data
    
        // Sprawdzenie liczby prób użytkownika dzisiaj
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT guess_number, guessed_correctly FROM user_guess_log WHERE id_user = :id_user AND guess_date = :guess_date");
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':guess_date', $today);
        $stmt->execute();
        $userGuessLog = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Jeśli użytkownik zgadł już poprawnie, blokujemy dalsze próby
        if (!empty($userGuessLog) && $userGuessLog['guessed_correctly']) {
            echo json_encode(['error' => 'Już zgadłeś dzisiaj piłkarza. Spróbuj ponownie jutro.']);
            return;
        }
    
        // Sprawdzenie liczby prób
        $guessNumber = $userGuessLog['guess_number'] ?? 0;
    
        // Jeśli użytkownik osiągnął limit prób, blokujemy zgadywanie
        if ($guessNumber >= 5) {
            echo json_encode(['error' => 'Już nie możesz dzisiaj zgadywać, nie masz prób. Spróbuj ponownie jutro.']);
            return;
        }
    
        // Pobranie piłkarza z bazy danych
        $guessedPlayer = $this->getPlayerByName($inputName);
    
        if ($guessedPlayer) {
            $targetPlayer = $_SESSION['target_player'] ?? null;
            if ($targetPlayer === null) {
                echo json_encode(['error' => 'Gra nie została jeszcze rozpoczęta.']);
                return;
            }
    
            $matches = [
                'country' => $this->getCountryName($guessedPlayer['country_id']) === $targetPlayer['country'],
                'league' => $this->getLeagueName($guessedPlayer['league_id']) === $targetPlayer['league'],
                'club' => $this->getClubName($guessedPlayer['club_id']) === $targetPlayer['club'],
                'position' => $this->getPositionName($guessedPlayer['position_id']) === $targetPlayer['position'],
                'age' => $this->getAge($guessedPlayer['age_id']) === $targetPlayer['age'],
                'shirt_number' => $this->getShirtNumber($guessedPlayer['shirt_number_id']) === $targetPlayer['shirt_number']
            ];
    
            $response = [
                'found' => true,
                'name' => $guessedPlayer['name'],
                'country' => $this->getCountryName($guessedPlayer['country_id']),
                'league' => $this->getLeagueName($guessedPlayer['league_id']),
                'club' => $this->getClubName($guessedPlayer['club_id']),
                'position' => $this->getPositionName($guessedPlayer['position_id']),
                'age' => $this->getAge($guessedPlayer['age_id']),
                'shirt_number' => $this->getShirtNumber($guessedPlayer['shirt_number_id']),
                'matches' => $matches,
                'game_over' => array_reduce($matches, fn($carry, $item) => $carry && $item, true)
            ];
    
            if ($response['game_over']) {
                // Jeśli zgadnięto poprawnie
                if ($userGuessLog) {
                    $stmt = $conn->prepare("UPDATE user_guess_log SET guess_number = guess_number + 1, guessed_correctly = TRUE WHERE id_user = :id_user AND guess_date = :guess_date");
                } else {
                    $stmt = $conn->prepare("INSERT INTO user_guess_log (id_user, guess_date, guess_number, guessed_correctly) VALUES (:id_user, :guess_date, 1, TRUE)");
                }
                $_SESSION['game_over'] = true;
            } else {
                // Jeśli nie zgadnięto poprawnie
                if ($userGuessLog) {
                    $stmt = $conn->prepare("UPDATE user_guess_log SET guess_number = guess_number + 1 WHERE id_user = :id_user AND guess_date = :guess_date");
                } else {
                    $stmt = $conn->prepare("INSERT INTO user_guess_log (id_user, guess_date, guess_number, guessed_correctly) VALUES (:id_user, :guess_date, 1, FALSE)");
                }
            }
    
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':guess_date', $today);
            $stmt->execute();
    
            echo json_encode($response);
        } else {
            // Jeśli piłkarza nie znaleziono
            if ($userGuessLog) {
                $stmt = $conn->prepare("UPDATE user_guess_log SET guess_number = guess_number + 1 WHERE id_user = :id_user AND guess_date = :guess_date");
            } else {
                $stmt = $conn->prepare("INSERT INTO user_guess_log (id_user, guess_date, guess_number, guessed_correctly) VALUES (:id_user, :guess_date, 1, FALSE)");
            }
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':guess_date', $today);
            $stmt->execute();
    
            echo json_encode(['error' => 'Nie znaleziono piłkarza. Spróbuj ponownie.']);
        }
    }
    




    public function getPlayers() {
        header('Content-Type: application/json');
    
        // Sprawdź, czy przekazano zapytanie
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        if (empty($query)) {
            echo json_encode(['players' => []]);
            return;
        }
    
        // Połącz się z bazą danych
        $db = new DatabaseConnector();
        $conn = $db->connect();
    
        // Wyszukaj graczy, którzy pasują do zapytania (np. imię lub nazwisko zaczyna się od frazy)
        $stmt = $conn->prepare("SELECT name FROM players WHERE name LIKE :query LIMIT 10");
        $searchQuery = $query . '%'; // Dopasowanie do początku frazy
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
    
        $players = $stmt->fetchAll(PDO::FETCH_COLUMN); // Pobierz tylko nazwy
        echo json_encode(['players' => $players]);
    }





    
    // Pomocnicza funkcja do pobrania piłkarza po nazwisku
    private function getPlayerByName($name) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT * FROM players WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch();
}
    // Pomocnicze funkcje do pobierania nazw
    private function getCountryName($countryId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT name FROM countries WHERE id = :id");
        $stmt->bindParam(':id', $countryId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getLeagueName($leagueId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT name FROM leagues WHERE id = :id");
        $stmt->bindParam(':id', $leagueId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getClubName($clubId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT name FROM clubs WHERE id = :id");
        $stmt->bindParam(':id', $clubId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getPositionName($positionId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT name FROM positions WHERE id = :id");
        $stmt->bindParam(':id', $positionId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getAge($ageId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT value FROM ages WHERE id = :id");
        $stmt->bindParam(':id', $ageId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getShirtNumber($shirtNumberId) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT number FROM shirt_numbers WHERE id = :id");
        $stmt->bindParam(':id', $shirtNumberId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
