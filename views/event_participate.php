<?php
session_start();
include_once '../config/Database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

$userId = $_SESSION['user']['UserID'];

$db = new Database();
$conn = $db->connect();

// Fetch all opened events
$query = "SELECT * FROM events WHERE Status = 'Opened' ORDER BY DateTime ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get participated EventIDs
$participatedQuery = "SELECT EventID FROM event_participants WHERE UserID = ?";
$participatedStmt = $conn->prepare($participatedQuery);
$participatedStmt->execute([$userId]);
$participatedEventIDs = $participatedStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participate in Events</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Available Events</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date & Time</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Participation</th>
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
                        <?php if (in_array($event['EventID'], $participatedEventIDs)): ?>
                            <div>
                                Participated
                                <form method="POST" action="../views/event_leave.php" style="margin-top: 5px;">
                                    <input type="hidden" name="event_id" value="<?= $event['EventID'] ?>">
                                    <input type="submit" value="Cancel">
                                </form>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="../views/event_participate_submit.php">
                                <input type="hidden" name="event_id" value="<?= $event['EventID'] ?>">
                                <input type="submit" value="Participate">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <button onclick="location.href='sidebar.php'">Back to Homepage</button>
</body>
</html>
