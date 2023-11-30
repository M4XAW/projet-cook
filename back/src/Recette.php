<?php

class Recette
{
    private $id;
    private $nom;
    private $difficulte;
    private $tempsPreparation;
    private $instructions;
    private $idCategorie;

    public function __construct($id, $nom, $difficulte, $tempsPreparation, $instructions, $idCategorie)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->difficulte = $difficulte;
        $this->tempsPreparation = $tempsPreparation;
        $this->instructions = $instructions;
        $this->idCategorie = $idCategorie;
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

    public function getIdCategorie()
    {
        return $this->idCategorie;
    }
}

