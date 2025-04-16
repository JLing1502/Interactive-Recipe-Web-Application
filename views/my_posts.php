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
$search = trim($_GET['q'] ?? '');
$posts = [];

if (!empty($search)) {
    // Search only user's posts by keyword
    $stmt = (new Database())->connect()->prepare("
        SELECT * FROM POSTS 
        WHERE UserID = ? AND (Title LIKE ? OR Content LIKE ?)
        ORDER BY CreatedAt DESC
    ");
    $stmt->execute([$userId, "%$search%", "%$search%"]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Show all posts by default
    $posts = $postModel->getUserPosts($userId);
}


include("header.php");
include("sidebar.php");
?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">DishCraft</span>
        <span>
            <div class="search-container">
                <form method="GET" action="my_posts.php" class="search-bar">
                    <input type="text" name="q" placeholder="Search..." required>
                    <button type="submit">
                        <i class='bx bx-search-alt-2'></i>
                    </button>
                </form>
            </div>
        </span>
    </div>

    <div class="dashboard-content2">
        <h2>Your Posts</h2>
        <?php if (!empty($search)): ?>
            <p>Showing results for "<strong><?= htmlspecialchars($search) ?></strong>"</p>
        <?php endif; ?>

        <?php if (!empty($search)): ?>
            <form method="GET" action="my_posts.php">
                <button type="submit" style="margin-top: 10px; font-size: 18px;"> ← Back </button>
            </form><br>
        <?php endif; ?>


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
            <p>Nothing to see at the moment...</p>
        <?php endif; ?>
    </div>
</section>

<?php include("footer.php"); ?>