<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__ . '/../models/Player.php';
require_once __DIR__.'/../repository/PlayerRepository.php';

class PlayerController extends AppController 
{
    private PlayerRepository $playerRepository;

    public function __construct() {
        $this->playerRepository = new PlayerRepository();

    }    private function getDbConnection() {
        $db = new DatabaseConnector();
        return $db->connect();
    }
    private function checkUserLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->checkLogin();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Nie jesteś zalogowany!";
            header("Location: /login");
            exit();
        }

        return $_SESSION['user_id'];
    }
    private function getPlayerDetails($playerId) {
        $conn = $this->getDbConnection();
        $stmt = $conn->prepare("
            SELECT p.*, c.name AS country, l.name AS league, cl.name AS club, pos.name AS position, 
                   a.value AS age, sn.number AS shirt_number
            FROM players p
            JOIN countries c ON p.country_id = c.id
            JOIN leagues l ON p.league_id = l.id
            JOIN clubs cl ON p.club_id = cl.id
            JOIN positions pos ON p.position_id = pos.id
            JOIN ages a ON p.age_id = a.id
            JOIN shirt_numbers sn ON p.shirt_number_id = sn.id
            WHERE p.id = :player_id
        ");
        $stmt->bindParam(':player_id', $playerId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function startGame() {
        $userId = $this->checkUserLoggedIn();
        $today = date('Y-m-d');

        $conn = (new DatabaseConnector())->connect();
        $stmt = $conn->prepare("SELECT player_id FROM user_player_assignment WHERE user_id = :user_id AND assignment_date = :today");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assignment) {
            $player = $this->playerRepository->getPlayerById($assignment['player_id']);
        } else {
            $player = $this->playerRepository->getRandomPlayer();
            if (!$player) {
                die("Błąd: Nie udało się wylosować piłkarza.");
            }
            $player_id = $player->getId();
            $stmt = $conn->prepare("
                INSERT INTO user_player_assignment (user_id, player_id, assignment_date) 
                VALUES (:user_id, :player_id, :assignment_date)
            ");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':player_id', $player_id);
            $stmt->bindParam(':assignment_date', $today);
            $stmt->execute();
        }

        $_SESSION['target_player'] = $player;

        $stmt = $conn->prepare("SELECT guess_number FROM user_guess_log WHERE id_user = :user_id AND guess_date = :today");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $userGuessLog = $stmt->fetch(PDO::FETCH_ASSOC);
        $remainingAttempts = 5 - ($userGuessLog['guess_number'] ?? 0);

        $this->render("FindPlayer", ['remainingAttempts' => $remainingAttempts]);
    }
    public function checkGuess() {
        header('Content-Type: application/json');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $inputData = json_decode(file_get_contents('php://input'), true);
        if (!isset($inputData['player_name']) || empty(trim($inputData['player_name']))) {
            echo json_encode(['error' => 'Brak nazwy piłkarza lub nieprawidłowe dane.']);
            return;
        }

        $inputName = htmlspecialchars(trim($inputData['player_name']), ENT_QUOTES, 'UTF-8');
        $userId = $this->checkUserLoggedIn();
        $today = date('Y-m-d');

        $conn = $this->getDbConnection();

        $stmt = $conn->prepare("SELECT guess_number, guessed_correctly FROM user_guess_log WHERE id_user = :id_user AND guess_date = :guess_date");
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':guess_date', $today);
        $stmt->execute();
        $userGuessLog = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($userGuessLog) && $userGuessLog['guessed_correctly']) {
            $targetPlayer = $_SESSION['target_player'] ?? null;
            $playerName = $targetPlayer ? $targetPlayer->getName() : 'Nieznany piłkarz';
            echo json_encode([
                'error' => 'Już zgadłeś dzisiaj piłkarza. Spróbuj ponownie jutro. Twoim piłkarzem do zgadnięcia był: <span style="color: gold;">' . $playerName . '</span>'
            ]);
            return;
        }

        $guessNumber = $userGuessLog['guess_number'] ?? 0;

       
        if ($guessNumber >= 5) {
            $targetPlayer = $_SESSION['target_player'] ?? null;
            $playerName = $targetPlayer ? $targetPlayer->getName() : 'Nieznany piłkarz';
            echo json_encode([
                'error' => 'Już nie możesz dzisiaj zgadywać, nie masz prób. Spróbuj ponownie jutro. Twoim piłkarzem do zgadnięcia był: <span style="color: gold;">' . $playerName . '</span>'
            ]);
            return;
        }

        $guessedPlayer = $this->getPlayerByName($inputName);

        if ($guessedPlayer) {
            $targetPlayer = $_SESSION['target_player'] ?? null;
            if ($targetPlayer === null) {
                echo json_encode(['error' => 'Gra nie została jeszcze rozpoczęta.']);
                return;
            }

            $matches = $this->checkMatches($guessedPlayer, $targetPlayer);

            $response = $this->prepareGuessResponse($guessedPlayer, $matches, $guessNumber, $userGuessLog);

            echo json_encode($response);
        } else {
  
            $this->updateGuessLog($userGuessLog, false);
            $maxAttempts = 5;
            $remainingAttempts = $maxAttempts - $guessNumber - 1;
            echo json_encode(['error' => 'Nie znaleziono piłkarza. Spróbuj ponownie.','remaining_attempts' => $remainingAttempts]);
        }
    }
    private function checkMatches(Player $guessedPlayer, Player $targetPlayer) {
        return [
            'country' => $guessedPlayer->getCountry() === $targetPlayer->getCountry(),
            'league' => $guessedPlayer->getLeague() === $targetPlayer->getLeague(),
            'club' => $guessedPlayer->getClub() === $targetPlayer->getClub(),
            'position' => $guessedPlayer->getPosition() === $targetPlayer->getPosition(),
            'age' => $guessedPlayer->getAge() === $targetPlayer->getAge(),
            'shirt_number' => $guessedPlayer->getShirtNumber() === $targetPlayer->getShirtNumber(),
            'age_comparison' => $this->compareAge($guessedPlayer->getAge(), $targetPlayer->getAge()),
            'shirt_number_comparison' => $this->compareShirtNumber($guessedPlayer->getShirtNumber(), $targetPlayer->getShirtNumber())
        ];
    }

    private function prepareGuessResponse($guessedPlayer, $matches, $guessNumber, $userGuessLog) {
        $maxAttempts = 5;
        $remainingAttempts = $maxAttempts - $guessNumber - 1;
        $gameOver = array_reduce($matches, fn($carry, $item) => $carry && $item, true);
    
        $response = [
            'found' => true,
            'name' => $guessedPlayer->getName(),
            'country' => $guessedPlayer->getCountry(),
            'league' => $guessedPlayer->getLeague(),
            'club' => $guessedPlayer->getClub(),
            'position' => $guessedPlayer->getPosition(),
            'age' => $guessedPlayer->getAge(),
            'shirt_number' => $guessedPlayer->getShirtNumber(),
            'remaining_attempts' => $remainingAttempts,
            'game_over' => $gameOver,
            'matches' => $matches,
            'age_comparison' => $matches['age_comparison'],
            'shirt_number_comparison' => $matches['shirt_number_comparison']
        ];
    
        $this->updateGuessLog($userGuessLog, $gameOver);
    
        return $response;
    }
    private function updateGuessLog($userGuessLog, $guessedCorrectly) {
        $conn = $this->getDbConnection();
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');
    
        if ($userGuessLog) {
            // Jeśli istnieje wpis dla dzisiejszej daty, aktualizujemy go
            $stmt = $conn->prepare("
                UPDATE user_guess_log 
                SET guess_number = guess_number + 1, guessed_correctly = :guessed_correctly
                WHERE id_user = :id_user AND guess_date = :guess_date
            ");
        } else {
            // Jeśli nie ma wpisu, tworzymy nowy
            $stmt = $conn->prepare("
                INSERT INTO user_guess_log (id_user, guess_date, guess_number, guessed_correctly) 
                VALUES (:id_user, :guess_date, 1, :guessed_correctly)
            ");
        }
    
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':guess_date', $today);
        $stmt->bindParam(':guessed_correctly', $guessedCorrectly, PDO::PARAM_BOOL);
        $stmt->execute();
    }
    // Funkcja porównująca wiek
    private function compareAge($guessedAgeId, $targetAgeId) {
        $guessedAge = $this->getAge($guessedAgeId);
        $targetAge = $this->getAge($targetAgeId);
        return $guessedAge < $targetAge ? 'up' : ($guessedAge > $targetAge ? 'down' : 'equal');
    }

    // Funkcja porównująca numer koszulki
    private function compareShirtNumber($guessedShirtNumberId, $targetShirtNumberId) {
        $guessedShirtNumber = $this->getShirtNumber($guessedShirtNumberId);
        $targetShirtNumber = $this->getShirtNumber($targetShirtNumberId);
        return $guessedShirtNumber < $targetShirtNumber ? 'up' : ($guessedShirtNumber > $targetShirtNumber ? 'down' : 'equal');
}
    public function getPlayers() {
        header('Content-Type: application/json');
    
        // Sprawdzamy, czy przekazano zapytanie
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        if (empty($query)) {
            echo json_encode(['players' => []]);
            return;
        }
    
        $db = new DatabaseConnector();
        $conn = $db->connect();
    
        // Wyszukaj graczy, którzy pasują do zapytania 
        $stmt = $conn->prepare("SELECT name FROM players WHERE name LIKE :query LIMIT 10");
        $searchQuery = $query . '%'; // Dopasowanie do początku frazy
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
    
        $players = $stmt->fetchAll(PDO::FETCH_COLUMN); // Pobieramy tylko nazwy
        echo json_encode(['players' => $players]);
    }


    // Pomocnicza funkcja do pobrania piłkarza po nazwisku
    private function getPlayerByName($name) {
        $db = new DatabaseConnector();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT * FROM players WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$playerData) {
            return null;
        }
    
        return new Player(
            $playerData['id'],
            $playerData['name'],
            $this->getCountryName($playerData['country_id']),
            $this->getLeagueName($playerData['league_id']),
            $this->getClubName($playerData['club_id']),
            $this->getPositionName($playerData['position_id']),
            $this->getAge($playerData['age_id']),
            $this->getShirtNumber($playerData['shirt_number_id'])
        );
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