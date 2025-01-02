<?php
class User
{
    private $id;
    private $email;
    private $name;
    private ?string $surname; // Typ zmieniony na nullable string
    private $avatarUrl;
    private $password;

    public function __construct($id, $email, $name, ?string $surname, $avatarUrl, $password = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->avatarUrl = $avatarUrl;
        $this->password = $password;

        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
