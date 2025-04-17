<?php
session_start();
require_once '../config/Database.php';

if (!isset($_SESSION['user']['UserID'])) {
    die("Unauthorized access.");
}

$db = new Database();
$conn = $db->connect();

$userId = $_SESSION['user']['UserID'];
$eventId = $_GET['event_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $steps = $_POST['steps'];
    $time = $_POST['time'];
    $categoryName = $_POST['category_name'];
    $ingredientName = $_POST['ingredient_name'];
    $recipeImage = $_FILES['recipe_image'];

    // Validate image
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($recipeImage['type'], $allowedTypes)) {
        die("Only JPEG and PNG images are allowed.");
    }

    $imagePath = '../uploads/' . basename($recipeImage['name']);
    move_uploaded_file($recipeImage['tmp_name'], $imagePath);

    // Insert category if not exist
    $catStmt = $conn->prepare("SELECT CategoryID FROM category WHERE Name = ?");
    $catStmt->execute([$categoryName]);
    $categoryID = $catStmt->fetchColumn();
    if (!$categoryID) {
        $insertCat = $conn->prepare("INSERT INTO category (Name) VALUES (?)");
        $insertCat->execute([$categoryName]);
        $categoryID = $conn->lastInsertId();
    }

    // Insert ingredient if not exist
    $ingStmt = $conn->prepare("SELECT IngredientID FROM ingredients WHERE Name = ?");
    $ingStmt->execute([$ingredientName]);
    $ingredientID = $ingStmt->fetchColumn();
    if (!$ingredientID) {
        $insertIng = $conn->prepare("INSERT INTO ingredients (Name) VALUES (?)");
        $insertIng->execute([$ingredientName]);
        $ingredientID = $conn->lastInsertId();
    }

    // Insert submission
    $submissionStmt = $conn->prepare("
        INSERT INTO submissions (UserID, EventID, Name, Description, PreparationSteps, PreparationTime, RecipeImage, CategoryID, IngredientID, CreatedAt)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $submissionStmt->execute([
        $userId, $eventId, $name, $description, $steps, $time, $imagePath, $categoryID, $ingredientID
    ]);

    echo "<p style='color: green;'>Recipe submitted successfully!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Recipe</title>
</head>
<body>
    <h2>Submit Your Recipe</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Recipe Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Preparation Steps:</label><br>
        <textarea name="steps" required></textarea><br><br>

        <label>Preparation Time (minutes):</label><br>
        <input type="number" name="time" required><br><br>

        <label>Category Name:</label><br>
        <input type="text" name="category_name" required><br><br>

        <label>Ingredients Name:</label><br>
        <input type="text" name="ingredient_name" required><br><br>

        <label>Upload Image (JPEG/PNG only):</label><br>
        <input type="file" name="recipe_image" accept="image/jpeg, image/png" required><br><br>

        <input type="submit" value="Submit Recipe">
    </form>

    <br>
    <button onclick="history.back()">Go Back</button>
</body>
</html>
