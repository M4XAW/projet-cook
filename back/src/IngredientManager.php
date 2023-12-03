<?php
require_once('../back/src/Ingredient.php');

class IngredientManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function recupererTousLesIngredients()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ingredient");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ingredients = [];
        foreach ($results as $result) {
            $ingredients[] = new Ingredient(
                $result['id_ingredient'],
                $result['nom_ingredient'],
                null,
                null
            );
        }

        return $ingredients;
    }

    public function recupererIngredientsParRecette($idRecette)
    {
        $stmt = $this->pdo->prepare("SELECT i.*, ri.quantite, um.nom_unite_mesure
                                 FROM ingredient i
                                 JOIN recetteIngredient ri ON i.id_ingredient = ri.id_ingredient
                                 JOIN uniteMesure um ON ri.id_unite_mesure = um.id_unite_mesure
                                 WHERE ri.id_recette = :idRecette");
        $stmt->bindParam(':idRecette', $idRecette);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ingredients = [];
        foreach ($results as $result) {
            $ingredients[] = new Ingredient(
                $result['id_ingredient'],
                $result['nom_ingredient'],
                $result['quantite'],
                $result['nom_unite_mesure']
            );
        }

        return $ingredients;
    }

    public function modifierIngredient($id, $nom, $id_recette)
    {
        $stmt = $this->pdo->prepare("UPDATE ingredients SET nom_ingredient = :nom, id_recette = :id_recette WHERE id_ingredient = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':id_recette', $id_recette);
        $stmt->execute();
    }

    public function ajouterIngredients($ingredients, $id_recette)
    {
        $this->pdo->beginTransaction();
    
        try {
            // Insérer les détails des ingrédients de la recette
            $stmtIngredient = $this->pdo->prepare("INSERT INTO recetteIngredient (id_recette, id_ingredient, quantite, id_unite_mesure) VALUES (:id_recette, :id_ingredient, :quantite, :id_unite_mesure)");
    
            foreach ($ingredients as $id_ingredient => $ingredientData) {
                $quantite = $ingredientData['quantity'];
                $id_unite_mesure = $ingredientData['unit'];
    
                $stmtIngredient->bindParam(':id_recette', $id_recette);
                $stmtIngredient->bindParam(':id_ingredient', $id_ingredient);
                $stmtIngredient->bindParam(':quantite', $quantite);
                $stmtIngredient->bindParam(':id_unite_mesure', $id_unite_mesure);
                $stmtIngredient->execute();
            }
    
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // Gérer l'erreur, la journalisation ou le retourner selon votre logique métier
            echo "Erreur lors de l'ajout des ingrédients : " . $e->getMessage();
        }
    }
    public function supprimerIngredient($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM ingredients WHERE id_ingredient = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}


?>