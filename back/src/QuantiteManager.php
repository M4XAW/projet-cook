<?php

Class QuantiteManager{
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function recupererToutesLesQuantites(){
        $stmt = $this->pdo->prepare("SELECT * FROM quantite");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Utilisez FETCH_ASSOC pour obtenir un tableau associatif
    
        $quantites = [];
        foreach ($results as $result) {
            $quantites[] = new Quantite(  
                $result['id_quantite'],
                $result['quantite'],
                $result['unite'],
                $result['id_ingredient'],
                $result['id_recette']
            );
        }
    
        return $quantites;
    }
    

    public function modifierQuantite($id, $quantite, $unite, $id_ingredient, $id_recette) {
        $stmt = $this->pdo->prepare("UPDATE quantite SET quantite = :quantite, unite = :unite, id_ingredient = :id_ingredient, id_recette = :id_recette WHERE id_quantite = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':unite', $unite);
        $stmt->bindParam(':id_ingredient', $id_ingredient);
        $stmt->bindParam(':id_recette', $id_recette);
        $stmt->execute();
    }

    public function ajouterQuantite($quantite, $unite, $id_ingredient, $id_recette) {
        $stmt = $this->pdo->prepare("INSERT INTO quantite (quantite, unite, id_ingredient, id_recette) VALUES (:quantite, :unite, :id_ingredient, :id_recette)");
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':unite', $unite);
        $stmt->bindParam(':id_ingredient', $id_ingredient);
        $stmt->bindParam(':id_recette', $id_recette);
        $stmt->execute();
    }
    public function supprimerQuantite($id) {
        $stmt = $this->pdo->prepare("DELETE FROM quantite WHERE id_quantite = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}

?>