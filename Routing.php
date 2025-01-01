<?php

require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/SecurityController.php';

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
    } else {
        die("404 Not Found"); // Dodam stronę błędu lub przekierowanie
    }

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Method $action not found in controller.");
    }
  }
}
