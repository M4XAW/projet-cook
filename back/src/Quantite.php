<?php

Class Quantite {
    private $id_quantite;
    private $quantite;
    private $unite;
    private $id_ingredient;
    private $id_recette;

    public function __construct($id_quantite, $quantite, $unite, $id_ingredient, $id_recette){
        $this->id_quantite = $id_quantite;
        $this->quantite = $quantite;
        $this->unite = $unite;
        $this->id_ingredient = $id_ingredient;
        $this->id_recette = $id_recette;
    }

    public function getId(){
        return $this->id_quantite;
    }

    public function getQuantite(){
        return $this->quantite;
    }

    public function getUnite(){
        return $this->unite;
    }

    public function getIdIngredient(){
        return $this->id_ingredient;
    }

    public function getIdRecette(){
        return $this->id_recette;
    }

    public function setId($id_quantite){
        $this->id_quantite = $id_quantite;
    }

    public function setQuantite($quantite){
        $this->quantite = $quantite;
    }

    public function setUnite($unite){
        $this->unite = $unite;
    }

    public function setIdIngredient($id_ingredient){
        $this->id_ingredient = $id_ingredient;
    }

    public function setIdRecette($id_recette){
        $this->id_recette = $id_recette;
    }

}

?>