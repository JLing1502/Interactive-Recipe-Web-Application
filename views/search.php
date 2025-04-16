<?php
require_once __DIR__ . '/../config/Database.php';
session_start();

$db = (new Database())->connect();
$keyword = trim($_GET['q'] ?? '');

$posts = [];
$comments = [];

if (!empty($keyword)) {
    // Search in posts
    $stmt1 = $db->prepare("SELECT p.*, u.Name 
        FROM POSTS p 
        JOIN USERS u ON p.UserID = u.UserID 
        WHERE p.Title LIKE ? OR p.Content LIKE ?");
    $stmt1->execute(["%$keyword%", "%$keyword%"]);
    $posts = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Search in comments
    $stmt2 = $db->prepare("SELECT c.*, u.Name, p.Title AS PostTitle 
        FROM COMMENTS c 
        JOIN USERS u ON c.UserID = u.UserID 
        JOIN POSTS p ON c.PostID = p.PostID 
        WHERE c.Content LIKE ?");
    $stmt2->execute(["%$keyword%"]);
    $comments = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>

<section class="home-section">
    <div class="home-content">
        <span class="text">Search Results for "<?= htmlspecialchars($keyword) ?>"</span>
    </div>

    <div class="dashboard-content2">
        <h3>📄 Matching Posts</h3>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="content-box">
                    <h4><?= htmlspecialchars($post['Title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($post['Content'])) ?></p>
                    <small>By <?= htmlspecialchars($post['Name']) ?> on <?= $post['CreatedAt'] ?></small><br>
                    <a href="post.php?id=<?= $post['PostID'] ?>">View Post</a>
                </div><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No matching posts found.</p>
        <?php endif; ?>

        <h3 style="margin-top: 30px;">💬 Matching Comments</h3>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="content-box">
                    <strong><?= htmlspecialchars($comment['Name']) ?></strong> commented on <em><?= htmlspecialchars($comment['PostTitle']) ?></em><br>
                    <p><?= nl2br(htmlspecialchars($comment['Content'])) ?></p>
                    <small><?= $comment['CreatedAt'] ?></small><br>
                    <a href="post.php?id=<?= $comment['PostID'] ?>">View Post</a>
                </div><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No matching comments found.</p>
        <?php endif; ?>
        <br><a href="community.php">← Back to Community Feed</a>
    </div>
</section>

<?php include("footer.php"); ?>
