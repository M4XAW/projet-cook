<?php


use PHPUnit\Framework\TestCase;

require_once('src/QuantiteManager.php');
require_once('src/Quantite.php');

class QuantiteManagerTest extends TestCase{

    private $pdo;
    private $QuantiteManager;

    public function setUp(): void
    {
        $this->configureDatabase();
        $this->QuantiteManager = new QuantiteManager($this->pdo);
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

   public function testrecupererToutesLesQuantites(){
        $toutesLesQuantites = $this->QuantiteManager->recupererToutesLesQuantites();

        $this->assertIsArray($toutesLesQuantites);
        $this->assertNotEmpty($toutesLesQuantites);
    }

//     public function testmodifierQuantite(){
//         $this->QuantiteManager->modifierQuantite(1, 1, "kg", 1, 1);
//         $stmt = $this->pdo->prepare("SELECT * FROM quantite WHERE id_quantite = 1");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEquals(1, $results[0]['quantite']);
//     }

//     public function testajouterQuantite(){
//         $this->QuantiteManager->ajouterQuantite(120, "g", 9, 1);
//         $stmt = $this->pdo->prepare("SELECT * FROM quantite WHERE id_ingredient = 9 AND id_recette = 1");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEquals(120, $results[0]['quantite']);
//    }

//     public function testsupprimerQuantite(){
//         $this->QuantiteManager->supprimerQuantite(5);
//         $stmt = $this->pdo->prepare("SELECT * FROM quantite WHERE id_quantite = 5");
//         $stmt->execute();
//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $this->assertEmpty($results);
//    }

}