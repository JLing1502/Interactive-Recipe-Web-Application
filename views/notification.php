<?php
require_once '../controllers/NotificationController.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['UserID'];
$notifications = (new NotificationController())->getForUser($userId);

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Notifications</span>
    </div>
    <div class="dashboard-content2">
        <h2>Your Notifications</h2>

        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $n): ?>
                <div class="content-box">
                    <?php
                    $link = "#";
                    if (in_array($n['TypeID'], [1, 2, 3, 4])) {
                        $link = "post.php?id=" . $n['ReferenceID'];
                    } elseif ($n['TypeID'] == 5) {
                        // You'll need to resolve postId from commentId if needed
                        $link = "post.php?comment_id=" . $n['ReferenceID'];
                    }
                    ?>
                    <p>
                        <strong><?= htmlspecialchars($n['TypeName']) ?>:</strong>
                        <a href="<?= $link ?>"><?= htmlspecialchars($n['Message']) ?></a>
                    </p>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No notifications yet!</p>
        <?php endif; ?>
    </div>
</section>

<?php include("footer.php"); ?>