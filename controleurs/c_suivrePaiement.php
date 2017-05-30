<?php

if ($_SESSION['typeUtilisateur'] == "Comptable") {
    $action = $_REQUEST['action'];
} else {
    $action = "accesInterdit";
}


$lesUtilisateurs = $pdo->getLesVisiteurs();

switch ($action) {
        case "choixUtilisateur" : {
            if (isset($_SESSION['utilisateurCible']) || isset($_SESSION['moisCible'])) {
                unset($_SESSION['utilisateurCible']);
                unset($_SESSION['moisCible']);
            }
            break;
        }
        case 'mettreEnPaiement' : {
            $pdo->majEtatFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible'],'VA');
            ajouterMessage("Etat mis à jour");
            break;
        }
        case 'validerLeRemboursement' : {
            $pdo->majEtatFicheFrais($_SESSION['utilisateurCible'],$_SESSION['moisCible'],'RB');
            ajouterMessage("Etat mis à jour");
            break;
        }
        
}

if (nbMessages() > 0) {
    include("vues/v_messageConfirmation.php");
}

if (count($lesUtilisateurs) > 0) {
    //On prend le premier utilisateur pour l'initialization des mois si le formulaire n'a pas été validé
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
    
    include('vues/v_listeUtilisateurs.php');
}

if (isset($utilisateurEnSelection) && isset($leMois) && $leMois != -1) {
    
    $numAnnee = substr($leMois,0,4);
    $numMois = substr($leMois,4,2);
    $leFrais = $pdo->getLesInfosFicheFrais($utilisateurEnSelection,$leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($utilisateurEnSelection,$leMois);
    $lesFraisForfait= $pdo->getLesFraisForfait($utilisateurEnSelection,$leMois);
    
    $libEtat = $leFrais['libEtat'];
    $montantValide = $leFrais['montantValide'];
    $nbJustificatifs = $leFrais['nbJustificatifs'];
    $dateModif =  $leFrais['dateModif'];
    $dateModif =  dateAnglaisVersFrancais($dateModif);
    
    include ('vues/v_modifierEtatFiche.php');
    include("vues/v_etatFrais.php");  
}