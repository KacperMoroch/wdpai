<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';

class RegistrationController extends AppController {
    public function register() {
        if ($this->isGet()) {
            // Renderujemy stronę rejestracji
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

        // Połączenie z bazą danych
        $db = new DatabaseConnector();
        $conn = $db->connect();

        try {
            // Sprawdzanie, czy e-mail już istnieje
            $stmt = $conn->prepare('SELECT COUNT(*) FROM public.user_account WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $email_exists = $stmt->fetchColumn();

            if ($email_exists > 0) {
                return $this->render("register", ['error' => 'Podany e-mail jest już zajęty!']);
            }

            // Sprawdzanie, czy numer telefonu już istnieje
            $stmt = $conn->prepare('SELECT COUNT(*) FROM public.user_details WHERE phone = :phone');
            $stmt->execute(['phone' => $phone]);
            $phone_exists = $stmt->fetchColumn();

            if ($phone_exists > 0) {
                return $this->render("register", ['error' => 'Podany numer telefonu jest już zajęty!']);
            }

            // Sprawdzanie, czy login (nickname) już istnieje
            $stmt = $conn->prepare('SELECT COUNT(*) FROM public.user_account WHERE login = :nickname');
            $stmt->execute(['nickname' => $nickname]);
            $nickname_exists = $stmt->fetchColumn();

            if ($nickname_exists > 0) {
                return $this->render("register", ['error' => 'Podany nick (login) jest już zajęty!']);
            }

            // Szyfrowanie hasła
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            // Trzeba w login dodac odhashowywanie hasel


            // Rozpoczynamy transakcję
            $conn->beginTransaction();

            // Dodanie adresu
            $stmt = $conn->prepare('INSERT INTO public.address (postal_code, street, locality, number) VALUES (:postal_code, :street, :locality, :number) RETURNING id_address');
            $stmt->execute([
                'postal_code' => '00000', // Tymczasowy kod
                'street' => 'Nieznana',  // Tymczasowa ulica
                'locality' => 'Nieznana', // Tymczasowa miejscowość
                'number' => '0'           // Tymczasowy numer
            ]);
            $id_address = $stmt->fetch(PDO::FETCH_ASSOC)['id_address'];

            // Dodanie szczegółów użytkownika
            $stmt = $conn->prepare('INSERT INTO public.user_details (id_address, name, surname, phone) VALUES (:id_address, :name, :surname, :phone) RETURNING id_user_details');
            $stmt->execute([
                'id_address' => $id_address,
                'name' => $nickname, // Tymczasowo jako imię
                'surname' => 'Nieznane', // Tymczasowo jako nazwisko
                'phone' => $phone
            ]);
            $id_user_details = $stmt->fetch(PDO::FETCH_ASSOC)['id_user_details'];

            // Dodanie użytkownika
            $stmt = $conn->prepare('INSERT INTO public.user_account (id_user_details, id_role, email, login, password, salt, created_at) VALUES (:id_user_details, :id_role, :email, :login, :password, :salt, CURRENT_TIMESTAMP)');
            $stmt->execute([
                'id_user_details' => $id_user_details,
                'id_role' => 1, // Domyślnie przypisujemy rolę (np. użytkownik/user)
                'email' => $email,
                'login' => $nickname,
                'password' => $hashed_password,
                'salt' => null // Salt jest nieużywany, ale możesz to zmienić
            ]);

            // Zatwierdzenie transakcji
            $conn->commit();

            // Przekierowanie po udanej rejestracji
            header('Location: /login');
            exit();
        } catch (Exception $e) {
            // Cofnięcie transakcji w razie błędu
            $conn->rollBack();
        
            // Ścieżka do niestandardowego pliku logów
            $logFile = __DIR__ . '/../../logs/error_log.txt';
        
            // Zapis błędu do pliku
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        
            // Wyświetlenie ogólnego błędu użytkownikowi
            return $this->render("register", ['error' => 'Wystąpił błąd podczas rejestracji.']);
        }
    }
}
