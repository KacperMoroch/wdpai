<?php
class User
{
    private $id;
    private $email;
    private $name;
    private ?string $surname; // Typ nullable string
    private $avatarUrl;
    private $password;
    private $roleId;
    private $login; 
    private $roleName;


    public function __construct($id, $email, $name, ?string $surname, $avatarUrl = null, $password = null, $roleId = null, $login = null, $roleName = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->avatarUrl = $avatarUrl;
        $this->password = $password;
        $this->roleId = $roleId;
        $this->login = $login;
        $this->roleName = $roleName;
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
    public function getLogin()
    {
        return $this->login;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(?string $roleName)
    {
        $this->roleName = $roleName;
    }
}
