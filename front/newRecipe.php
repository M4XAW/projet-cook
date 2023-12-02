<?php
include_once("../back/src/config.php");
include_once("../back/src/RecettesManager.php");
include_once("../back/src/IngredientManager.php");

$recettesManager = new RecettesManager($db);
$ingredientManager = new IngredientManager($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $difficulte = $_POST['difficulty'];
    $temps_preparation = $_POST['temps_preparation'];
    $instructions = $_POST['instructions'];
    $image_url = $_POST['image_url'];
    $id_categorie = $_POST['category'];

    // Récupérer les ingrédients sélectionnés
    $ingredients = isset($_POST['ingredients']) ? $_POST['ingredients'] : [];

    // Ajouter la recette avec les ingrédients
    $recettesManager->ajouterRecette($nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie, $ingredients);

    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front/style/style.css">
    <link rel="stylesheet" href="newRecipe.css">
    <title>Nouvelle recette</title>
</head>

<body>
    <header>
        <a href="home.php">MyRecipes</a>
        <nav>
            <ul>
                <li><a href="home.php">Accueil</a></li>
                <li><a href="#scroll">Recettes</a></li>
            </ul>
        </nav>
    </header>
    <main class="newRecipe">
        <form class="formNewRecipes" action="newRecipe.php" method="post">
            <label for="nom">Nom de la recette:</label>
            <input type="text" id="nom" name="nom" required>

            <label for="difficulty">Difficulté:</label>
            <select class="choiceDifficulty" name="difficulty" id="difficulty">
                <option value="1" selected>Facile</option>
                <option value="2">Moyen</option>
                <option value="3">Difficile</option>
            </select>

            <label for="temps_preparation">Temps de préparation (en minutes) :</label>
            <input type="number" id="temps_preparation" name="temps_preparation" required>

            <label for="ingredients">Ingrédients:</label>

            <?php
                $ingredients = $ingredientManager->recupererTousLesIngredients();
                foreach ($ingredients as $ingredient) {
                    echo '<div class="checkboxContainer">';
                    echo '<input type="checkbox" id="' . $ingredient->getNom() . '" name="ingredients[]" value="' . $ingredient->getId() . '" class="checkboxInput">';
                    echo '<label for="' . $ingredient->getNom() . '" class="checkboxLabel">' . $ingredient->getNom() . '</label>';
                    echo '</div>';
                }
            ?>

            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" required></textarea>

            <label for="image_url">URL de l'image:</label>
            <input type="text" id="image_url" name="image_url" required>

            <label for="category">Catégorie:</label>
            <select class="choiceCategory" name="category" id="category">
                <option value="1" selected>Entrées</option>
                <option value="2">Plats</option>
                <option value="3">Desserts</option>
            </select>

            <button type="submit">Ajouter la recette</button>
        </form>
        <div class="videoContainer">
            <video src="assets/video/video.mp4" autoplay muted loop start="00:00:30"></video>
        </div>
    </main>
</body>

</html>