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
        //TODO: retrive data from database
        //TODO; process
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
}