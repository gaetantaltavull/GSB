<?php

if ($_SESSION['typeUtilisateur'] == "Comptable") {
    $action = $_REQUEST['action'];
} else {
    $action = "accesInterdit";
}


$lesUtilisateurs = $pdo->getLesVisiteurs();

switch ($action) {
    case "choixUtilisateur" : {
            //Initialization - remise à zéro des paramètres 
        
            if (isset($_SESSION['utilisateurCible']) || isset($_SESSION['moisCible'])) {
                unset($_SESSION['utilisateurCible']);
                unset($_SESSION['moisCible']);
            }

            break;
        }
        //L'utilisateur 
    case "validerForfait" : {
        $lesFrais = $_POST['lesFrais'];
        $pdo->majFraisForfait($_SESSION['utilisateurCible'],$_SESSION['moisCible'],$lesFrais);
        ajouterMessage("Le frais forfaitisé à été validé");
        break;
    }
    case "validerHorsForfait" : {
        $idFraisHorsForfait = $_GET['idHF'];
        $leFraisHorsForfait = $pdo->getUnFraisHorsForfait($_SESSION['utilisateurCible'],$_SESSION['moisCible'],$idFraisHorsForfait);
        if ($leFraisHorsForfait[0]['idEtat'] == 'VA') {
            ajouterErreur("Le frais hors forfait à déja été validé.");
        }
        else {
            if ($leFraisHorsForfait[0]['idEtat'] == 'RE' && substr($leFraisHorsForfait[0]['libelle'],0,8) == "REFUSE :" ) {
                $leFraisHorsForfait[0]['libelle'] = substr($leFraisHorsForfait[0]['libelle'],8);
            }
            $nbJustificatifs = $pdo->getNbjustificatifs($_SESSION['utilisateurCible'],$_SESSION['moisCible']);
            $montant = $leFraisHorsForfait[0]['montant'];
            $ancienMontant = $pdo->getMontantFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible']);
            $leFraisHorsForfait[0]['idEtat'] = 'VA';
            $pdo->transactionModifierEtatHorsForfait($_SESSION['utilisateurCible'],$leFraisHorsForfait[0],$ancienMontant + $montant,$_SESSION['moisCible'],$nbJustificatifs + 1);
            ajouterMessage("Frais hors forfait validé");
        }
        break;
    }
    case "rejeterHorsForfait" : {
        $idFraisHorsForfait = $_GET['idHF'];
        $leFraisHorsForfait = $pdo->getUnFraisHorsForfait($_SESSION['utilisateurCible'],$_SESSION['moisCible'],$idFraisHorsForfait);
        if ($leFraisHorsForfait[0]['idEtat'] == 'RE') {
            ajouterErreur("Le frais hors forfait à déja été refusé.");
        }
        else {
            $nbJustificatifs = $pdo->getNbjustificatifs($_SESSION['utilisateurCible'],$_SESSION['moisCible']);
            if ($leFraisHorsForfait[0]['idEtat'] == 'VA') {
                $montant = $leFraisHorsForfait[0]['montant'];
            } else {
                $montant = 0;
            }
            $ancienMontant = $pdo->getMontantFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible']);
            $leFraisHorsForfait[0]['idEtat'] = 'RE';
            $leFraisHorsForfait[0]['libelle'] = "REFUSE :" . $leFraisHorsForfait[0]['libelle'];
            $pdo->transactionModifierEtatHorsForfait($_SESSION['utilisateurCible'],$leFraisHorsForfait[0],$ancienMontant - $montant,$_SESSION['moisCible'],$nbJustificatifs - 1);
            ajouterMessage("Frais hors forfait refusé");
        }
        //$pdo->refuserFraisHorsForfait($_SESSION['utilisateurCible'],$_SESSION['moisCible'],$idFraisHorsForfait);
        break;
    }
    case "reporterHorsForfait" : {
        $idFraisHorsForfait = $_GET['idHF'];
        $libelle = $_GET['libelleHF'];
        $montant = $_GET['montantHF'];
        $date = $_GET['dateHF'];
        $mois = $_GET['moisHF'];
        $pdo->transactionReportHorsForfait($_SESSION['utilisateurCible'],$idFraisHorsForfait,$libelle,$date,$montant,$mois);
        break;
    }
    case "validerFiche" : {
            $pdo->majEtatFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible'],"VA");
            ajouterMessage("Le fiche à été validé, elle est en attente de remboursement");
            include("vues/v_messageConfirmation.php");
            unset($_SESSION['utilisateurCible']);
            unset($_SESSION['moisCible']);
        break;
    }
   
}

if (nbMessages() > 0) {
    include("vues/v_messageConfirmation.php");
}
if (nbErreurs() > 0) {
    include("vues/v_erreurs.php");
}


if (count($lesUtilisateurs) > 0) {
    //On prend le premier utilisateur pour l'initialization
    
    if (isset($_GET['utilisateurCible'])) {
        $utilisateurEnSelection = $_GET['utilisateurCible'];
        $_SESSION['utilisateurCible'] = $utilisateurEnSelection;
    }
    elseif (isset($_SESSION['utilisateurCible'])) {
        $utilisateurEnSelection = $_SESSION['utilisateurCible'];
    }
    else {
        $utilisateurEnSelection = key($lesUtilisateurs);
        $_SESSION['utilisateurCible'] = $utilisateurEnSelection;
    }
    
    $lesMois = $pdo->getLesMoisDisponibles($utilisateurEnSelection);
    
    
    if (isset($_POST['lstMois'])) {
        $leMois = $_POST['lstMois'];
        $_SESSION['moisCible'] = $leMois;
    }
    if (isset($_SESSION['moisCible'])) {
        $leMois = $_SESSION['moisCible'];
    }
    else {
        $leMois = key($lesMois);
        $_SESSION['moisCible'] = $leMois;
    }
    
    //Variable qui indique si on est dans le cadre d'une validation des frais ou simplement un suivie de paiement
    $modifier = true;
    include('vues/v_listeUtilisateurs.php');
}


if (isset($utilisateurEnSelection) && isset($leMois)) {
    $laFiche = $pdo->getLesInfosFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible']);
    if ($leMois != -1) {
        if ($laFiche['idEtat'] == "CL") {
            $lesLibelleFraisForfait = $pdo->getLesLibelleFrais();
            $lesFraisForfait = $pdo->getLesFraisForfait($utilisateurEnSelection, $leMois);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($utilisateurEnSelection, $leMois);
            include("vues/v_validationFrais.php");
        } else if ($action != choixUtilisateur){
            ajouterErreur("La fiche sélectionné est en cours de saisie où à déja été validé");
            include("vues/v_erreurs.php");
        }
    } else {
        include("vues/v_validationFrais.php");
    }   
}