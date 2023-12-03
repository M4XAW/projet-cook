<?php

    class RecettesManager 
    {
        private $pdo;
    
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }
    
        
        
        public function recupererToutesLesRecettes() {
            $stmt = $this->pdo->prepare("
                SELECT r.id_recette, r.nom_recette, r.difficulte, r.temps_preparation, r.instructions, r.image_url, r.id_categorie, 
                       i.nom_ingredient, q.quantite, q.unite
                FROM recettes r
                INNER JOIN ingredients i ON r.id_recette = i.id_recette
                INNER JOIN quantite q ON i.id_ingredient = q.id_ingredient AND r.id_recette = q.id_recette
            ");
            
            $stmt->execute(); // Exécute la requête
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère les résultats
            
            $recettes = [];
            
            foreach ($results as $result) {
                $recetteId = $result['id_recette'];
                
                // Vérifie si la recette existe déjà dans $recettes
                if (!isset($recettes[$recetteId])) {
                    $recette = new Recette();
                    $recette->setId($recetteId);
                    $recette->setNom($result['nom_recette']);
                    $recette->setDifficulté($result['difficulte']);
                    $recette->setTempsPréparation($result['temps_preparation']);
                    $recette->setInstructions($result['instructions']);
                    $recette->setImage_url($result['image_url']);
                    $recette->setId_categorie($result['id_categorie']);
                    $recettes[$recetteId] = $recette;
                }
                
                // Ajoute les détails de l'ingrédient à la recette
                $ingredient = new Ingredient();
                $ingredient->setNom($result['nom_ingredient']);
                $ingredient->setQuantite($result['quantite']);
                $ingredient->setUnite($result['unite']);
                
                $recettes[$recetteId]->ajouterIngredient($ingredient); // Ajoute l'ingrédient à la recette
            }
            
            return array_values($recettes); // Retourne les recettes sous forme de tableau
        }
        
        

        public function ajouterRecetteAvecIngredients($nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie, $ingredients) {
            $this->pdo->beginTransaction();
        
            try {
                // Insérer les détails de la recette
                $stmt = $this->pdo->prepare("INSERT INTO recettes (nom_recette, difficulte, temps_preparation, instructions, image_url, id_categorie) VALUES (:nom, :difficulte, :temps_preparation, :instructions, :image_url, :id_categorie)");
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':difficulte', $difficulte);
                $stmt->bindParam(':temps_preparation', $temps_preparation);
                $stmt->bindParam(':instructions', $instructions);
                $stmt->bindParam(':image_url', $image_url);
                $stmt->bindParam(':id_categorie', $id_categorie);
                $stmt->execute();
        
                $id_recette = $this->pdo->lastInsertId(); // Récupérer l'ID de la recette insérée
        
                // Insérer les ingrédients et leurs quantités pour cette recette
                $stmtInsertIngredients = $this->pdo->prepare("INSERT INTO ingredients (nom_ingredient, id_recette) VALUES (:nom_ingredient, :id_recette)");
                $stmtInsertQuantite = $this->pdo->prepare("INSERT INTO quantite (quantite, unite, id_ingredient, id_recette) VALUES (:quantite, :unite, :id_ingredient, :id_recette)");
        
                foreach ($ingredients as $ingredient) {
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

        
        public function modifierRecetteAvecIngredients($id_recette, $nom, $difficulte, $temps_preparation, $instructions, $image_url, $id_categorie, $nouveauxIngredients) {
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

        public function supprimerRecetteAvecIngredients($id_recette) {
            $this->pdo->beginTransaction();
        
            try {
                // Supprimer les quantités liées aux ingrédients de cette recette
                $stmtDeleteQuantites = $this->pdo->prepare("DELETE FROM quantite WHERE id_recette = :id_recette");
                $stmtDeleteQuantites->bindParam(':id_recette', $id_recette);
                $stmtDeleteQuantites->execute();
        
                // Supprimer les ingrédients de cette recette
                $stmtDeleteIngredients = $this->pdo->prepare("DELETE FROM ingredients WHERE id_recette = :id_recette");
                $stmtDeleteIngredients->bindParam(':id_recette', $id_recette);
                $stmtDeleteIngredients->execute();
        
                // Supprimer la recette elle-même
                $stmtDeleteRecette = $this->pdo->prepare("DELETE FROM recettes WHERE id_recette = :id_recette");
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
    }
?>
