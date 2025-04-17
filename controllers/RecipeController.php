<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Recipe.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class RecipeController
{
    private $recipeModel;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->recipeModel = new Recipe($db);
    }

    public function handleRequest()
    {

        // fetch from model
        $categories = $this->recipeModel->getAllCategories();
        $dishes = $this->recipeModel->getAllDishes();
        $ingredients = $this->recipeModel->getAllIngredients();

        return [
            'categories' => $categories,
            'dishes' => $dishes,
            'ingredients' => $ingredients
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_recipe'])) {
            $recipeId = $this->recipeModel->addRecipe($_POST, $_FILES);

            if ($recipeId) {
                $this->recipeModel->addIngredients(
                    $recipeId,
                    $_POST['ingredients'],
                    $_POST['quantities'],
                    $_POST['units']
                );
                header("Location: recipe_success.php");
                exit;
            } else {
                echo "Failed to add recipe.";
            }
        }
    }
}
