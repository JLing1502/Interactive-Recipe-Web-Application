<?php
session_start();
include_once '../config/Database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid event ID.");
}

$eventId = (int)$_GET['id'];
$userId = $_SESSION['user']['UserID'];

try {
    $db = new Database();
    $conn = $db->connect();

    $checkQuery = "SELECT * FROM events WHERE EventID = ? AND UserID = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$eventId, $userId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found or access denied.");
    }

    $deleteQuery = "DELETE FROM events WHERE EventID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute([$eventId]);

    header("Location: ../views/view_event.php");
    exit();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
