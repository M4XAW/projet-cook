<?php
require_once('../back/src/config.php');
require_once('../back/src/Recette.php');
require_once('../back/src/RecettesManager.php');
require_once('../back/src/Ingredient.php');
require_once('../back/src/IngredientManager.php');

$recette = new RecettesManager($db);
$ingredientManager = new IngredientManager($db);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'VotreClasseRecette.php'; // Assurez-vous de remplacer 'VotreClasseRecette' par le nom réel de votre classe
    $recette =

        $recetteId = $_POST['recette_id'];

    $recette->supprimerRecette($recetteId);
    header("Location: home.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front/style/style.css">
    <link rel="stylesheet" href="recipe.css">
    <title>Recette n°
        <?php echo $_GET['id']; ?>
    </title>
</head>

<body>
    <header>
        <a href="home.php">Recettes</a>
        <nav>
            <ul>
                <li><a class="link" href="home.php">Accueil</a></li>
                <li><a class="link" href="recipe.php">Recettes</a></li>
            </ul>
        </nav>
    </header>
    <main class="recipePage">
        <div class="recipeContent">
            <div class="imageContainer">
                <?php
                $recetteData = $recette->recupererRecette($_GET['id']);

                if ($recetteData) {
                    echo '<img src="' . $recetteData->getImageUrl() . '" alt="image recette">';
                } else {
                    echo '<p class="empty">Aucune recette n\'a été trouvée.</p>';
                }
                ?>
            </div>
            <div class="recipeInformations">
                <?php
                if ($recetteData) {
                    echo '<h2>' . $recetteData->getNom() . '</h2>';
                    echo '<p>Difficulté: ';
                    switch ($recetteData->getDifficulté()) {
                        case 1:
                            echo 'Facile';
                            break;
                        case 2:
                            echo 'Moyen';
                            break;
                        case 3:
                            echo 'Difficile';
                            break;
                        default:
                            echo 'Erreur';
                            break;
                    }
                    echo '</p>';
                    echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . '</p>';
                    echo '<p>Instructions: ' . $recetteData->getInstructions() . '</p>';
                    $ingredientManagers = $ingredientManager->recupererTousLesIngredients(); // Modify this based on your logic
                
                    echo '<p>Ingrédients: ';
                    foreach ($ingredientManagers as $ingredientManager) {
                        echo $ingredientManager->getNom() . ', ';
                    }
                    echo '</p>';
                }
                ?>
            </div>
        </div>
        <div class="videoContainerRecipe">
            <video src="assets/video/video.mp4" autoplay muted loop start="00:00:30"></video>
        </div>
    </main>

</body>

</html>