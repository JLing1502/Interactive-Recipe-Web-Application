<?php
session_start();
include_once '../config/Database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
    die('You must be logged in to create an event.');
}

$db = Database::getInstance();
$conn = $db->getConnection();

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Filtering
$eventFilter = $_GET['event'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "WHERE 1=1";
if (!empty($eventFilter)) {
    $where .= " AND e.Title LIKE '%" . $conn->real_escape_string($eventFilter) . "%'";
}
if (!empty($statusFilter)) {
    $where .= " AND s.Status = '" . $conn->real_escape_string($statusFilter) . "'";
}

// Query
$query = "
    SELECT s.SubmissionID, s.EventID, s.UserID, s.Status, s.CreatedAt, s.UpdatedAt, 
           e.Title, u.Username
    FROM submissions s
    JOIN events e ON s.EventID = e.EventID
    JOIN users u ON s.UserID = u.UserID
    $where
    ORDER BY s.CreatedAt DESC
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($query);

// Count total records for pagination
$countQuery = "
    SELECT COUNT(*) AS total
    FROM submissions s
    JOIN events e ON s.EventID = e.EventID
    $where
";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Event Submissions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #999;
        }
        th {
            background-color: #eee;
        }
        form.filter-form {
            margin-bottom: 20px;
        }
        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ccc;
            margin: 0 2px;
            text-decoration: none;
        }
        .pagination .current {
            font-weight: bold;
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>All Submissions for Events</h2>

<form class="filter-form" method="get">
    <label>Event Title:
        <input type="text" name="event" value="<?= htmlspecialchars($eventFilter) ?>">
    </label>
    <label>Status:
        <select name="status">
            <option value="">All</option>
            <option value="Pending" <?= $statusFilter == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Approved" <?= $statusFilter == 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="Rejected" <?= $statusFilter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </label>
    <button type="submit">Filter</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Event</th>
        <th>User</th>
        <th>Status</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['SubmissionID']) ?></td>
        <td><?= htmlspecialchars($row['Title']) ?></td>
        <td><?= htmlspecialchars($row['Username']) ?></td>
        <td><?= htmlspecialchars($row['Status']) ?></td>
        <td><?= htmlspecialchars($row['CreatedAt']) ?></td>
        <td><?= htmlspecialchars($row['UpdatedAt']) ?></td>
        <td>
            <form action="update_submission_status.php" method="post" style="display:inline;">
                <input type="hidden" name="submission_id" value="<?= $row['SubmissionID'] ?>">
                <input type="hidden" name="status" value="Approved">
                <button type="submit">Approve</button>
            </form>
            <form action="update_submission_status.php" method="post" style="display:inline;">
                <input type="hidden" name="submission_id" value="<?= $row['SubmissionID'] ?>">
                <input type="hidden" name="status" value="Rejected">
                <button type="submit">Reject</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="pagination">
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="?page=<?= $p ?>&event=<?= urlencode($eventFilter) ?>&status=<?= urlencode($statusFilter) ?>"
           class="<?= $p == $page ? 'current' : '' ?>">
            <?= $p ?>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>
