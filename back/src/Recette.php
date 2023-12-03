<?php

class Recette
{
    private $id;
    private $nom;
    private $difficulte;
    private $tempsPreparation;
    private $instructions;
    private $image_url;
    private $idCategorie;
    private $ingredients = [];
    private $quantite = [];

    public function __construct($id, $nom, $difficulte, $tempsPreparation, $instructions, $image_url, $idCategorie)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->difficulte = $difficulte;
        $this->tempsPreparation = $tempsPreparation;
        $this->instructions = $instructions;
        $this->image_url = $image_url;
        $this->idCategorie = $idCategorie;
        // $this->ingredients = $ingredients;
        // $this->quantite = $quantite;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }
    public function getDifficulté()
    {
        return $this->difficulte;
    }
    public function getTempsPréparation()
    {
        return $this->tempsPreparation;
    }

    public function getInstructions()
    {
        return $this->instructions;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }   
}

