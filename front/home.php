<?php

require_once('../back/src/config.php');
require_once('../back/src/Recette.php');
require_once('../back/src/RecettesManager.php');

$recette = new RecettesManager($db);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front/style/style.css">
    <link rel="stylesheet" href="home.css">
    <title>MyRecipes</title>
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
    <main class="home">
        <div class="videoContainer">
            <a class="overlay" href="#scroll"></a>
            <video src="assets/video/video.mp4" autoplay muted loop start="00:00:30"></video>
        </div>
        <h1 class="titleHome">Découvrez de délicieuses recettes sur MyRecipes</h1>
        <div class="recipesContainer" id="scroll">
            <div class="banner">
                <h1 class="title">Recettes</h1>
                <div class="filter">
                    <a class="addRecipe" href="newRecipe.php"></a>
                    <!-- Faire une boucle pour récupérer toutes les catégories et afficher les recettes en fonction -->
                    <select name="categories" id="categories">
                        <option value="all" selected>Toutes</option>
                        <option value="entree">Entrées</option>
                        <option value="plat">Plats</option>
                        <option value="dessert">Desserts</option>
                    </select>
                    <input type="search" placeholder="Rechercher">
                </div>
            </div>
            <div class="recipes">
                <div class="recipes">
                    <?php
                    $recettesData = $recette->recupererToutesLesRecettes();

                    if (empty($recettesData)) {
                        echo '<p class="empty">Aucune recette n\'a été trouvée.</p>';
                    } else {
                        foreach ($recettesData as $recetteData) {
                            echo '<a href="recipe.php?id=' . $recetteData->getId() . '">'; // Ajouter un lien vers la page de la recette
                            echo '<div class="recipe">';
                            echo '<div class="recipe-image" style="background-image: url(\'' . $recetteData->getImageUrl() . '\')"></div>'; // Utiliser un conteneur div pour l'image
                            echo '<div class="recipeDetails">';
                            echo '<h2>' . $recetteData->getNom() . '</h2>';
                            echo '<p>Difficulté: ';
                            if ($recetteData->getDifficulté() == 1) {
                                echo 'Facile';
                            } elseif ($recetteData->getDifficulté() == 2) {
                                echo 'Moyen';
                            } elseif ($recetteData->getDifficulté() == 3) {
                                echo 'Difficile';
                            } else {
                                echo 'Erreur';
                            }
                            echo '</p>';

                            echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . '</p>';

                            echo '<p>Catégorie : ';
                            if ($recetteData->getIdCategorie() == 1) {
                                echo 'Entrée';
                            } elseif ($recetteData->getIdCategorie() == 2) {
                                echo 'Plat';
                            } elseif ($recetteData->getIdCategorie() == 3) {
                                echo 'Dessert';
                            } else {
                                echo 'Erreur';
                            }
                            echo '</p>';

                            echo '<form method="post" action="supprimer_recette.php">';
                            echo '<input type="hidden" name="recette_id" value="' . $recetteData->getId() . '">';
                            echo '<button type="submit" class="delete-button">Supprimer</button>';
                            echo '</form>';

                            echo '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>