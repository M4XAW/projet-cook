<?php

require_once('../back/src/config.php');
require_once('../back/src/Recette.php');
require_once('../back/src/RecettesManager.php');

$recetteManager = new RecettesManager($db);

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
                    <form class="searchForm" method="GET" action="home.php">
                        <input type="search" name="search" placeholder="Rechercher"
                            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button class="searchButton" type="submit"></button>
                    </form>
                </div>
            </div>

            <div class="recipes">
                <?php
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $recherche = $_GET['search'];

                    // Utiliser la fonction generale pour rechercher par nom de recette et ingrédient
                    $recettesData = $recetteManager->rechercherRecettes($recherche);

                    if (empty($recettesData)) {
                        echo '<p class="empty">Aucune recette n\'a été trouvée pour la recherche "' . $recherche . '".</p>';
                    } else {
                        afficherRecettes($recettesData);
                    }
                } else {
                    // Si aucune recherche n'est effectuée, afficher toutes les recettes de la catégorie sélectionnée
                    $categorieFilter = isset($_GET['categories']) ? $_GET['categories'] : 'all';

                    // Récupérer toutes les recettes ou celles d'une catégorie spécifique
                    if ($categorieFilter == 'all') {
                        $recettesData = $recetteManager->recupererToutesLesRecettes();
                        // } else {
                        //     $recettesData = $recetteManager->recupererRecettesParCategorie($categorieFilter);
                    }

                    if (empty($recettesData)) {
                        echo '<p class="empty">Aucune recette n\'a été trouvée.</p>';
                    } else {
                        afficherRecettes($recettesData);
                    }
                }

                function afficherRecettes($recettesData)
                {
                    foreach ($recettesData as $recetteData) {
                        afficherRecette($recetteData);
                    }
                }

                function afficherRecette($recetteData)
                {
                    echo '<a href="recipe.php?id=' . $recetteData->getId() . '">'; // Ajouter un lien vers la page de la recette
                    echo '<div class="recipe">';
                    echo '<div class="recipe-image" style="background-image: url(\'' . $recetteData->getImageUrl() . '\')"></div>'; // Utiliser un conteneur div pour l'image
                    echo '<div class="recipeDetails">';
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
                    echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . 'min</p>';

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

                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }

                ?>
            </div>
        </div>
    </main>
</body>

</html>