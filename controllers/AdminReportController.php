<?php
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../config/Database.php';

class AdminReportController {
    private $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new Report($db);
    }

    public function index() {
        return $this->model->getAllReports();
    }
}
