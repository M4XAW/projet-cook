<?php

    class RecettesManager 
    {
        private $pdo;
    
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }
    
        public function recupererToutesLesRecettes(){
            $stmt = $this->pdo->prepare("SELECT * FROM recettes");
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
        

        public function modifierRecette($id, $nom, $difficulte, $temps_preparation, $instructions, $id_categorie) {
            $stmt = $this->pdo->prepare("UPDATE recettes SET nom_recette = :nom, difficulte = :difficulte, temps_preparation = :temps_preparation, instructions = :instructions, id_categorie = :id_categorie WHERE id_recette = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':difficulte', $difficulte);
            $stmt->bindParam(':temps_preparation', $temps_preparation);
            $stmt->bindParam(':instructions', $instructions);
            $stmt->bindParam(':id_categorie', $id_categorie);
            $stmt->execute();
        }

        public function ajouterRecette($nom, $difficulte, $temps_preparation, $instructions, $id_categorie) {
            $stmt = $this->pdo->prepare("INSERT INTO recettes (nom_recette, difficulte, temps_preparation, instructions, id_categorie) VALUES (:nom, :difficulte, :temps_preparation, :instructions, :id_categorie)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':difficulte', $difficulte);
            $stmt->bindParam(':temps_preparation', $temps_preparation);
            $stmt->bindParam(':instructions', $instructions);
            $stmt->bindParam(':id_categorie', $id_categorie);
            $stmt->execute();
        }

        // public function rechercherRecettes($recherche) {
        //     $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE nom_recette LIKE :recherche");
        //     $stmt->bindValue(':recherche', '%' . $recherche . '%');
        //     $stmt->execute();
        //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //     $recettes = [];
        //     foreach ($results as $result) {
        //         $recettes[] = new Recette(  
        //             $result['id_recette'],
        //             $result['nom_recette'],
        //             $result['difficulte'],
        //             $result['temps_preparation'],
        //             $result['instructions'],
        //             $result['image_url'],
        //             $result['id_categorie']
        //         );
        //     }
        
        //     return $recettes;
        // }

        public function rechercherRecettes($recherche) {
            $stmt = $this->pdo->prepare("SELECT DISTINCT r.* FROM recettes r
                                         JOIN ingredients i ON r.id_recette = i.id_recette
                                         WHERE r.nom_recette LIKE :recherche OR i.nom_ingredient LIKE :recherche");
            $stmt->bindValue(':recherche', '%' . $recherche . '%');
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
        }
        
        
        public function afficherRecette(Recette $recetteData) {
            echo '<div class="recipe">';
            echo '<img src="' . $recetteData->getImageUrl() . '" alt="image recette">';
            echo '<h2>' . $recetteData->getNom() . '</h2>';
            echo '<p>Difficulté: ' . $recetteData->getDifficulté() . '</p>';
            echo '<p>Temps de préparation: ' . $recetteData->getTempsPréparation() . '</p>';
            // echo '<p>Instructions: ' . $recetteData->getInstructions() . '</p>';
            echo '</div>';
        }
        
        
    }
?>


    