<?php

require_once('../back/src/config.php');
require_once('../back/src/Recette.php');
require_once('../back/src/RecettesManager.php');

$recette = new RecettesManager($db);
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
        <div class="recipesContainer" id="scroll">
            <div class="banner">
                <h1 class="title">Recettes</h1>
                <div class="filter">
                    <!-- Faire une boucle pour récupérer toutes les catégories et afficher les recettes en fonction -->
                    <select name="categories" id="categories">
                        <option value="all">Toutes</option>
                        <option value="entree">Entrées</option>
                        <option value="plat">Plats</option>
                        <option value="dessert">Desserts</option>
                    </select>
                    <form method="GET" action="home.php">
                        <input type="search" name="search" placeholder="Rechercher" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button type="submit">Rechercher</button>
                    </form>
                </div>
            </div>
            <div class="recipes">
            <?php
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $recherche = $_GET['search'];
                    $recettesData = $recetteManager->rechercherRecettes($recherche);
                
                    if (empty($recettesData)) {
                        echo '<p class="empty">Aucune recette n\'a été trouvée pour la recherche "' . $recherche . '".</p>';
                    } else {
                        foreach ($recettesData as $recetteData) {
                            $recetteManager->afficherRecette($recetteData);
                        }
                    }
                } else {
                    $recettesData = $recetteManager->recupererToutesLesRecettes();
                
                    if (empty($recettesData)) {
                        echo '<p class="empty">Aucune recette n\'a été trouvée.</p>';
                    } else {
                        foreach ($recettesData as $recetteData) {
                            $recetteManager->afficherRecette($recetteData);
                        }
                    }
                }

            ?>
            </div>
        </div>
    </main>
</body>

</html>
