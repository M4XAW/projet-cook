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
                $result['quantite'],
                $result['nom_unite_mesure']
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
        $stmtIngredient = $this->pdo->prepare("INSERT INTO ingredients (nom_ingredient, id_recette) VALUES (:nom_ingredient, :id_recette)");

        foreach ($ingredients as $ingredient) {
            $nom_ingredient = $ingredient['nom'];

            $stmtIngredient->bindParam(':nom_ingredient', $nom_ingredient);
            $stmtIngredient->bindParam(':id_recette', $id_recette);
            $stmtIngredient->execute();
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