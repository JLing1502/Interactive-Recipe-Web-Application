<?php
require_once '../config/Database.php';
require_once '../models/Post.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['UserID'];
$postModel = new Post((new Database())->connect());
$posts = $postModel->getUserPosts($userId);

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
  <div class="home-content"><span class="text">My Posts</span></div>
  <div class="dashboard-content2">
    <h2>Your Posts</h2>
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="content-box">
                <h4><?= htmlspecialchars($post['Title']) ?></h4>
                <p><?= nl2br(htmlspecialchars($post['Content'])) ?></p>
                <small>Posted on <?= $post['CreatedAt'] ?></small><br>
                <a href="post.php?id=<?= $post['PostID'] ?>">View</a>
            </div><br>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't posted anything yet.</p>
    <?php endif; ?>
  </div>
</section>

<?php include("footer.php"); ?>
