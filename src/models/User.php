<?php
class User
{
    private int $id;
    private string $email;
    private ?string $name;
    private ?string $surname; 
    private ?string $avatarUrl;
    private ?string $password;
    private ?int $roleId;
    private ?string $login; 
    private ?string $roleName;
    private ?DateTime $createdAt;

    public function __construct(
        int $id, 
        string $email, 
        ?string $name, 
        ?string $surname, 
        ?string $avatarUrl = null, 
        ?string $password = null, 
        ?int $roleId = null, 
        ?string $login = null, 
        ?string $roleName = null, 
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->avatarUrl = $avatarUrl;
        $this->password = $password;
        $this->roleId = $roleId;
        $this->login = $login;
        $this->roleName = $roleName;
        $this->createdAt = $createdAt ? new DateTime($createdAt) : null; 
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
