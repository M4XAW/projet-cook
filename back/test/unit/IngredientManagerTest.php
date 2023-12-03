<?php
use PHPUnit\Framework\TestCase;

require_once('src/IngredientManager.php');
require_once('src/Ingredient.php');

class IngredientManagerTest extends TestCase
{

    private $pdo;
    private $IngredientManager;

    public function setUp(): void
    {
        $this->configureDatabase();
        $this->IngredientManager = new IngredientManager($this->pdo);
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

    public function testAjouterIngredient()
    {
        $this->IngredientManager->ajouterIngredient("Amande");
        $stmt = $this->pdo->prepare("SELECT * FROM ingredient WHERE nom_ingredient = 'Amande'");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals("Amande", $results[0]['nom_ingredient']);
    }

    public function testRecupererTousLesIngredients()
    {
        $tousLesIngredients = $this->IngredientManager->recupererTousLesIngredients();

        $this->assertIsArray($tousLesIngredients);
        $this->assertNotEmpty($tousLesIngredients);
    }

    public function testModifierIngredient()
    {
        $this->IngredientManager->modifierIngredient(22, "NouveauNom");
        
        $stmt = $this->pdo->prepare("SELECT * FROM ingredient WHERE id_ingredient = 22");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $this->assertEquals("NouveauNom", $results[0]['nom_ingredient']);
    }

    public function testSupprimerIngredient()
    {
        $this->IngredientManager->supprimerIngredient(22);
        $stmt = $this->pdo->prepare("SELECT * FROM ingredient WHERE id_ingredient = 22");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEmpty($results);
    }
}


?>