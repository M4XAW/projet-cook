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
    <link rel="stylesheet" href="recipe.css">
    <title>Document</title>
</head>

<body>
    <header>
        <a href="home.php">Recettes</a>
        <nav>
            <ul>
                <li><a class="link" href="home.php">Accueil</a></li>
                <li><a class="link" href="recipe.php">Recettes</a></li>
                <li><a class="link" href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main class="recipe">
        <div class="banner">
            <h1>Recettes</h1>
            <input type="search" placeholder="Rechercher une recette">
        </div>
        <div class="recipes">
            <?php
                $recettesData = $recette->recupererToutesLesRecettes();

                foreach ($recettesData as $recetteData) {
                    echo '<div>';
                    echo '<h2>' . $recetteData->getNom() . '</h2>';
                    echo '<p>Difficulté: ' . $recetteData->getDifficulté() . '</p>';
                    echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . '</p>';
                    echo '<p>Instructions: ' . $recetteData->getInstructions() . '</p>';
                    echo '<p>Catégorie: ' . $recetteData->getIdCategorie() . '</p>';
                    echo '</div>';
                }
            ?>
        </div>
    </main>
</body>

</html>