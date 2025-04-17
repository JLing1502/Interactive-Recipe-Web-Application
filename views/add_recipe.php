<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controllers/RecipeController.php';

$controller = new RecipeController();
$data = $controller->handleRequest();
$categories = $data['categories'];
$dishes = $data['dishes'];
$ingredients = $data['ingredients'];


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
            <input type="text" name="name" placeholder="Recipe Name" required><br><br>

            <select name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['CategoryID'] ?>"><?= $cat['Name'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="dish_id" required>
                <?php foreach ($dishes as $dish): ?>
                    <option value="<?= $dish['DishID'] ?>"><?= $dish['DishName'] ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <textarea name="description" placeholder="Description"></textarea><br><br>
            <textarea name="steps" placeholder="Preparation Steps"></textarea><br><br>
            <input type="number" name="prep_time" placeholder="Prep Time (minutes)"><br><br>
            <input type="file" name="image"><br><br>

            <!-- Ingredients (can use JS to dynamically add more) -->
            <div id="ingredients">
                <div class="ingredient-row">
                    <select name="ingredients[]">
                        <option value="">-- Select Existing Ingredient --</option>
                        <?php foreach ($ingredients as $ing): ?>
                            <option value="<?= $ing['IngredientID'] ?>"><?= $ing['Name'] ?></option>
                        <?php endforeach; ?>
                    </select><br><br>

                    <input type="text" name="new_ingredients[]" placeholder="Or enter new ingredient"><br><br>
                    <input type="text" name="quantities[]" placeholder="Quantity"><br><br>
                    <input type="text" name="units[]" placeholder="Unit"><br><br>
                    <button type="button" onclick="removeIngredient(this)">Remove Ingredients</button>
                </div>
            </div>
            
           <!---<div class="add-recipe">--->
            <button class="recipe-add" type="submit" name="add_recipe">Add Recipe</button>
            <button type="button" onclick="addIngredient()">+ Add Another Ingredient</button>
           <!--- </div> --->
        </form>
    </div>
</section>

<script>
    function addIngredient() {
        const ingredientsDiv = document.getElementById('ingredients');

        const row = document.createElement('div');
        row.classList.add('ingredient-row');
        row.innerHTML = `
        <select name="ingredients[]">
            <option value="">-- Select Existing Ingredient --</option>
            <?php foreach ($ingredients as $ing): ?>
                <option value="<?= $ing['IngredientID'] ?>"><?= $ing['Name'] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="new_ingredients[]" placeholder="Or enter new ingredient">
        <input type="text" name="quantities[]" placeholder="Quantity">
        <input type="text" name="units[]" placeholder="Unit">
        <button type="button" onclick="removeIngredient(this)">Remove</button>
    `;
        ingredientsDiv.appendChild(row);
    }

    function removeIngredient(button) {
        button.parentElement.remove();
    }
</script>

<?php include("footer.php"); ?>