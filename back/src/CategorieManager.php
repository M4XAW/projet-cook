<?php
class CategorieManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function recupererCategories()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $categories = [];
        foreach ($result as $categorie) {
            $categories[] = new Categorie($categorie['id_categorie'], $categorie['nom_categorie']);
        }
        return $categories;
    }

    public function recupererRecettesParCategorie($categorie)
    {
        $stmt = $this->pdo->prepare("SELECT recette.*, categorie.nom_categorie 
                            FROM recette 
                            INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie 
                            WHERE categorie.nom_categorie = :categorie");


        $stmt->bindParam(':categorie', $categorie);

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
}
