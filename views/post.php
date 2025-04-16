<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controllers/PostController.php';

$controller = new PostController();
$data = $controller->handleRequest();

$post = $data['post'];
$comments = $data['comments'];
$user = $_SESSION['user'];
?>

<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text"><?= htmlspecialchars($post['Title']) ?></span>
    </div>

    <div class="dashboard-content2">
        <h2><?= htmlspecialchars($post['Title']) ?></h2>
        <p class="content-box"><?= nl2br(htmlspecialchars($post['Content'])) ?></p>
        <small>Posted by <?= htmlspecialchars($post['Name']) ?> on <?= htmlspecialchars($post['CreatedAt']) ?></small>

        <?php if ($user['UserID'] === $post['UserID']): ?>
            <div class="editdelete-btn">
                <a href="edit_post.php?id=<?= $post['PostID'] ?>"><button>Edit Post</button></a>

                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    <input type="hidden" name="delete" value="1">
                    <button class="delete-btn" type="submit">Delete Post</button>
                </form>
            </div>
        <?php endif; ?>

        <form method="POST">
            <button name="like">👍 Like (<?= $post['Likes'] ?>)</button>
            <button name="dislike">👎 Dislike (<?= $post['Dislikes'] ?>)</button>
        </form>

        <?php if ($user['UserID'] !== $post['UserID']): ?>
            <form method="POST" action="report_handler.php">
                <input type="hidden" name="report_post" value="1">
                <input type="hidden" name="post_id" value="<?= $post['PostID'] ?>">
                <textarea name="reason" placeholder="Reason for reporting this post" required></textarea><br>
                <button type="submit" style="background: orange;">Report Post</button>
            </form>
        <?php endif; ?>
        
        <br>

        <hr>
        <h3>Comments</h3>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-section">
                    <strong><?= htmlspecialchars($comment['Name']) ?></strong><br>
                    <?= nl2br(htmlspecialchars($comment['Content'])) ?><br>
                    <small><?= htmlspecialchars($comment['CreatedAt']) ?></small>
                    <form method="POST" action="report_handler.php" style="margin-top: 5px;">
                        <input type="hidden" name="report_comment" value="1">
                        <input type="hidden" name="comment_id" value="<?= $comment['CommentID'] ?>">
                        <input type="text" name="reason" placeholder="Reason" required>
                        <button type="submit" style="font-size: small;">Report</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <form class="like-btn" method="POST">
            <textarea name="content" placeholder="Write a comment..." required style="width: 100%; height: 80px;"></textarea><br>
            <button class="comment-btn" name="comment">Post Comment</button>
        </form>

        <p style="margin-top: 20px;"><a href="community.php">← Back to Community</a></p>
    </div>

</section>

<?php include("footer.php"); ?>