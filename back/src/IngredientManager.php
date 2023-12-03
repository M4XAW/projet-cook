<?php
Class IngredientManager
{
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function recupererTousLesIngredients(){
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Utilisez FETCH_ASSOC pour obtenir un tableau associatif
    
        $ingredients = [];
        foreach ($results as $result) {
            $ingredients[] = new Ingredient(  
                $result['id_ingredient'],
                $result['nom_ingredient'],
                $result['id_recette']
            );
        }
    
        return $ingredients;
    }

    public function recupererIngredient($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id_ingredient = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $ingredient = new Ingredient(  
            $result['id_ingredient'],
            $result['nom_ingredient'],
            $result['id_recette']
        );
    
        return $ingredient;
    }

    public function modifierIngredient($id, $nom, $id_recette) {
        $stmt = $this->pdo->prepare("UPDATE ingredients SET nom_ingredient = :nom, id_recette = :id_recette WHERE id_ingredient = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':id_recette', $id_recette);
        $stmt->execute();
    }

    public function ajouterIngredient($nom, $id_recette) {
        $stmt = $this->pdo->prepare("INSERT INTO ingredients (nom_ingredient, id_recette) VALUES (:nom, :id_recette)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':id_recette', $id_recette);
        $stmt->execute();
    }
    public function supprimerIngredient($id) {
        $stmt = $this->pdo->prepare("DELETE FROM ingredients WHERE id_ingredient = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}


?>