<?php

require_once __DIR__.'/../../DatabaseConnector.php';
require_once __DIR__.'/../models/Player.php';

class PlayerRepository {
    private PDO $conn;

    public function __construct() {
        $db = new DatabaseConnector();
        $this->conn = $db->connect();
    }

    public function getPlayerById(int $playerId): ?Player {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.name, c.name AS country, l.name AS league, cl.name AS club, pos.name AS position, 
                   a.value AS age, sn.number AS shirt_number, tr.transfer_amount
            FROM players p
            JOIN countries c ON p.country_id = c.id
            JOIN leagues l ON p.league_id = l.id
            JOIN clubs cl ON p.club_id = cl.id
            JOIN positions pos ON p.position_id = pos.id
            JOIN ages a ON p.age_id = a.id
            JOIN shirt_numbers sn ON p.shirt_number_id = sn.id
            LEFT JOIN transfer tr ON p.id = tr.player_id -- Assuming transfer table exists
            WHERE p.id = :player_id
        ");
        $stmt->bindParam(':player_id', $playerId);
        $stmt->execute();
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $playerData ? new Player(
            $playerData['id'], 
            $playerData['name'], 
            $playerData['country'], 
            $playerData['league'], 
            $playerData['club'], 
            $playerData['position'], 
            (int)$playerData['age'], 
            (int)$playerData['shirt_number'],
            $playerData['transfer_amount'] !== null ? (float)$playerData['transfer_amount'] : null 
        ) : null;
    }

    public function getRandomPlayer(): ?Player {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.name, c.name AS country, l.name AS league, cl.name AS club, pos.name AS position, 
                   a.value AS age, sn.number AS shirt_number
            FROM players p
            JOIN countries c ON p.country_id = c.id
            JOIN leagues l ON p.league_id = l.id
            JOIN clubs cl ON p.club_id = cl.id
            JOIN positions pos ON p.position_id = pos.id
            JOIN ages a ON p.age_id = a.id
            JOIN shirt_numbers sn ON p.shirt_number_id = sn.id
            ORDER BY RANDOM() LIMIT 1
        ");
        $stmt->execute();
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $playerData ? new Player(
            $playerData['id'], 
            $playerData['name'], 
            $playerData['country'], 
            $playerData['league'], 
            $playerData['club'], 
            $playerData['position'], 
            (int)$playerData['age'], 
            (int)$playerData['shirt_number']
        ) : null;
    }

    public function getPlayerByName(string $name): ?Player {
        $stmt = $this->conn->prepare("SELECT * FROM players WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $playerData ? new Player(
            $playerData['id'], 
            $playerData['name'], 
            $playerData['country_id'], 
            $playerData['league_id'], 
            $playerData['club_id'], 
            $playerData['position_id'], 
            (int)$playerData['age_id'], 
            (int)$playerData['shirt_number_id']
        ) : null;
    }
}
