<?php

class Player {
    private int $id;
    private string $name;
    private string $country;
    private string $league;
    private string $club;
    private string $position;
    private int $age;
    private int $shirtNumber;
    private ?float $transferAmount; 

    public function __construct(int $id, string $name, string $country, string $league, string $club, string $position, int $age, int $shirtNumber, ?float $transferAmount = null ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->league = $league;
        $this->club = $club;
        $this->position = $position;
        $this->age = $age;
        $this->shirtNumber = $shirtNumber;
        $this->transferAmount = $transferAmount;
    }

    public function getTransferAmount(): float { return $this->transferAmount; }
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCountry(): string { return $this->country; }
    public function getLeague(): string { return $this->league; }
    public function getClub(): string { return $this->club; }
    public function getPosition(): string { return $this->position; }
    public function getAge(): int { return $this->age; }
    public function getShirtNumber(): int { return $this->shirtNumber; }
}
