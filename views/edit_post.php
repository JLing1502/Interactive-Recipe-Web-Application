<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controllers/EditPostController.php';
$controller = new EditPostController();
$data = $controller->handleRequest();
$post = $data['post'];
$error = $data['error'] ?? null;
?>

<?php
include("header.php");
include("sidebar.php");
?>

<section class="home-section">
  <div class="home-content">
    <i class='bx bx-menu'></i>
    <span class="text">Edit Post</span>
  </div>

  <div class="dashboard-content2">
    <h2>Edit Your Post</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="update-btn" method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($post['Title']) ?>" required ><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" required style="width: 100%;"><?= htmlspecialchars($post['Content']) ?></textarea><br><br>

        <button type="submit">Update Post</button>
        <a href="post.php?id=<?= $post['PostID'] ?>" style="margin-left: 10px;">Cancel</a>
    </form>
  </div>
</section>

<?php include("footer.php"); ?>
