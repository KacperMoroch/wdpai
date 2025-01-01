<?php

require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/RegistrationController.php';  

class Router {
  public static function run ($url) {
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
    } else {
        die("404 Not Found");
    }

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Method $action not found in controller.");
    }
  }
}

