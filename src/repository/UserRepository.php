<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';


class UserRepository extends Repository {

    public function getUsers() {
        $stmt = $this->database->connect()->prepare('SELECT * FROM public.user');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // TODO return object, you can use FETCH_CLASS to avoid iterator with foreach
        $usersResponse = [];
        foreach($users as $user) {
            $usersResponse[] =  new User(
                $user['id'],
                $user['email'],
                $user['name'],
                $user['surname'],
                $user['avatar_url'],
            );
        }

        return $usersResponse;
    }
    
    public function addUser(User $user)
    {


        $stmt = $this->database->connect()->prepare('
            INSERT INTO user (email, password, id_user_details)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $this->getUserDetailsId($user)
        ]);
    }
    public function getUserByEmailOrLogin(string $emailOrLogin): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT ua.id_user, ua.email, ua.password, ud.name, ud.surname, ud.phone
            FROM user_account ua
            LEFT JOIN user_details ud ON ua.id_user_details = ud.id_user_details
            WHERE ua.email = :emailOrLogin OR ua.login = :emailOrLogin
        ');
    
        $stmt->bindParam(':emailOrLogin', $emailOrLogin, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($user);

        if ($user === false) {
            return null; // Użytkownik nie został znaleziony
        }
    
        return new User(
            $user['id_user'],
            $user['email'],
            $user['name'],
            $user['surname'],
            $user['phone'], // możesz to dodać, jeśli chcesz w obiekcie User
            $user['password']
        );
    }
    


}