<?php

namespace Classes;

use Classes\Database;

require 'Classes/Database.php';

/**
 * class Personnage
 * 
 * Permet de créer un nouveau personnage
 */
class Personnage
{

    /**
     * Fixe la taille de départ du personnage
     *
     * @var float (42-57)
     */
    protected $_tailleNaissance;

    /**
     * Espérance de vie du personnage
     *
     * @var int (0-100)
     */
    protected $_vie;

    /**
     * Taux de croissance appliqué à la taille du personnage
     *
     * @var float (0.8-1.2)
     */
    protected $_croissance;

    /**
     * Sexe du personnage (Homme ou pas)
     *
     * @var string
     */
    protected $_sexe;

    /**
     * Indique le numéro de la case du personnage
     *
     * @var int
     */
    protected $_emplacement;

    public function __construct($emplacement)
    {

        $this->_tailleNaissance = mt_rand(420, 570) / 10;
        $this->_vie = mt_rand(0, 100);
        $this->_croissance = mt_rand(8, 12) / 10;
        $this->_emplacement = $emplacement;

        $proba_sexe = mt_rand(0, 100);

        if ($proba_sexe < 50) {
            $this->_sexe = 'Homme';
        } else {
            $this->_sexe = 'Femme';
        }
    }

    public function getTailleNaissance()
    {
        return $this->_tailleNaissance;
    }

    public function setTailleNaissance($tailleNaissance)
    {
        $this->_tailleNaissance = $tailleNaissance;
        // return $this->_tailleNaissance;
    }


    public function getEsperanceVie()
    {
        return $this->_vie;
    }

    public function setVie($vie)
    {

        $this-> _vie = $vie;
    }


    public function getCroissance()
    {
        return $this->_croissance;
    }

    public function setCroissance($croissance)
    {

        $this-> _croissance = $croissance;
    }

    public function getEmplacement()
    {
        return $this->_emplacement;
    }

    public function setEmplacement($emplacement)
    {
        $this-> _emplacement = $emplacement;
    }


    public function getSexe()
    {
        return $this->_sexe;
    }

    public function setSexe($sexe)
    {
        $this-> _sexe = $sexe;
    }

    public function checkPersonnage()
    {
        $pdo = new Database();

        $connect = $pdo->getPdo();

        $check = $connect->prepare('SELECT * from personnage
        WHERE lifespan = ?
        AND growth = ?
        AND birthsize = ?
        AND men = ?
        AND location = ?');

        $check->bindParam(1, $this->_lifespan);
        $check->bindParam(2, $this->_growth);
        $check->bindParam(3, $this->_birthsize);
        if ($this->_sexe === 'Homme') {
            $boolSexe = true;
        } else {
            $boolSexe = false;
        }
        $check->bindParam(4, $boolSexe);
        $check->bindParam(5, $this->_emplacement);

        $check->execute();

        $checkPersonnage = $check->fetch();

        return $checkPersonnage;

    }

    public function registerPersonnage()
    {

        $pdo = new Database();

        $connect = $pdo->getPdo();

        $checkPersonnage = $this -> checkPersonnage();

        if (!$checkPersonnage) {
        $enregistre = $connect->prepare('INSERT INTO personnage (lifespan, growth, birthsize, men, location) VALUES (?, ?, ?, ?, ?)');

        $enregistre->bindParam(1, $this->_vie);
        $enregistre->bindParam(2, $this->_croissance);
        $enregistre->bindParam(3, $this->_tailleNaissance);
        if ($this->_sexe === 'Homme') {
            $boolSexe = true;
        } else {
            $boolSexe = false;
        }
        
        $enregistre->bindParam(4, $boolSexe);
        $enregistre->bindParam(5, $this->_emplacement);

        
        $enregistre->execute();
 
        return true;
    }



    }

    public function getPersonnage(){
 

        $result_perso = ['men', 'birthsize', 'lifespan','location','sexe'];
        $result_perso['men'] = $this->_sexe;
        $result_perso['birthsize'] =$this->_tailleNaissance;
        $result_perso['lifespan'] = $this->_vie;
        $result_perso['location']=$this->_emplacement;
        $result_perso['sexe']=$this->_sexe;


        return $result_perso;
        

    }

    public function savePartiePerso($id_partie){
        
        $pdo = new Database();

        $connect = $pdo->getPdo();

        $save = $connect->prepare('SELECT id_perso FROM personnage WHERE lifespan = ?, growth = ?, birthsize = ?, men =?, location =?');
        $save->bindParam(1, $this->_vie);
        $save->bindParam(2, $this->_croissance);
        $save->bindParam(3, $this->_tailleNaissance);
        if ($this->_sexe === 'Homme') {
            $boolSexe = true;
        } else {
            $boolSexe = false;
        }
        
        $save->bindParam(4, $boolSexe);
        $save->bindParam(5, $this->_emplacement);

        $save-> execute();

        $saveIdPerso = $save -> fetch();

        $idPerso = $saveIdPerso[0];

        $savePartiePerso = $connect-> prepare('INSERT INTO partie_perso 
        VALUES id_partie=?, id_perso=?;');

        $save->bindParam(1, $id_partie);
        $save->bindParam(2, $idPerso);

        $savePartiePerso -> execute;

        return true;

    
    }
}