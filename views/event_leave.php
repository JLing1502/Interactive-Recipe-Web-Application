<?php
session_start();
include_once '../config/Database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['event_id'])) {
    die("Invalid request.");
}

$userId = $_SESSION['user']['UserID'];
$eventId = $_POST['event_id'];

$db = new Database();
$conn = $db->connect();

// Delete participation
$deleteQuery = "DELETE FROM event_participants WHERE EventID = ? AND UserID = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->execute([$eventId, $userId]);

// Redirect back
header("Location: event_participate.php");
exit;
