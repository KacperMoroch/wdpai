<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository {

    private function fetchUser(array $user): User 
    {
        return new User(
            $user['id_user'],
            $user['email'],
            $user['name'] ?? null,
            $user['surname'] ?? null,
            null, 
            $user['password'] ?? null,
            $user['id_role'] ?? null,
            $user['login'] ?? null,
            $user['role_name'] ?? null,
            $user['created_at'] ?? null
        );
    }

    public function getUsers(): array 
    {
        $stmt = $this->database->connect()->prepare('SELECT * FROM public.user');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'fetchUser'], $users);
    }
    
    public function addUser(User $user)
    {
        $pdo = $this->database->connect();
        $pdo->beginTransaction();
    
        try {
            // Dodanie user_details, jeśli użytkownik ma imię i nazwisko
            $userDetailsId = null;
            if ($user->getName() || $user->getSurname()) {
                $stmt = $pdo->prepare('
                    INSERT INTO user_details (name, surname) 
                    VALUES (:name, :surname) 
                    RETURNING id_user_details
                ');
                $stmt->execute([
                    'name' => $user->getName(),
                    'surname' => $user->getSurname()
                ]);
                $userDetailsId = $stmt->fetchColumn();
            }
    
            // Dodanie użytkownika do user_account
            $stmt = $pdo->prepare('
                INSERT INTO user_account (email, password, id_user_details, id_role, login, created_at)
                VALUES (:email, :password, :id_user_details, :id_role, :login, CURRENT_TIMESTAMP)
            ');
    
            $stmt->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'id_user_details' => $userDetailsId,
                'id_role' => $user->getRoleId() ?? 1, // Domyślna rola to "User"
                'login' => $user->getLogin()
            ]);
    
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e; // Rzucenie wyjątku, jeśli coś poszło nie tak
        }
    }

    public function getUserByEmailOrLogin(string $emailOrLogin): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT ua.id_user, ua.email, ua.password, ud.name, ud.surname, ua.id_role, 
                   r.rola AS role_name, ua.login, ua.created_at
            FROM user_account ua
            LEFT JOIN user_details ud ON ua.id_user_details = ud.id_user_details
            LEFT JOIN role r ON ua.id_role = r.id_role
            WHERE ua.email = :emailOrLogin OR ua.login = :emailOrLogin
        ');
        $stmt->bindParam(':emailOrLogin', $emailOrLogin, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $this->fetchUser($user) : null;
    }


    public function getAllUsers(): array 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT ua.id_user, ua.email, ua.login, r.rola AS role_name, r.id_role
            FROM user_account ua
            LEFT JOIN role r ON ua.id_role = r.id_role
        ');
        $stmt->execute();

        return array_map([$this, 'fetchUser'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function deleteUserById(int $userId): void
    {
        $stmt = $this->database->connect()->prepare("DELETE FROM user_account WHERE id_user = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getUserById(int $userId): ?User 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT ua.id_user, ua.email, ua.password, ud.name, ud.surname, ua.id_role, 
                   r.rola AS role_name, ua.login, ua.created_at
            FROM user_account ua
            LEFT JOIN user_details ud ON ua.id_user_details = ud.id_user_details
            LEFT JOIN role r ON ua.id_role = r.id_role
            WHERE ua.id_user = :userId
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $this->fetchUser($user) : null;
    }

    public function getUserSettings(int $userId): ?array {
        $stmt = $this->database->connect()->prepare('
            SELECT login, email FROM user_account WHERE id_user = :userId
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    // Liczy punkty z tabeli user_guess_log
    public function countPointsFromUserGuessLog(int $userId): int 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT COUNT(*) AS correct_guesses FROM user_guess_log 
            WHERE id_user = :userId AND guessed_correctly = true
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['correct_guesses'] ?? 0) * 100;
    }

    // Liczy punkty z tabeli user_guess_log_transfer
    public function countPointsFromUserGuessLogTransfer(int $userId): int 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT COUNT(*) AS correct_guesses FROM user_guess_log_transfer 
            WHERE id_user = :userId AND guessed_correctly = true
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['correct_guesses'] ?? 0) * 100;
    }

    public function deleteUser(int $userId): bool {
        $stmt = $this->database->connect()->prepare('DELETE FROM user_account WHERE id_user = :userId');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}