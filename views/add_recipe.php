<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controllers/RecipeController.php';

$controller = new RecipeController();
$recipes = $controller->handleRequest();

$user = $_SESSION['user'];
?>

<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Discraft My Recipe</span>
    </div>

    <div class="dashboard-content2">
        <form action="add_recipe.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Recipe Name" required><br>

            <select name="category_id">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['CategoryID'] ?>"><?= $cat['Name'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="dish_id">
                <?php foreach ($dishes as $dish): ?>
                    <option value="<?= $dish['DishID'] ?>"><?= $dish['DishName'] ?></option>
                <?php endforeach; ?>
            </select>

            <textarea name="description" placeholder="Description"></textarea><br>
            <textarea name="steps" placeholder="Preparation Steps"></textarea><br>
            <input type="number" name="prep_time" placeholder="Prep Time (minutes)"><br>
            <input type="file" name="image"><br>

            <!-- Ingredients (can use JS to dynamically add more) -->
            <div id="ingredients">
                <div>
                    <select name="ingredients[]">
                        <?php foreach ($ingredients as $ing): ?>
                            <option value="<?= $ing['IngredientID'] ?>"><?= $ing['Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="quantities[]" placeholder="Quantity"><br>
                    <input type="text" name="units[]" placeholder="Unit"><br>
                </div>
            </div>

            <button type="submit" name="add_recipe">Add Recipe</button>
        </form>
    </div>
</section>

<?php include("footer.php"); ?>