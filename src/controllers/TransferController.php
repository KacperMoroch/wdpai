<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__.'/../models/Player.php';
require_once __DIR__.'/../repository/PlayerRepository.php';

class TransferController extends AppController
{
    private PlayerRepository $playerRepository;

    public function __construct()
    {
        $this->playerRepository = new PlayerRepository();
    }

    public function start()
    {
        session_start();

        $this->checkLogin();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Nie jesteś zalogowany!";
            header("Location: /login");
            exit();
        }

        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');

        $db = new DatabaseConnector();
        $conn = $db->connect();

        $stmt = $conn->prepare("SELECT transfer_id FROM transfer_question_of_the_day WHERE question_date = :today");
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($question) {
            $transferId = $question['transfer_id'];
        } else {
   
            $stmt = $conn->prepare("SELECT * FROM transfer ORDER BY RANDOM() LIMIT 1");
            $stmt->execute();
            $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$transfer) {
                die("Błąd: Nie udało się wylosować transferu.");
            }

            $stmt = $conn->prepare("INSERT INTO transfer_question_of_the_day (transfer_id, question_date) VALUES (:transfer_id, :question_date)");
            $stmt->bindParam(':transfer_id', $transfer['id']);
            $stmt->bindParam(':question_date', $today);
            $stmt->execute();
            
            $transferId = $transfer['id'];
        }

        $_SESSION['transfer_question'] = $transferId;

        $stmt = $conn->prepare("
            SELECT tr.*, f.name AS from_club, t.name AS to_club 
            FROM transfer tr
            JOIN clubs f ON tr.from_club_id = f.id
            JOIN clubs t ON tr.to_club_id = t.id
            WHERE tr.id = :transfer_id
        ");
        $stmt->bindParam(':transfer_id', $transferId);
        $stmt->execute();
        $transferDetails = $stmt->fetch(PDO::FETCH_ASSOC);


        $stmt = $conn->prepare("SELECT guess_number FROM user_guess_log_transfer WHERE id_user = :user_id AND guess_date = :today");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $userGuessLog = $stmt->fetch(PDO::FETCH_ASSOC);
        $remainingAttempts = 5 - ($userGuessLog['guess_number'] ?? 0);



        $this->render("transfer", ['transfer_details' => $transferDetails,'remainingAttempts' => $remainingAttempts]);
    }

    public function guess()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $inputData = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($inputData['player_name']) || empty(trim($inputData['player_name']))) {
            echo json_encode(['error' => 'Brak nazwy piłkarza lub nieprawidłowe dane.']);
            return;
        }
    
        $playerName = htmlspecialchars(trim($inputData['player_name']), ENT_QUOTES, 'UTF-8');

        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Użytkownik nie jest zalogowany.']);
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');
    
        $transferId = $_SESSION['transfer_question'] ?? null;
        if (!$transferId) {
            echo json_encode(['error' => 'Gra nie została rozpoczęta.']);
            return;
        }
    
        $db = new DatabaseConnector();
        $conn = $db->connect();
    
        $stmt = $conn->prepare("
            SELECT id, guess_number, guessed_correctly 
            FROM user_guess_log_transfer 
            WHERE id_user = :id_user AND guess_date = :guess_date
        ");
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':guess_date', $today);
        $stmt->execute();
        $userGuessLog = $stmt->fetch(PDO::FETCH_ASSOC);

        $correctPlayer = $this->getCorrectPlayer($transferId);

        if (!$correctPlayer) {
            echo json_encode(['error' => 'Błąd podczas pobierania danych transferu.']);
            return;
        }

        if (!empty($userGuessLog) && $userGuessLog['guessed_correctly']) {
            $correctPlayerName = $correctPlayer->getName(); 
            echo json_encode(['error' => 'Już zgadłeś dzisiaj piłkarza. Spróbuj ponownie jutro. Piłkarzem do zgadnięcia był: "' . $correctPlayerName . '" ']);
            return;
        }

        $guessNumber = ($userGuessLog['guess_number'] ?? 0);
        if ($guessNumber >= 5) {
            $correctPlayerName = $correctPlayer->getName(); 
            echo json_encode([
                'error' => 'Już nie możesz dzisiaj zgadywać, nie masz prób. Spróbuj ponownie jutro. Piłkarzem do zgadnięcia był: "' . $correctPlayerName . '" '
            ]);
            return;
        }

        $guessedCorrectly = strtolower($playerName) === strtolower($correctPlayer->getName());
        $guessNumber++;
    
        if ($userGuessLog) {
            $stmt = $conn->prepare("
                UPDATE user_guess_log_transfer 
                SET guess_number = :guess_number, guessed_correctly = :guessed_correctly 
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $userGuessLog['id']);
        } else {
            $stmt = $conn->prepare("
                INSERT INTO user_guess_log_transfer (id_user, guess_date, guess_number, guessed_correctly) 
                VALUES (:id_user, :guess_date, :guess_number, :guessed_correctly)
            ");
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':guess_date', $today);
        }
        $stmt->bindParam(':guess_number', $guessNumber);
        $stmt->bindParam(':guessed_correctly', $guessedCorrectly, PDO::PARAM_BOOL);
        $stmt->execute();
    
        $remainingAttempts = 5 - $guessNumber;
    
        $response = [
            'guessed_correctly' => $guessedCorrectly,
            'correct_player' => $correctPlayer->getName(),
            'transfer_amount' => $correctPlayer->getTransferAmount(), 
            'remaining_attempts' => $remainingAttempts,
            'game_over' => $guessedCorrectly || $remainingAttempts <= 0,
        ];
    
        echo json_encode($response);
    }

    private function getCorrectPlayer(int $transferId): ?Player
    {
        $db = new DatabaseConnector();
        $conn = $db->connect();
    
        $stmt = $conn->prepare("
            SELECT p.id
            FROM players p
            JOIN transfer tr ON p.id = tr.player_id
            WHERE tr.id = :transfer_id
        ");
        $stmt->bindParam(':transfer_id', $transferId);
        $stmt->execute();
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $playerData ? $this->playerRepository->getPlayerById($playerData['id']) : null;
    }
}