<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class PostController {
    private $postModel;
    private $commentModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->postModel = new Post($db);
        $this->commentModel = new Comment($db);
    }

    public function handleRequest() {
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }
    
        $userId = $_SESSION['user']['UserID'];
        $postId = $_GET['id'] ?? null;
    
        if (!$postId) {
            echo "Post ID missing.";
            exit;
        }
    
        $db = (new Database())->connect();
        $notificationModel = new Notification($db);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->postModel->getPostById($postId);
    
            if (isset($_POST['delete']) && $userId === $post['UserID']) {
                $this->postModel->deletePost($postId);
                header("Location: community.php");
                exit;
            }
    
            // LIKE
            if (isset($_POST['like'])) {
                $this->postModel->likePost($postId, $userId);
    
                if ($userId !== $post['UserID']) {
                    $postOwnerId = $post['UserID'];
                    $currentUser = $_SESSION['user']['Name'];
                    $message = "$currentUser liked your post.";
                    $notificationModel->create($postOwnerId, 2, $postId, $message); // 2 = Post Liked
                }
    
            // DISLIKE
            } elseif (isset($_POST['dislike'])) {
                $this->postModel->dislikePost($postId, $userId);
    
                if ($userId !== $post['UserID']) {
                    $postOwnerId = $post['UserID'];
                    $currentUser = $_SESSION['user']['Name'];
                    $message = "$currentUser disliked your post.";
                    $notificationModel->create($postOwnerId, 3, $postId, $message); // 3 = Post Disliked
                }
    
            // COMMENT
            } elseif (isset($_POST['comment'])) {
                $content = trim($_POST['content']);
                if (!empty($content)) {
                    $this->commentModel->addComment($postId, $userId, $content);
    
                    if ($userId !== $post['UserID']) {
                        $postOwnerId = $post['UserID'];
                        $currentUser = $_SESSION['user']['Name'];
                        $message = "$currentUser commented on your post.";
                        $notificationModel->create($postOwnerId, 1, $postId, $message); // 1 = New Comment
                    }
                }
    
            // REPORT POST
            } elseif (isset($_POST['report_post']) && isset($_POST['reason'])) {
                $reason = trim($_POST['reason']);
                $postOwnerId = $post['UserID'];
                if ($userId !== $postOwnerId) {
                    $message = "Your post was reported for: $reason";
                    $notificationModel->create($postOwnerId, 4, $postId, $message); // 4 = Post Reported
                }
    
            // REPORT COMMENT
            } elseif (isset($_POST['report_comment']) && isset($_POST['comment_id']) && isset($_POST['reason'])) {
                $reason = trim($_POST['reason']);
                $commentId = $_POST['comment_id'];
    
                // Get comment owner
                $commentModel = new Comment($db);
                $comment = $commentModel->getCommentById($commentId);
                if ($comment && $userId !== $comment['UserID']) {
                    $commentOwnerId = $comment['UserID'];
                    $message = "Your comment was reported for: $reason";
                    $notificationModel->create($commentOwnerId, 5, $commentId, $message); // 5 = Comment Reported
                }
            }
    
            header("Location: post.php?id=$postId");
            exit;
        }
    
        $post = $this->postModel->getPostById($postId);
        $comments = $this->commentModel->getCommentsByPost($postId);
    
        return ['post' => $post, 'comments' => $comments];
    }
    

}
