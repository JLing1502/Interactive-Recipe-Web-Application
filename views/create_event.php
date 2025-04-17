<?php
session_start();
include_once '../config/Database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

$userId = $_SESSION['user']['UserID'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $datetime = $_POST['datetime'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $status = $_POST['status'] ?? 'Pending'; // Default fallback

    if (!empty($title) && !empty($datetime) && !empty($deadline) && !empty($status)) {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("INSERT INTO events (UserID, Title, Description, DateTime, Deadline, Status, CreatedAt, UpdatedAt)
                                VALUES (:userId, :title, :description, :datetime, :deadline, :status, NOW(), NOW())");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':datetime', $datetime);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            echo "<script>alert('Event created successfully!'); window.location.href='view_event.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to create event.');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>
<h2>Create New Event</h2>
<form method="post">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Date & Time:</label><br>
    <input type="datetime-local" name="datetime" required><br><br>

    <label>Deadline:</label><br>
    <input type="datetime-local" name="deadline" required><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="">-- Select Status --</option>
        <option value="Pending">Pending</option>
        <option value="Opened">Opened</option>
        <option value="Closed">Closed</option>
    </select><br><br>

    <button type="submit">Create Event</button>
</form>

<button onclick="history.back()">Back</button>
</body>
</html>
