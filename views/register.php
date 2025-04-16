<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();
$error = $auth->register(); // capture error message (if your controller supports it)

// Start output buffering
ob_start();
?>

<h2>Register New Account</h2>

<?php if (!empty($error)): ?>
    <div style="color: red;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" required ><br><br>

    <label>Gender:</label><br>
    <select name="gender" required >
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br><br>

    <label>Birth Date:</label><br>
    <input type="date" name="birthdate" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required ><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required ><br><br>

    <button style=" width: 100%; padding: 8px;" type="submit">Register </button>
</form>

<p style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a></p>

<?php
$content = ob_get_clean();
$title = "Register";
include("auth_layout.php");
?>
