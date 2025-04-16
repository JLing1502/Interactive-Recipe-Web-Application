<?php
class Report {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function submitPostReport($userId, $postId, $reason) {
        $stmt = $this->conn->prepare("INSERT INTO REPORTS (UserID, PostID, Reason, CreatedAt) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$userId, $postId, $reason]);
    }

    public function submitCommentReport($userId, $commentId, $reason) {
        $stmt = $this->conn->prepare("INSERT INTO REPORTS (UserID, CommentID, Reason, CreatedAt) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$userId, $commentId, $reason]);
    }
}
