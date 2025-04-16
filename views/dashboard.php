<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>

<section class="home-section">
  <div class="home-content">
    <i class='bx bx-menu'></i>
    <span class="text">Dashboard</span>
  </div>

  <div class="dashboard-content">
    <h2>Hello, <?= htmlspecialchars($user['Name']) ?>!</h2>
    <p>Your email: <?= htmlspecialchars($user['Email']) ?></p>
    <p>Your role ID: <?= $user['RoleID'] ?></p>

    <a href="community.php">Go to Community</a>
    <br>
    <a href="logout.php">Logout</a>
  </div>
</section>

<?php include("footer.php"); ?>
