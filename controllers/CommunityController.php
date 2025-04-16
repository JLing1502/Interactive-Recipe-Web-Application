<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../config/Database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class CommunityController {
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

        // Handle new post submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['UserID'];
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            // ✅ Optional: Basic validation
            if (!empty($title) && !empty($content)) {
                $this->postModel->create($userId, $title, $content);
            }

            // Always redirect to avoid duplicate submission on refresh
            header("Location: community.php");
            exit;
        }

        // Return all posts
        return $this->postModel->getAll();
    }
}
