<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
                $mdp = hash('sha256',$mdp);
                echo $mdp;
		$utilisateur = $pdo->getInfosUtilisateur($login,$mdp);
		if(!is_array( $utilisateur)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			$id = $utilisateur['id'];
			$nom =  $utilisateur['nom'];
			$prenom = $utilisateur['prenom'];
                        $type = $utilisateur['type'];
			connecter($id,$nom,$prenom,$type);
			include("vues/v_sommaire.php");
		}
		break;
        }
        case 'deconnexion': {
            $_REQUEST['action'] = null;
            include("vues/v_connexion.php");
            deconnecter();
                break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>