<?php
class Comment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addComment($postId, $userId, $content) {
        $stmt = $this->conn->prepare("INSERT INTO COMMENTS (PostID, UserID, Content, CreatedAt, UpdatedAt) VALUES (?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function getCommentsByPost($postId) {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.Name
            FROM COMMENTS c
            JOIN USERS u ON c.UserID = u.UserID
            WHERE c.PostID = ?
            ORDER BY c.CreatedAt ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
