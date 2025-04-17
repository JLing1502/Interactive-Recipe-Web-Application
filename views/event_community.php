<?php
// File: views/event_community.php

session_start();
include_once '../config/Database.php';

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Event not specified.");
}

$eventId = intval($_GET['event_id']);

$db = new Database();
$conn = $db->connect();

// Fetch event details
$eventQuery = "SELECT * FROM events WHERE EventID = ? AND Status = 'Approved'";
$stmt = $conn->prepare($eventQuery);
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found or not approved.");
}

// Fetch recipe submissions for this event
$submissionsQuery = "SELECT s.*, u.Username FROM submissions s JOIN users u ON s.UserID = u.UserID WHERE s.EventID = ?";
$stmt = $conn->prepare($submissionsQuery);
$stmt->execute([$eventId]);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($event['Title']) ?> - Community Event</title>
    <style>
        .submission {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<h2><?= htmlspecialchars($event['Title']) ?> - Recipes</h2>
<p><strong>Description:</strong> <?= htmlspecialchars($event['Description']) ?></p>
<p><strong>Deadline:</strong> <?= htmlspecialchars($event['Deadline']) ?></p>
<hr>

<h3>Submit Your Recipe</h3>
<?php if (isset($_SESSION['user'])): ?>
    <form action="../actions/event_submit_recipe.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="EventID" value="<?= $eventId ?>">
        <label>Recipe Name: <input type="text" name="Name" required></label><br>
        <label>Description: <textarea name="Description" required></textarea></label><br>
        <label>Preparation Steps: <textarea name="PreparationSteps" required></textarea></label><br>
        <label>Preparation Time: <input type="text" name="PreparationTime" required></label><br>
        <label>Recipe Image: <input type="file" name="RecipeImage" accept="image/*"></label><br>
        <button type="submit">Submit Recipe</button>
    </form>
<?php else: ?>
    <p>You must be logged in to submit a recipe.</p>
<?php endif; ?>

<hr>
<h3>All Submissions</h3>
<?php foreach ($submissions as $submission): ?>
    <div class="submission">
        <h4><?= htmlspecialchars($submission['Name']) ?> by <?= htmlspecialchars($submission['Username']) ?></h4>
        <p><?= nl2br(htmlspecialchars($submission['Description'])) ?></p>
        <p><strong>Preparation:</strong> <?= nl2br(htmlspecialchars($submission['PreparationSteps'])) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($submission['PreparationTime']) ?></p>
        <?php if (!empty($submission['RecipeImage'])): ?>
            <img src="../uploads/<?= htmlspecialchars($submission['RecipeImage']) ?>" alt="Recipe Image" width="200">
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<button onclick="location.href='sidebar.php'">Back to Homepage</button>
</body>
</html>
