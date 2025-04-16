<?php
require_once '../config/Database.php';
require_once '../models/Comment.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['UserID'];
$commentModel = new Comment((new Database())->connect());
$comments = $commentModel->getUserComments($userId);

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
  <div class="home-content"><span class="text">My Comments</span></div>
  <div class="dashboard-content2">
    <h2>Your Comments</h2>
    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $c): ?>
            <div class="content-box">
                <p><strong>On post:</strong> <?= htmlspecialchars($c['Title']) ?></p>
                <p><?= nl2br(htmlspecialchars($c['Content'])) ?></p>
                <small>Commented on <?= $c['CreatedAt'] ?></small>
                <br><a href="post.php?id=<?= $c['PostID'] ?>">View Post</a>
            </div><br>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven’t commented on anything yet.</p>
    <?php endif; ?>
  </div>
</section>

<?php include("footer.php"); ?>
