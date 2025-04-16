<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($userId, $title, $content) {
        $stmt = $this->conn->prepare("INSERT INTO POSTS (UserID, Title, Content, CreatedAt, UpdatedAt) VALUES (?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$userId, $title, $content]);
    }

    public function getAll() {
        $stmt = $this->conn->query("
            SELECT p.*, u.Name,
                (SELECT COUNT(*) FROM POSTLIKES WHERE PostID = p.PostID) AS Likes,
                (SELECT COUNT(*) FROM POSTDISLIKES WHERE PostID = p.PostID) AS Dislikes
            FROM POSTS p
            JOIN USERS u ON p.UserID = u.UserID
            ORDER BY p.CreatedAt DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id) {
        $stmt = $this->conn->prepare("
            SELECT p.*, u.Name,
                (SELECT COUNT(*) FROM POSTLIKES WHERE PostID = p.PostID) AS Likes,
                (SELECT COUNT(*) FROM POSTDISLIKES WHERE PostID = p.PostID) AS Dislikes
            FROM POSTS p
            JOIN USERS u ON p.UserID = u.UserID
            WHERE p.PostID = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletePost($postId) {
        $stmt = $this->conn->prepare("DELETE FROM POSTS WHERE PostID = ?");
        return $stmt->execute([$postId]);
    }
    
    public function updatePost($postId, $title, $content) {
        $stmt = $this->conn->prepare("UPDATE POSTS SET Title = ?, Content = ?, UpdatedAt = NOW() WHERE PostID = ?");
        return $stmt->execute([$title, $content, $postId]);
    }
    

    public function likePost($postId, $userId) {
        $stmt = $this->conn->prepare("REPLACE INTO POSTLIKES (PostID, UserID, CreatedAt, LikesCount) VALUES (?, ?, NOW(), 1)");
        $stmt->execute([$postId, $userId]);
        // Remove dislike if exists
        $this->conn->prepare("DELETE FROM POSTDISLIKES WHERE PostID = ? AND UserID = ?")->execute([$postId, $userId]);
    }

    public function dislikePost($postId, $userId) {
        $stmt = $this->conn->prepare("REPLACE INTO POSTDISLIKES (PostID, UserID, CreatedAt, DislikesCount) VALUES (?, ?, NOW(), 1)");
        $stmt->execute([$postId, $userId]);
        // Remove like if exists
        $this->conn->prepare("DELETE FROM POSTLIKES WHERE PostID = ? AND UserID = ?")->execute([$postId, $userId]);
    }

    public function getUserPosts($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM POSTS WHERE UserID = ? ORDER BY CreatedAt DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLikedPosts($userId) {
        $stmt = $this->conn->prepare("
            SELECT p.*
            FROM POSTLIKES pl
            JOIN POSTS p ON pl.PostID = p.PostID
            WHERE pl.UserID = ?
            ORDER BY pl.CreatedAt DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
