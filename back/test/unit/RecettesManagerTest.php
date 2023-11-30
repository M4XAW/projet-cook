<?php
use PHPUnit\Framework\TestCase;

require_once('src/Recette.php');
require_once('src/RecettesManager.php');

class RecettesManagerTest extends TestCase 
{
    private $pdo;
    private $RecettesManager;

    public function setUp(): void
    {
        $this->configureDatabase();
        $this->RecettesManager = new RecettesManager($this->pdo);
    }

    public function configureDatabase(): void
    {
        $this->pdo = new PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_DATABASE')
            ),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testRecupererToutesLesRecettes() {
        // Appel de la méthode pour récupérer toutes les recettes disponibles
        $toutesLesRecettes = $this->RecettesManager->recupererToutesLesRecettes();

        // Assertions pour vérifier que des recettes ont été récupérées
        $this->assertIsArray($toutesLesRecettes); // Vérifie que $toutesLesRecettes est un tableau
        $this->assertNotEmpty($toutesLesRecettes); // Vérifie que le tableau n'est pas vide
    }

    public function ajouterRecette() {
        // Appel de la méthode pour ajouter une recette
        $this->RecettesManager->ajouterRecette("Pomme", "Facile", "10", "Couper la pomme", 1);

        // Récupération de la recette ajoutée
        $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE nom_recette = 'Pomme'");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Assertions pour vérifier que la recette a bien été ajoutée
        $this->assertEquals("Pomme", $results[0]['nom_recette']);
        $this->assertEquals("Facile", $results[0]['difficulte']);
        $this->assertEquals("10", $results[0]['temps_preparation']);
        $this->assertEquals("Couper la pomme", $results[0]['instructions']);
        $this->assertEquals(1, $results[0]['id_categorie']);
    }

    // public function modifierRecette() {
    //     // Appel de la méthode pour modifier une recette
    //     $this->RecettesManager->modifierRecette(1, "Pomme", "Facile", "10", "Couper la pomme", 1);

    //     // Récupération de la recette modifiée
    //     $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE id_recette = 1");
    //     $stmt->execute();
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Assertions pour vérifier que la recette a bien été modifiée
    //     $this->assertEquals("Pomme", $results[0]['nom_recette']);
    //     $this->assertEquals("Facile", $results[0]['difficulte']);
    //     $this->assertEquals("10", $results[0]['temps_preparation']);
    //     $this->assertEquals("Couper la pomme", $results[0]['instructions']);
    //     $this->assertEquals(1, $results[0]['id_categorie']);
    // }

    // public function supprimerRecette() {
    //     // Appel de la méthode pour supprimer une recette
    //     $this->RecettesManager->supprimerRecette(1);

    //     // Récupération de la recette supprimée
    //     $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE id_recette = 1");
    //     $stmt->execute();
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Assertions pour vérifier que la recette a bien été supprimée
    //     $this->assertEmpty($results);
    // }
}

?>
