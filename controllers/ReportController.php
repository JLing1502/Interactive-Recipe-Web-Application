<?php
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../config/Database.php';
session_start();

class ReportController {
    private $reportModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->reportModel = new Report($db);
    }

    public function handleReport() {
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        $userId = $_SESSION['user']['UserID'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');

            if (isset($_POST['report_post']) && isset($_POST['post_id'])) {
                $this->reportModel->submitPostReport($userId, $_POST['post_id'], $reason);
            }

            if (isset($_POST['report_comment']) && isset($_POST['comment_id'])) {
                $this->reportModel->submitCommentReport($userId, $_POST['comment_id'], $reason);
            }

            // Optional: flash message or redirect
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
