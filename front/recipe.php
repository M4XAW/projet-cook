<?php

require_once('../back/src/config.php');
require_once('../back/src/Recette.php');
require_once('../back/src/RecettesManager.php');
require_once('../back/src/Ingredient.php');
require_once('../back/src/IngredientManager.php');

$recetteManager = new RecettesManager($db);
$ingredientManager = new IngredientManager($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recette_id'])) {
    $recetteId = $_POST['recette_id'];

    $resultatSuppression = $recetteManager->supprimerRecetteAvecIngredients($recetteId);

    if ($resultatSuppression) {
        header('Location: home.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de la recette.";
    }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_recette_id'])) {
//     $editRecetteId = $_POST['edit_recette_id'];
//     $editedNom = $_POST['edited_nom'];
//     $editedDifficulte = $_POST['edited_difficulte'];
//     // Ajoutez d'autres champs selon vos besoins

//     $resultatModification = $recetteManager->modifierRecetteAvecIngredients($editRecetteId, $editedNom, $editedDifficulte)
    
//     if ($resultatModification) {
//         header('Location: recipe.php?id=' . $editRecetteId);
//         exit();
//     } else {
//         echo "Erreur lors de la modification de la recette.";
//     }
// }

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
        <a href="home.php">MyRecipes</a>
        <nav>
            <ul>
                <li><a class="link" href="home.php">Accueil</a></li>
                <li><a class="link" href="recipe.php">Recettes</a></li>
            </ul>
        </nav>
    </header>
    <main class="recipePage">
        <div class="recipeContent">
            <div class="buttons">
                <?php

                $recetteData = $recetteManager->recupererRecette($_GET['id']);

                echo '<form method="post" action="recipe.php">';
                echo '<input type="hidden" name="recette_id" value="' . $recetteData->getId() . '">';
                echo '<button type="submit" class="deleteButton"></button>';
                echo '</form>';
                
                echo '<form method="post" action="recipe.php">';
                echo '<input type="hidden" name="recette_id" value="' . $recetteData->getId() . '">';
                echo '<button type="submit" class="editButton"></button>';
                echo '</form>';
                ?>
            </div>

            <div class="imageContainer">
                <?php
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
                    echo '<div class="titleContainer">';
                    echo '<h2>' . $recetteData->getNom() . '</h2>';
                    echo '<p>';
                    switch ($recetteData->getDifficulté()) {
                        case 1:
                            echo '<span style="color: #4CAF50;">Facile</span>';
                            break;
                        case 2:
                            echo '<span style="color: #FFC107;">Moyen</span>';
                            break;
                        case 3:
                            echo '<span style="color: #FF5722;">Difficile</span>';
                            break;
                        default:
                            echo '<span;">Erreur</span>';
                            break;
                    }
                    echo '</p>';                    
                    
                    echo '</div>';

                    echo '<p class="preparationTime">Temps de préparation : ' . $recetteData->getTempsPréparation() . 'min</p>';
                    echo '<p class="instructions">Instructions : ' . $recetteData->getInstructions() . '</p>';
                    $ingredientsRecette = $ingredientManager->recupererIngredientsParRecette($recetteData->getId());
                
                    if ($recetteData) {
                        echo '<p>Ingrédients :</p>';
                        echo '<ul class="ingredientsList">';
                        foreach ($ingredientsRecette as $ingredientRecette) {
                            echo '<li>• ' . $ingredientRecette->getNom() . ' (' . $ingredientRecette->getQuantite() . ' ' . $ingredientRecette->getUnite() . ')</li>';
                        }
                        echo '</ul>';
                    }
                    
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