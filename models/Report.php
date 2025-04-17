<?php
class Report
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function submitPostReport($userId, $postId, $reason)
    {
        $stmt = $this->conn->prepare("INSERT INTO REPORTS (UserID, PostID, Reason, CreatedAt) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$userId, $postId, $reason]);
    }

    public function submitCommentReport($userId, $commentId, $reason)
    {
        $stmt = $this->conn->prepare("INSERT INTO REPORTS (UserID, CommentID, Reason, CreatedAt) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$userId, $commentId, $reason]);
    }

    public function getAllReports() {
        $sql = "
            SELECT 
                r.ReportID,
                r.UserID,
                u.Name AS ReporterName,
                r.PostID,
                r.CommentID,
                r.Reason,
                r.CreatedAt,
                COALESCE(r.PostID, c.PostID) AS ResolvedPostID
            FROM REPORTS r
            LEFT JOIN USERS u ON r.UserID = u.UserID
            LEFT JOIN COMMENTS c ON r.CommentID = c.CommentID
            ORDER BY r.CreatedAt DESC
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
