<?php
session_start();
include_once '../config/Database.php';

$db = new Database();
$conn = $db->connect();

// Approve event
if (isset($_GET['approve'])) {
    $eventId = $_GET['approve'];

    $stmt = $conn->prepare("UPDATE events SET Status = 'Opened' WHERE EventID = ?");
    if ($stmt->execute([$eventId])) {
        echo "<script>alert('Event approved successfully and status set to Opened');</script>";
    } else {
        echo "<script>alert('Error approving event');</script>";
    }
}

// Reject event
if (isset($_GET['reject'])) {
    $eventId = $_GET['reject'];

    $stmt = $conn->prepare("UPDATE events SET Status = 'Rejected' WHERE EventID = ?");
    if ($stmt->execute([$eventId])) {
        echo "<script>alert('Event rejected successfully');</script>";
    } else {
        echo "<script>alert('Error rejecting event');</script>";
    }
}

// Load all pending events
$stmt = $conn->prepare("SELECT * FROM events WHERE Status = 'Pending'");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Approval</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .btn { padding: 5px 10px; margin-right: 5px; }
    </style>
</head>
<body>
    <h2>Pending Events for Approval</h2>

    <?php if (empty($events)): ?>
        <p>No pending events found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>DateTime</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['Title']) ?></td>
                        <td><?= htmlspecialchars($event['Description']) ?></td>
                        <td><?= htmlspecialchars($event['DateTime']) ?></td>
                        <td><?= htmlspecialchars($event['Deadline']) ?></td>
                        <td><?= htmlspecialchars($event['Status']) ?></td>
                        <td>
                            <a class="btn" href="?approve=<?= $event['EventID'] ?>" onclick="return confirm('Approve this event?')">Approve</a>
                            <a class="btn" href="?reject=<?= $event['EventID'] ?>" onclick="return confirm('Reject this event?')">Reject</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>

    <button onclick="location.href='event_participate.php'">Back to Events</button>
</body>
</html>
