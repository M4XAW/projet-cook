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

    // public function testAjouterRecette() {
    //     // Appel de la méthode pour ajouter une recette
    //     $this->RecettesManager->ajouterRecette("Pommes", 1, 1, "Couper la pomme", "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.7",3);
    
    //     // Vérification que la recette a bien été ajoutée
    //     $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE nom_recette = 'Pommes'");
    //     $stmt->execute();
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //     // Vérifiez si des résultats ont été retournés par la requête SQL
    //     $this->assertGreaterThan(0, count($results), "Aucune recette trouvée pour le nom spécifié");
    
    //     // Vérifiez si la recette ajoutée correspond aux informations fournies
    //     if (count($results) > 0) {
    //         $this->assertEquals("Pommes", $results[0]['nom_recette']);
    //     }
    // }

    // public function testsupprimerRecette(){
    //     // Appel de la méthode pour supprimer une recette
    //     $this->RecettesManager->supprimerRecette(3);

    //     // Vérification que la recette a bien été supprimée
    //     $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE id_recette = 3");
    //     $stmt->execute();
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Vérifiez si des résultats ont été retournés par la requête SQL
    //     $this->assertEquals(0, count($results), "La recette n'a pas été supprimée");
    // }

    public function testModifierRecette() {
        // Appel de la méthode pour modifier une recette
        $this->RecettesManager->modifierRecette(2, "Pommes", "Facile", 1, "Couper la pomme", "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.7", 3);
    
        // Vérification que la recette a bien été modifiée
        $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE id_recette = 2");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Vérifiez si des résultats ont été retournés par la requête SQL
        $this->assertGreaterThan(0, count($results), "La recette n'a pas été trouvée");
    
        // Vérifiez si la recette modifiée correspond aux informations fournies
        if (count($results) > 0) {
            $modifiedRecipe = $results[0];
            $this->assertEquals("Pommes", $modifiedRecipe['nom_recette']);
            $this->assertEquals("Facile", $modifiedRecipe['difficulte']);
            $this->assertEquals(1, $modifiedRecipe['temps_preparation']);
            $this->assertEquals("Couper la pomme", $modifiedRecipe['instructions']);
            $this->assertEquals("https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.7", $modifiedRecipe['image_url']);
            $this->assertEquals(3, $modifiedRecipe['id_categorie']);
            
        }
    }
    
    

    
    
    
}

?>
