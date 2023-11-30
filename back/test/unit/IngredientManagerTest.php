<?php
Use PHPUnit\Framework\TestCase;

require_once('src/IngredientManager.php');
require_once('src/Ingredient.php');

class IngredientManagerTest extends TestCase{

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

   public function testrecupererTousLesIngredients(){
        $tousLesIngredients = $this->IngredientManager->recupererTousLesIngredients();

        $this->assertIsArray($tousLesIngredients);
        $this->assertNotEmpty($tousLesIngredients);
    }

    public function testmodifierIngredient(){
        $this->IngredientManager->modifierIngredient(1, "test", 1);
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id_ingredient = 1");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals("test", $results[0]['nom_ingredient']);
    }

    public function testajouterIngredient(){
        $this->IngredientManager->ajouterIngredient("test", 1);
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE nom_ingredient = 'test'");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals("test", $results[0]['nom_ingredient']);
   }

    public function testsupprimerIngredient(){
        $this->IngredientManager->supprimerIngredient(5);
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id_ingredient = 5");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEmpty($results);
    }

    
}


?>