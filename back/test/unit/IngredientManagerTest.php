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

//     public function testmodifierIngredient(){
//         $this->IngredientManager->modifierIngredient(1, "Pomme", 1);
//         $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id_ingredient = 1");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEquals("Pomme", $results[0]['nom_ingredient']);
//     }

//     public function testajouterIngredient(){
//         $this->IngredientManager->ajouterIngredient("Amande", 1);
//         $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE nom_ingredient = 'Amande'");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEquals("Amande", $results[0]['nom_ingredient']);
//    }

//     public function testsupprimerIngredient(){
//         $this->IngredientManager->supprimerIngredient(8);
//         $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id_ingredient = 8");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEmpty($results);
//     }

    
}


?>