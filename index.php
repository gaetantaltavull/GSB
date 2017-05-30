<?php
session_start();
//Désactive les erreurs pour la production
error_reporting(0);
require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
include('vues/v_sommaire.php');
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	 
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
        case 'validerFrais' : {
                include("controleurs/c_validerFrais.php");break;
        }
        case 'suivrePaiement' : {
                include("controleurs/c_suivrePaiement.php");break;
        }
}
include("vues/v_pied.php") ;
?>

