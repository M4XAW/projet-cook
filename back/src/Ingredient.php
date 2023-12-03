<?php
Class Ingredient {
    private $id_ingredient;
    private $nom_ingredient;
    private $quantite;
    private $unite;

    public function __construct($id_ingredient, $nom_ingredient, $quantite, $unite){
        $this->id_ingredient = $id_ingredient;
        $this->nom_ingredient = $nom_ingredient;
        $this->quantite = $quantite;
        $this->unite = $unite;
    }

    public function getId(){
        return $this->id_ingredient;
    }

    public function getNom(){
        return $this->nom_ingredient;
    }

    public function getQuantite(){
        return $this->quantite;
    }

    public function getUnite(){
        return $this->unite;
    }

    public function setId($id_ingredient){
        $this->id_ingredient = $id_ingredient;
    }

    public function setNom($nom_ingredient){
        $this->nom_ingredient = $nom_ingredient;
    }

    public function setQuantite($quantite){
        $this->quantite = $quantite;
    }

    public function setUnite($unite){
        $this->unite = $unite;
    }
}



?>