<?php

require_once 'AppController.php';
require_once __DIR__.'/../../DatabaseConnector.php';

class DashboardController extends AppController
{
    public function dashboard() {
        //TODO: retrive data from database
        //TODO; process
        $connector = new DatabaseConnector();
        $stmt = $connector->connect()->prepare('SELECT * FROM public.user_account');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render("dashboard", ['name' => "Adrian", "users" => $users]);
    }
}