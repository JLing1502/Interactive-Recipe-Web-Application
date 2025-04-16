<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';

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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete']) && $postId && $userId === $this->postModel->getPostById($postId)['UserID']) {
                $this->postModel->deletePost($postId);
                header("Location: community.php");
                exit;
            }
            if (isset($_POST['like'])) {
                $this->postModel->likePost($postId, $userId);
            } elseif (isset($_POST['dislike'])) {
                $this->postModel->dislikePost($postId, $userId);
            } elseif (isset($_POST['comment'])) {
                $content = trim($_POST['content']);
                if (!empty($content)) {
                    $this->commentModel->addComment($postId, $userId, $content);
                }
            }

            header("Location: post.php?id=$postId"); // prevent resubmission
            exit;
        }

        $post = $this->postModel->getPostById($postId);
        $comments = $this->commentModel->getCommentsByPost($postId);

        return ['post' => $post, 'comments' => $comments];
    }

    
}
