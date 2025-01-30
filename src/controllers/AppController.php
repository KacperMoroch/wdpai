<?php

class AppController {

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.php';
        $output = 'File not found';
                
        if(file_exists($templatePath)){
            extract($variables);
            
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;
    }
    // Metoda, która sprawdza, czy użytkownik jest zalogowany
    public function checkLogin() {
        if (!isset($_SESSION['user_id'])) {
            // Jeśli nie ma sesji użytkownika, przekieruj do logowania
            header("Location: /login");
            exit();  // Zatrzymaj dalsze wykonywanie skryptu
        }
    }
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}