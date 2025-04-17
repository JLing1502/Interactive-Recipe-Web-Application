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
$highlight = $_GET['highlight_comment'] ?? null;
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
                <form method="POST" action="post.php?id=<?= $post['PostID'] ?>" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display:inline;">
                    <input type="hidden" name="delete" value="1">
                    <input type="hidden" name="post_id" value="<?= $post['PostID'] ?>">
                    <button class="delete-btn" type="submit">Delete Post</button>
                </form>
            </div>
        <?php elseif ($user['RoleID'] == 1): ?>
            <div class="editdelete-btn">
                <form method="POST" action="post.php?id=<?= $post['PostID'] ?>" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display:inline;">
                    <input type="hidden" name="delete" value="1">
                    <input type="hidden" name="post_id" value="<?= $post['PostID'] ?>">
                    <button class="delete-btn" type="submit" style="background-color: red; color: white;">Delete Post</button>
                </form>
            </div>
        <?php endif; ?>


        <form method="POST">
            <button name="like">👍 Like (<?= $post['Likes'] ?>)</button>
            <button name="dislike">👎 Dislike (<?= $post['Dislikes'] ?>)</button>
        </form>

        <?php if ($user['UserID'] !== $post['UserID']): ?>
            <form method="POST" action="report_handler.php" onsubmit="return confirm('Submit this report?');">
                <input type="hidden" name="report_post" value="1">
                <input type="hidden" name="post_id" value="<?= $post['PostID'] ?>">

                <!-- Toggle button -->
                <button type="button" onclick="toggleReason('post-reason')" style="text-decoration: underline;">Report Post</button>

                <!-- Hidden textarea -->
                <div id="post-reason" style="display: none; margin-top: 5px;">
                    <textarea name="reason" placeholder="Reason for reporting" required></textarea><br>
                    <button class="submit-reason" type="submit">Submit Report</button>
                </div>
            </form>
        <?php endif; ?>
        <br>
        <hr>
        <h3>Comments</h3>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-section" id="comment-<?= $comment['CommentID'] ?>"
                    style="<?= ($highlight == $comment['CommentID']) ? 'background-color: #fff8c6; border-left: 5px solid orange;' : '' ?>">
                    <strong><?= htmlspecialchars($comment['Name']) ?></strong><br>
                    <?= nl2br(htmlspecialchars($comment['Content'])) ?><br>
                    <small><?= htmlspecialchars($comment['CreatedAt']) ?></small>

                    <div style="margin-top: 5px;">
                        <?php if ($user['UserID'] === $comment['UserID'] || $user['RoleID'] == 1): ?>
                            <form method="POST" action="post.php?id=<?= $post['PostID'] ?>" onsubmit="return confirm('Are you sure you want to delete this comment?');" style="display:inline;">
                                <input type="hidden" name="delete_comment" value="1">
                                <input type="hidden" name="comment_id" value="<?= $comment['CommentID'] ?>">
                                <button class="comment-delete" type="submit" style="background-color: crimson; color: white; border: none; padding: 4px 8px; margin-right: 10px;">Delete</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($user['UserID'] !== $comment['UserID']): ?>
                            <form method="POST" action="post.php?id=<?= $post['PostID'] ?>" onsubmit="return confirm('Submit this report?');" style="display:inline;">
                                <input type="hidden" name="report_comment" value="1">
                                <input type="hidden" name="comment_id" value="<?= $comment['CommentID'] ?>">
                                <input type="hidden" name="post_id" value="<?= $post['PostID'] ?>">

                                <button type="button" onclick="toggleReason('comment-reason-<?= $comment['CommentID'] ?>')" style="text-decoration: underline;">Report</button>

                                <div id="comment-reason-<?= $comment['CommentID'] ?>" style="display: none; margin-top: 5px;">
                                    <input type="text" name="reason" placeholder="Reason" required>
                                    <button class="comment-report" type="submit">Submit</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
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

<script>
    function toggleReason(id) {
        const reasonBox = document.getElementById(id);
        if (reasonBox.style.display === "none") {
            reasonBox.style.display = "block";
        } else {
            reasonBox.style.display = "none";
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const highlighted = document.querySelector("[id^='comment-'][style*='background-color']");
        if (highlighted) {
            highlighted.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>


<?php include("footer.php"); ?>