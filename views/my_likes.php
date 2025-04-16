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
$likedPosts = $postModel->getLikedPosts($userId);

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">DishCraft</span>
    </div>
    <div class="dashboard-content2">
        <h2>Posts You Liked</h2>
        <?php if (!empty($likedPosts)): ?>
            <?php foreach ($likedPosts as $post): ?>
                <div class="content-box">
                    <h4><?= htmlspecialchars($post['Title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($post['Content'])) ?></p>
                    <small>Liked on <?= $post['CreatedAt'] ?></small><br>
                    <a href="post.php?id=<?= $post['PostID'] ?>">View</a>
                </div><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You haven’t liked any posts yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php include("footer.php"); ?>