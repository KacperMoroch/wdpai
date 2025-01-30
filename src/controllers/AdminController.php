<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function admin()
    {
        session_start();

        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
            header("Location: /dashboard");
            exit();
        }

        $users = $this->userRepository->getAllUsers();
        return $this->render("admin", ['users' => $users]);
    }

    public function deleteUser()
    {
        session_start();

        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
            header("Location: /dashboard");
            exit();
        }

        if (!isset($_POST['user_id'])) {
            header("Location: /admin");
            exit();
        }

        $this->userRepository->deleteUserById((int)$_POST['user_id']);
        header("Location: /admin");
    }
}
