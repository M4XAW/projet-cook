<?php
Class Ingredient {
    private $id_ingredient;
    private $nom_ingredient;
    private $id_recette;

    public function __construct($id_ingredient, $nom_ingredient, $id_recette){
        $this->id_ingredient = $id_ingredient;
        $this->nom_ingredient = $nom_ingredient;
        $this->id_recette = $id_recette;
    }

    public function getId(){
        return $this->id_ingredient;
    }

    public function getNom(){
        return $this->nom_ingredient;
    }

    public function getIdRecette(){
        return $this->id_recette;
    }

    public function setId($id_ingredient){
        $this->id_ingredient = $id_ingredient;
    }

    public function setNom($nom_ingredient){
        $this->nom_ingredient = $nom_ingredient;
    }


    public function setIdRecette($id_recette){
        $this->id_recette = $id_recette;
    }
}



?>