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
    
    $ingredients = isset($_POST['ingredients']) ? $_POST['ingredients'] : []; // Si $_POST['ingredients'] n'existe pas, on lui donne une valeur par défaut (un tableau vide)

    $ingredientsData = [];
    foreach ($ingredients as $ingredientId => $ingredientValues) { // Pour chaque ingrédient, on récupère son id et ses valeurs
        $isSelected = isset($ingredientValues['selected']) ? $ingredientValues['selected'] : 0; // Si l'ingrédient est sélectionné, on récupère sa quantité et son unité
        $quantity = isset($ingredientValues['quantity']) ? $ingredientValues['quantity'] : null; 
        $unit = isset($ingredientValues['unit']) ? $ingredientValues['unit'] : null;

        if ($isSelected) { // Si l'ingrédient est sélectionné, on l'ajoute à la liste des ingrédients de la recette
            $ingredientsData[$ingredientId] = [ // On ajoute l'ingrédient à la liste des ingrédients de la recette
                'quantity' => $quantity,
                'unit' => $unit,
            ];
        }
    }

    $id_recette = $recettesManager->ajouterRecette($nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie);

    $ingredientManager->ajouterIngredients($ingredientsData, $id_recette);

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
            <div class="ingredientContainerScroll">
                <?php
                $ingredients = $ingredientManager->recupererTousLesIngredients();
                foreach ($ingredients as $ingredient) {
                    echo '<div class="ingredientContainer">';
                    echo '<input type="checkbox" id="' . $ingredient->getNom() . '" name="ingredients[' . $ingredient->getId() . '][selected]" value="1" class="checkboxInput">';
                    echo '<label for="' . $ingredient->getNom() . '" class="checkboxLabel">' . $ingredient->getNom() . '</label>';
                    echo '<label for="' . $ingredient->getNom() . '_quantity" class="quantityLabel"></label>';
                    echo '<input type="text" id="' . $ingredient->getNom() . '_quantity" name="ingredients[' . $ingredient->getId() . '][quantity]" placeholder="Quantité">';
                    echo '<label for="' . $ingredient->getNom() . '_unit" class="unitLabel"></label>';
                    echo '<select id="' . $ingredient->getNom() . '_unit" name="ingredients[' . $ingredient->getId() . '][unit]">';
                    echo '<option value="1">grammes</option>';
                    echo '<option value="2">millilitres</option>';
                    echo '<option value="3">cuillères à soupe</option>';
                    echo '<option value="4">cuillères à café</option>';
                    echo '<option value="5">unité</option>';
                    echo '</select>';
                    echo '</div>';
                }
                ?>
            </div>

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