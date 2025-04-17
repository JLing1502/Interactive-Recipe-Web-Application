<?php
class Recipe
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addRecipe($data, $files)
    {
        $imgName = time() . '_' . basename($files['image']['name']);
        $targetPath = "uploads/" . $imgName;
        move_uploaded_file($files['image']['tmp_name'], $targetPath);

        $sql = "INSERT INTO RECIPES (CategoryID, Name, Description, PreparationSteps, PreparationTime, DishID, RecipeImage)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['steps'],
            $data['prep_time'],
            $data['dish_id'],
            $targetPath
        ]);

        return $this->conn->lastInsertId();
    }

    public function addIngredients($recipeId, $ingredients, $quantities, $units)
    {
        $sql = "INSERT INTO RECIPEINGREDIENTS (RecipeID, IngredientID, Quantity, Unit) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        for ($i = 0; $i < count($ingredients); $i++) {
            $stmt->execute([$recipeId, $ingredients[$i], $quantities[$i], $units[$i]]);
        }
    }

    // Fetch categories
    public function getAllCategories()
    {
        $stmt = $this->conn->query("SELECT * FROM CATEGORY");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch dishes
    public function getAllDishes()
    {
        $stmt = $this->conn->query("SELECT * FROM DISH");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch ingredients
    public function getAllIngredients()
    {
        $stmt = $this->conn->query("SELECT * FROM INGREDIENTS");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

