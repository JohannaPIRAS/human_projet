<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'Classes/Personnage.php';
require 'Classes/Partie.php';
use Classes\Personnage;
use Classes\Database;
use Classes\Partie;


// Vérifier connexion bdd
// $db = new Database;
// $pdo = $db->getPdo();

// if ($pdo) {
//     echo "connecter";
// } else {
//     echo "non connecter";
// }

$personnage = new Personnage(4);

$personnage->setTailleNaissance(200);
var_dump("Taille naissance = ".$personnage->getTailleNaissance());
$personnage->setVie(85);
var_dump("Esperance vie = ".$personnage->getEsperanceVie());
$personnage->setCroissance(1);
var_dump("Croissance = ".$personnage->getCroissance());
$personnage->setEmplacement(4);
var_dump("Emplacement = ".$personnage-> getEmplacement());
$personnage->setSexe('Femme');
var_dump("Sexe =".$personnage-> getSexe());

$result = $personnage->registerPersonnage();
var_dump($result);
// var_dump($personnage);


var_dump($personnage -> getPersonnage());

$partie = new Partie();
$getPartie = $partie -> statisticAll();

var_dump($partie);
var_dump($getPartie['lifespan']);
var_dump($getPartie['growth']);
var_dump($getPartie['birthsize']);

$checkPerso = $personnage -> checkPersonnage();
var_dump($checkPerso);
// if  ($persoSaved && $partie){

//     $partie->getSexRatio();
//     var_dump($partie->getSexRatio());
//     var_dump($partie->statisticPartie());
    
// }


?>