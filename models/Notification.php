<?php
class Notification {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($userId, $typeId, $referenceId, $message) {
        $stmt = $this->conn->prepare("
            INSERT INTO NOTIFICATIONS (UserID, TypeID, ReferenceID, Message)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $typeId, $referenceId, $message]);
    }

    public function getUserNotifications($userId) {
        $stmt = $this->conn->prepare("
            SELECT n.*, t.TypeName, n.CreatedAt 
            FROM NOTIFICATIONS n 
            JOIN NOTIFICATIONTYPE t ON n.TypeID = t.TypeID 
            WHERE n.UserID = ?
            ORDER BY n.CreatedAt DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
