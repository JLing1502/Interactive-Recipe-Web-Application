<?php
require_once __DIR__ . '/../config/Database.php';

class Event {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getEventsByUserId($userId) {
        $sql = "SELECT * FROM events WHERE UserID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($data) {
        $sql = "INSERT INTO events (Title, Description, DateTime, Status, Deadline, UserID, CreatedAt, UpdatedAt)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['Title'],
            $data['Description'],
            $data['DateTime'],
            $data['Status'],
            $data['Deadline'],
            $data['UserID']
        ]);
    }

    public function deleteEvent($eventId, $userId) {
        $sql = "DELETE FROM events WHERE EventID = ? AND UserID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$eventId, $userId]);
    }

    public function getEventById($eventId, $userId) {
        $sql = "SELECT * FROM events WHERE EventID = ? AND UserID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$eventId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEvent($eventId, $userId, $data) {
        $sql = "UPDATE events SET Title = ?, Description = ?, DateTime = ?, Status = ?, Deadline = ?, UpdatedAt = NOW()
                WHERE EventID = ? AND UserID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['Title'],
            $data['Description'],
            $data['DateTime'],
            $data['Status'],
            $data['Deadline'],
            $eventId,
            $userId
        ]);
    }
}
