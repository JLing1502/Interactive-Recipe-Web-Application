<?php
session_start();
include_once '../config/Database.php';

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

$userId = $_SESSION['user']['UserID'];

$db = new Database();
$conn = $db->connect(); 

$query = "SELECT * FROM events WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$userId]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Events</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #aaa;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2>My Events</h2>

<table>
    <thead>
        <tr>
            <th>Event Title</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Title']) ?></td>
                <td><?= htmlspecialchars($row['Description']) ?></td>
                <td><?= htmlspecialchars($row['DateTime']) ?></td>
                <td><?= htmlspecialchars($row['Status']) ?></td>
                <td><?= htmlspecialchars($row['Deadline']) ?></td>
                <td><?= htmlspecialchars($row['CreatedAt']) ?></td>
                <td><?= htmlspecialchars($row['UpdatedAt']) ?></td>
                <td>
                    <a href="../views/delete_event.php?id=<?= $row['EventID'] ?>"
                       onclick="return confirm('Are you sure you want to delete this event?');">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>

<button onclick="location.href='create_event.php'">Create an Event</button>
<button onclick="location.href='sidebar.php'">Back to Homepage</button>
</body>
</html>