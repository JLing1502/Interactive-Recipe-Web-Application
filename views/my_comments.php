<?php
require_once '../config/Database.php';
require_once '../models/Comment.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['UserID'];
$search = trim($_GET['q'] ?? '');
$comments = [];

$db = (new Database())->connect();
$commentModel = new Comment($db);

if (!empty($search)) {
    // Search user's comments by keyword
    $stmt = $db->prepare("
        SELECT c.*, p.Title 
        FROM COMMENTS c
        JOIN POSTS p ON c.PostID = p.PostID
        WHERE c.UserID = ? AND c.Content LIKE ?
        ORDER BY c.CreatedAt DESC
    ");
    $stmt->execute([$userId, "%$search%"]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default: show all user comments
    $comments = $commentModel->getUserComments($userId);
}

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
  <div class="home-content">
  <i class='bx bx-menu'></i>
    <span class="text">My Comments</span>
    <div class="search-container">
      <form method="GET" action="my_comments.php" class="search-bar">
        <input type="text" name="q" placeholder="Search my comments..." required>
        <button type="submit">
          <i class='bx bx-search-alt-2'></i>
        </button>
      </form>
    </div>
  </div>

  <div class="dashboard-content2">
    <h2>Your Comments</h2>

    <?php if (!empty($search)): ?>
      <p>Showing results for "<strong><?= htmlspecialchars($search) ?></strong>"</p>
      <form method="GET" action="my_comments.php">
          <button type="submit" style="margin-bottom: 20px;font-size: 18px;"> ← Back</button>
      </form>
    <?php endif; ?>

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
