<?php
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../config/Database.php';

class NotificationController {
    private $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new Notification($db);
    }

    public function getForUser($userId) {
        return $this->model->getUserNotifications($userId);
    }
}
