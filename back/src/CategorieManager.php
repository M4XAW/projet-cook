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

    public function recupererCategorieParId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id_categorie = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return new Categorie($result['id_categorie'], $result['nom_categorie']);
    }

    public function ajouterCategorie($nom)
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom_categorie) VALUES (:nom)");
        $stmt->execute(['nom' => $nom]);
        return $this->pdo->lastInsertId();
    }

    public function supprimerCategorie($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id_categorie = :id");
        $stmt->execute(['id' => $id]);
    }

    public function modifierCategorie($id, $nom)
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom_categorie = :nom WHERE id_categorie = :id");
        $stmt->execute(['id' => $id, 'nom' => $nom]);
    }
}

?>