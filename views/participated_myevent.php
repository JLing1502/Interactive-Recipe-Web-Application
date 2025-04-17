<?php
session_start();
include_once '../config/Database.php';

$userId = $_SESSION['user']['UserID'];

$db = new Database();
$conn = $db->connect();

// Filtering by status (optional)
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$query = "
    SELECT e.*, ep.UserID, ep.EventID
    FROM event_participants ep
    INNER JOIN events e ON ep.EventID = e.EventID
    WHERE ep.UserID = :userId
";

if (!empty($statusFilter)) {
    $query .= " AND e.Status = :status";
}

$stmt = $conn->prepare($query);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
if (!empty($statusFilter)) {
    $stmt->bindParam(':status', $statusFilter, PDO::PARAM_STR);
}
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Participated Events</title>
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

        .filter {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Events I've Participated In</h2>

<div class="filter">
    <form method="get">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status">
            <option value="">All</option>
            <option value="Pending" <?= $statusFilter == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Opened" <?= $statusFilter == 'Opened' ? 'selected' : '' ?>>Opened</option>
            <option value="Closed" <?= $statusFilter == 'Closed' ? 'selected' : '' ?>>Closed</option>
        </select>
        <button type="submit">Filter</button>
    </form>
</div>

<?php if (count($events) > 0): ?>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Cancel Participation</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['Title']) ?></td>
                <td><?= htmlspecialchars($event['Description']) ?></td>
                <td><?= htmlspecialchars($event['DateTime']) ?></td>
                <td><?= htmlspecialchars($event['Status']) ?></td>
                <td><?= htmlspecialchars($event['Deadline']) ?></td>
                <td><?= htmlspecialchars($event['CreatedAt']) ?></td>
                <td><?= htmlspecialchars($event['UpdatedAt']) ?></td>
                <td>
                    <a href="../actions/event_leave.php?user_id=<?= $userId ?>&event_id=<?= $event['EventID'] ?>"
                       onclick="return confirm('Are you sure you want to cancel participation in this event?');">
                       Cancel
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No events found.</p>
<?php endif; ?>

<button onclick="history.back()">Go Back</button>
</body>
</html>
