<?php

class RecettesManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function recupererRecette($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recette WHERE id_recette = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $recette = new Recette(
            $result['id_recette'],
            $result['nom_recette'],
            $result['difficulte'],
            $result['temps_preparation'],
            $result['instructions'],
            $result['image_url'],
            $result['id_categorie']
        );

        return $recette;
    }

    public function recupererToutesLesRecettes()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recette");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Utilisez FETCH_ASSOC pour obtenir un tableau associatif

        $recettes = [];
        foreach ($results as $result) {
            $recettes[] = new Recette(
                $result['id_recette'],
                $result['nom_recette'],
                $result['difficulte'],
                $result['temps_preparation'],
                $result['instructions'],
                $result['image_url'],
                $result['id_categorie']
            );
        }

        return $recettes;
    }

    public function ajouterRecette($nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie)
    {
        $this->pdo->beginTransaction();

        try {
            // Insérer les détails de la recette
            $stmtRecette = $this->pdo->prepare("INSERT INTO recette (nom_recette, difficulte, temps_preparation, instructions, image_url, id_categorie) VALUES (:nom, :difficulte, :temps_preparation, :instructions, :image_url, :id_categorie)");
            $stmtRecette->bindParam(':nom', $nom);
            $stmtRecette->bindParam(':difficulte', $difficulte);
            $stmtRecette->bindParam(':temps_preparation', $temps_preparation);
            $stmtRecette->bindParam(':instructions', $instructions);
            $stmtRecette->bindParam(':image_url', $image_url);
            $stmtRecette->bindParam(':id_categorie', $id_categorie);
            $stmtRecette->execute();

            // Récupérer l'ID de la recette nouvellement insérée
            $id_recette = $this->pdo->lastInsertId();

            $this->pdo->commit();

            return $id_recette;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // Gérer l'erreur, la journalisation ou le retourner selon votre logique métier
            echo "Erreur lors de l'ajout de la recette : " . $e->getMessage();
        }
    }

    public function modifierRecetteAvecIngredients($id_recette, $nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie, $nouveauxIngredients)
    {
        $this->pdo->beginTransaction();

        try {
            // Mettre à jour les détails de la recette
            $stmtUpdateRecette = $this->pdo->prepare("UPDATE recettes SET nom_recette = :nom, difficulte = :difficulte, temps_preparation = :temps_preparation, instructions = :instructions, image_url = :image_url, id_categorie = :id_categorie WHERE id_recette = :id_recette");
            $stmtUpdateRecette->bindParam(':id_recette', $id_recette);
            $stmtUpdateRecette->bindParam(':nom', $nom);
            $stmtUpdateRecette->bindParam(':difficulte', $difficulte);
            $stmtUpdateRecette->bindParam(':temps_preparation', $temps_preparation);
            $stmtUpdateRecette->bindParam(':instructions', $instructions);
            $stmtUpdateRecette->bindParam(':image_url', $image_url);
            $stmtUpdateRecette->bindParam(':id_categorie', $id_categorie);
            $stmtUpdateRecette->execute();

            // Supprimer tous les anciens ingrédients de cette recette
            $stmtDeleteIngredients = $this->pdo->prepare("DELETE FROM ingredients WHERE id_recette = :id_recette");
            $stmtDeleteIngredients->bindParam(':id_recette', $id_recette);
            $stmtDeleteIngredients->execute();

            // Réinsérer les nouveaux ingrédients
            $stmtInsertIngredients = $this->pdo->prepare("INSERT INTO ingredients (nom_ingredient, id_recette) VALUES (:nom_ingredient, :id_recette)");
            $stmtInsertQuantite = $this->pdo->prepare("INSERT INTO quantite (quantite, unite, id_ingredient, id_recette) VALUES (:quantite, :unite, :id_ingredient, :id_recette)");

            foreach ($nouveauxIngredients as $ingredient) {
                $nom_ingredient = $ingredient['nom'];
                $quantite = $ingredient['quantite'];
                $unite = $ingredient['unite'];

                $stmtInsertIngredients->bindParam(':nom_ingredient', $nom_ingredient);
                $stmtInsertIngredients->bindParam(':id_recette', $id_recette);
                $stmtInsertIngredients->execute();

                $id_ingredient = $this->pdo->lastInsertId(); // Récupérer l'ID de l'ingrédient inséré

                $stmtInsertQuantite->bindParam(':quantite', $quantite);
                $stmtInsertQuantite->bindParam(':unite', $unite);
                $stmtInsertQuantite->bindParam(':id_ingredient', $id_ingredient);
                $stmtInsertQuantite->bindParam(':id_recette', $id_recette);
                $stmtInsertQuantite->execute();
            }

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // Gérer l'erreur, la journalisation ou le retourner selon votre logique métier
        }
    }

    public function supprimerRecetteAvecIngredients($id_recette)
    {
        $this->pdo->beginTransaction();

        try {
            // Supprimer les quantités liées aux ingrédients de cette recette
            $stmtDeleteQuantites = $this->pdo->prepare("DELETE FROM recetteIngredient WHERE id_recette = :id_recette");
            $stmtDeleteQuantites->bindParam(':id_recette', $id_recette);
            $stmtDeleteQuantites->execute();

            // Supprimer la recette elle-même
            $stmtDeleteRecette = $this->pdo->prepare("DELETE FROM recette WHERE id_recette = :id_recette");
            $stmtDeleteRecette->bindParam(':id_recette', $id_recette);
            $stmtDeleteRecette->execute();

            $this->pdo->commit();
            return true; // Indique que la suppression a réussi
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // Gérer l'erreur, la journalisation ou le retourner selon votre logique métier
            echo "Erreur lors de la suppression : " . $e->getMessage();
            return false; // En cas d'échec
        }
    }

    public function rechercherRecettes($recherche)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT DISTINCT r.* FROM recette r LEFT JOIN recetteIngredient ri ON r.id_recette = ri.id_recette
                                        LEFT JOIN ingredient i ON ri.id_ingredient = i.id_ingredient
                                        WHERE r.nom_recette LIKE :recherche OR i.nom_ingredient LIKE :recherche");
            $rechercheParam = '%' . $recherche . '%';
            $stmt->bindParam(':recherche', $rechercheParam);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $recettes = [];
            foreach ($results as $result) {
                $recettes[] = new Recette(
                    $result['id_recette'],
                    $result['nom_recette'],
                    $result['difficulte'],
                    $result['temps_preparation'],
                    $result['instructions'],
                    $result['image_url'],
                    $result['id_categorie']
                );
            }

            return $recettes;
        } catch (PDOException $e) {
            echo "Erreur lors de lae recherche de recettes : " . $e->getMessage();
            return [];
        }
    }
}
?>