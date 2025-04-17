<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['RoleID'] != 1) {
    header("Location: login.php");
    exit;
}

require_once '../controllers/AdminReportController.php';
$controller = new AdminReportController();
$reports = $controller->index();

include("header.php");
include("sidebar.php");
?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Reported Content</span>
    </div>

    <div class="dashboard-content2">
        <h2>🚩 Reports Submitted by Users</h2>

        <?php if (!empty($reports)): ?>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reported By</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= $report['ReportID'] ?></td>
                            <td><?= htmlspecialchars($report['ReporterName']) ?></td>
                            <td>
                                <?= $report['PostID'] ? 'Post' : 'Comment' ?>
                            </td>
                            <td><?= htmlspecialchars($report['Reason']) ?></td>
                            <td><?= $report['CreatedAt'] ?></td>
                            <td>
                                <?php if ($report['PostID']): ?>
                                    <a href="post.php?id=<?= $report['PostID'] ?>" target="_blank">View Post</a>
                                <?php elseif ($report['CommentID']): ?>
                                    <a href="post.php?id=<?= $report['ResolvedPostID'] ?>&highlight_comment=<?= $report['CommentID'] ?>" target="_blank">View Comment</a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No reports found.</p>
        <?php endif; ?>
    </div>
</section>

<?php include("footer.php"); ?>