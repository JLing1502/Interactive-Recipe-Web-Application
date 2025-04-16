<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controllers/CommunityController.php';

$controller = new CommunityController();
$posts = $controller->handleRequest();

$user = $_SESSION['user'];
?>

<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>

<section class="home-section">
  <div class="home-content">
    <i class='bx bx-menu'></i>
    <span class="text">Community Feed</span>
  </div>

  <div class="dashboard-content" >
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Post title" required><br><br>
        <textarea name="content" placeholder="Write your post..." required ></textarea><br>
        <button type="submit">Post</button>
    </form>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-content" >
                <h3><?= htmlspecialchars($post['Title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['Content'])) ?></p>
                <small>Posted by: <?= htmlspecialchars($post['Name']) ?> on <?= htmlspecialchars($post['CreatedAt']) ?></small><br>
                <p>👍 <?= $post['Likes'] ?> | 👎 <?= $post['Dislikes'] ?></p>
                <a href="post.php?id=<?= $post['PostID'] ?>">View & Comment</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts yet. Be the first to post!</p>
    <?php endif; ?>

    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</section>

<?php include("footer.php"); ?>
