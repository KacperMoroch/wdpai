<?php

require_once __DIR__.'/../../DatabaseConnector.php';

class Repository {
    protected $database;

    public function __construct()
    {
        $this->database = new DatabaseConnector();
    }
}