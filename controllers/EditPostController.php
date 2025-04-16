<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../config/Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class EditPostController {
    private $postModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->postModel = new Post($db);
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

        $post = $this->postModel->getPostById($postId);

        if (!$post || $post['UserID'] !== $userId) {
            echo "You are not allowed to edit this post.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if (!empty($title) && !empty($content)) {
                $this->postModel->updatePost($postId, $title, $content);
                header("Location: post.php?id=$postId");
                exit;
            } else {
                return ['post' => $post, 'error' => "Please fill in all fields."];
            }
        }

        return ['post' => $post];
    }
}
