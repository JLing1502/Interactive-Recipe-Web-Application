<?php
require_once '../config/Database.php';

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Invalid request.");
}

$eventId = $_GET['id'];
$action = $_GET['action'];
$status = $action === 'approve' ? 'Approved' : 'Rejected';

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("UPDATE events SET Status = ? WHERE EventID = ?");
$stmt->execute([$status, $eventId]);

header("Location: ../views/event_approval.php");
exit;
