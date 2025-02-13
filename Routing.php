<?php

require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/RegistrationController.php';  
require_once 'src/controllers/PlayerController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/TransferController.php';


class Router {
  public static function run ($url) {

    // Jeśli URL jest pusty, ustaw domyślną akcję na "login"
    if ($url === '') {
        $url = 'login';
    }

    $action = explode("/", $url)[0];
    $controller = null;

    if ($action === "dashboard") {
        $controller = new DashboardController();
        $action = "dashboard";
    } elseif ($action === "login") {
        $controller = new SecurityController();
        $action = "login";
    } elseif ($action === "register") { 
        $controller = new RegistrationController();
        $action = "register";   
    } elseif ($action === "startGame") {
        $controller = new PlayerController();
        $action = "startGame";
    } elseif ($action === "checkGuess") {
        $controller = new PlayerController();
        $action = "checkGuess";
    } elseif ($action === "getPlayers") {
        $controller = new PlayerController();
        $action = "getPlayers";
    }elseif ($action === "logout") {
        $controller = new DashboardController(); 
        $action = "logout";
    } elseif ($action === "profile") {
        $controller = new DashboardController();
        $action = "profile";
    }elseif ($action === "admin") {
        $controller = new AdminController();
        $action = "admin";
    } elseif ($action === "deleteUser") {
        $controller = new AdminController();
        $action = "deleteUser";
    }elseif ($action === "settings") {
        $controller = new DashboardController();
        $action = "settings";
    } elseif ($action === "deleteAccount") {
        $controller = new DashboardController();
        $action = "deleteAccount";
    }elseif ($action === "updateAccount") {
        $controller = new DashboardController();
        $controller->updateAccount();
    }elseif ($action === "start") {
        $controller = new TransferController();
        $action = "start";
    } elseif ($action === "guess") {
        $controller = new TransferController();
        $action = "guess";
    }

    else {
        die("404 Not Found");
    }

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Method $action not found in controller.");
    }
  }
}

