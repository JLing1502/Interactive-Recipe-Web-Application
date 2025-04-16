<?php
session_start();
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();
$error = $auth->login();

// Output buffer lets us capture login form as $content
ob_start();
?>

<h2>Login to Your Account</h2>

<?php if (!empty($error)): ?>
  <div style="color: red;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
  <label>Email:</label><br>
  <input type="email" name="email" required ><br><br>

  <label>Password:</label><br>
  <input type="password" name="password" required ><br><br>

  <button type="submit">Login</button>
</form>

<p style="margin-top: 15px;">Don't have an account? <a href="register.php">Register here</a></p>

<?php
$content = ob_get_clean();
$title = "Login";
include("auth_layout.php");
?>
