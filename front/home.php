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
            <div class="overlay"></div>
            <video src="assets/video/video.mp4" autoplay muted loop start="00:00:30"></video>
        </div>
        <div class="recipesContainer" id="scroll">
            <div class="banner">
                <h1 class="title">Recettes</h1>
                <input type="search" placeholder="Rechercher">
            </div>
            <div class="recipes">
                <?php
                    $recettesData = $recette->recupererToutesLesRecettes();

                    foreach ($recettesData as $recetteData) {
                        echo '<div class="recipe">';
                        echo '<img src="' . $recetteData->getUrlImage() . '" alt="image recette">';
                        echo '<h2>' . $recetteData->getNom() . '</h2>';
                        echo '<p>Difficulté: ' . $recetteData->getDifficulté() . '</p>';
                        echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . '</p>';
                        // echo '<p>Instructions: ' . $recetteData->getInstructions() . '</p>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </main>
</body>

</html>