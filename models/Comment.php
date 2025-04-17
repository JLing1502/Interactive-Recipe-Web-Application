<?php
class Comment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // comment on a post
    public function addComment($postId, $userId, $content) {
        $stmt = $this->conn->prepare("INSERT INTO COMMENTS (PostID, UserID, Content, CreatedAt, UpdatedAt) VALUES (?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$postId, $userId, $content]);
    }

    //get comment on a particular post
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

    //get userid from comments
    public function getUserComments($userId) {
        $stmt = $this->conn->prepare("
            SELECT c.*, p.Title 
            FROM COMMENTS c
            JOIN POSTS p ON c.PostID = p.PostID
            WHERE c.UserID = ?
            ORDER BY c.CreatedAt DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //retrieve comment
    public function getCommentById($commentId) {
        $stmt = $this->conn->prepare("SELECT * FROM COMMENTS WHERE CommentID = ?");
        $stmt->execute([$commentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Untuk delete comment (Admin function) if it still doesnt work, i'll give up and work as tukang jual sayur at pasar am Sabah
    public function deleteComment($commentId) {
        $sql = "DELETE FROM COMMENTS WHERE CommentID = :commentId";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':commentId' => $commentId]);
    }   
    
}

/*I spent 2 weeks on this, whoever messed it up I can see in github ---kim*/
