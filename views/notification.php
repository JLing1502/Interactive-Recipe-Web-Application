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
        <span class="text">DishCraft</span>
    </div>
    <div class="dashboard-content2">
        <h2>Your Notifications</h2>

        <?php
        if (!empty($notifications)):
            $currentGroup = '';
            foreach ($notifications as $n):
                $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $n['CreatedAt'], new DateTimeZone('UTC'));
                if ($createdAt !== false) {
                    $createdAt->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
                } else {
                    $createdAt = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                }

                $dateLabel = '';

                $today = new DateTime();
                $yesterday = (new DateTime())->modify('-1 day');

                if ($createdAt->format('Y-m-d') === $today->format('Y-m-d')) {
                    $dateLabel = 'Today';
                } elseif ($createdAt->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                    $dateLabel = 'Yesterday';
                } else {
                    $dateLabel = $createdAt->format('F j, Y');
                }

                if ($dateLabel !== $currentGroup):
                    $currentGroup = $dateLabel;
                    echo "<h3 style='margin-top: 20px;'>$dateLabel</h3>";
                endif;

                // Notification target link
                $link = "#";
                if (in_array($n['TypeID'], [1, 2, 3, 4])) {
                    $link = "post.php?id=" . $n['ReferenceID'];
                } elseif ($n['TypeID'] == 5) {
                    $link = "post.php?comment_id=" . $n['ReferenceID']; // optional: resolve to post ID
                }
        ?>
                <div class="content-box">
                    <p>
                        <small><?= $createdAt->format('H:i') ?> 🕒</small>
                        <strong><?= htmlspecialchars($n['TypeName']) ?>:</strong>
                        <a href="<?= $link ?>"><?= htmlspecialchars($n['Message']) ?></a><br>
                    </p>
                </div><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No notifications yet!</p>
        <?php endif; ?>
        
    </div>
</section>

<?php include("footer.php"); ?>