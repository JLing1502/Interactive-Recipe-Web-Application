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

// Check if already participated
$checkQuery = "SELECT * FROM event_participants WHERE EventID = ? AND UserID = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->execute([$eventId, $userId]);

if ($checkStmt->rowCount() === 0) {
    // Insert participation, NULL submission ID, and current timestamp
    $insertQuery = "INSERT INTO event_participants (UserID, EventID, SubmissionID, ParticipateAt)
                    VALUES (?, ?, NULL, CURRENT_TIMESTAMP)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->execute([$userId, $eventId]);

    $_SESSION['flash_message'] = "Successfully participated in the event.";
} else {
    $_SESSION['flash_message'] = "You have already registered for this event.";
}

// Redirect back
header("Location: event_participate.php");
exit;