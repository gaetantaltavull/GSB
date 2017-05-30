<?php
$idVisiteur = $_SESSION['idUtilisateur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
if ($_SESSION['typeUtilisateur'] == "Visiteur") {
    $action = $_REQUEST['action'];
} else {
    $action = 'accesInterdit';
}
switch($action){
	case 'saisirFrais':{
		if($pdo->estPremierFraisMois($idVisiteur,$mois)){
			$pdo->creeNouvellesLignesFrais($idVisiteur,$mois);
		}
		break;
	}
	case 'validerMajFraisForfait':{
		$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais);
                           ajouterMessage("Frais forfaitaire enregistrés");
		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
		}
	  break;
	}
	case 'validerCreationFrais':{
		$dateFrais = $_REQUEST['dateFrais'];
		$libelle = $_REQUEST['libelle'];
		$montant = $_REQUEST['montant'];
		valideInfosFrais($dateFrais,$libelle,$montant);
		if (nbErreurs() == 0 ){
			$pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
		}
		break;
	}
	case 'supprimerFrais':{
		$idFrais = $_REQUEST['idFrais'];
	    $pdo->supprimerFraisHorsForfait($idFrais);
		break;
	}
        case 'accesInterdit':{
            include("vues/v_accesInterdit.php");
	}
}
if (nbMessages() > 0) {
    include("vues/v_messageConfirmation.php");
}
if (nbErreurs() > 0) {
    include("vues/v_erreurs.php");
}
if ($action != 'accesInterdit') {
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
    $lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
    include("vues/v_listeFraisForfait.php");
    include("vues/v_listeFraisHorsForfait.php");
}

?>