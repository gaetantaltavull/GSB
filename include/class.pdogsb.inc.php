<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author JPP,Quentin Aprilante, Gaëtan Taltavull
 * @version    1.1
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb_frais';   		
      	private static $user='root' ;    		
      	private static $mdp='root' ;	
        private static $monPdo;
        private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosUtilisateur($login, $mdp){
		$req = "select utilisateur.id as id, utilisateur.nom as nom, utilisateur.prenom as prenom, libelle as type 
                    from utilisateur inner join typeUtilisateur on utilisateur.type = typeUtilisateur.id 
                    where utilisateur.login='$login' and utilisateur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idUtilisateur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
        
/**
 * Retourne sous forme d'un tableau associatif la ligne de frais hors forfait
 * concernées par les 3 arguments (clé primaire)
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $idFraisHorsForfait
 * @return tous les champs d'une ligne de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getUnFraisHorsForfait($idVisiteur,$mois,$idFraisHorForfait){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idUtilisateur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' and lignefraishorsforfait.id = '$idFraisHorForfait'";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
        
/**
 * Modifie la valeur du champ validite à 1 de la table ligneFraisHorsForfait
 
 * Modifie la valeur du champ validite à 1 de la table ligneFraisHorsForfait 
 * pour un visiteur et un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $idLeFrais identifiant du frais hors forfait à modifier
*/
	/*public function refuserFraisHorsForfait($idVisiteur,$mois,$idLeFrais){
            $req = "update lignefraishorsforfait set lignefraishorsforfait.validite = 1, 
            lignefraishorsforfait.libelle = concat('REFUSE :',lignefraishorsforfait.libelle)
            where lignefraishorsforfait.idUtilisateur = '$idVisiteur' and lignefraishorsforfait.mois = '$mois'
            and lignefraishorsforfait.id = '$idLeFrais' and lignefraishorsforfait.validite <> 1";
            PdoGsb::$monPdo->exec($req);
            
            /*Vode fonctionnelle avant le test length() :
             * $req = "update lignefraishorsforfait set lignefraishorsforfait.validite = 1, 
            lignefraishorsforfait.libelle = concat('REFUSE :',lignefraishorsforfait.libelle)
            where lignefraishorsforfait.idUtilisateur = '$idVisiteur' and lignefraishorsforfait.mois = '$mois'
            and lignefraishorsforfait.id = '$idLeFrais' and lignefraishorsforfait.validite <> 1";
            PdoGsb::$monPdo->exec($req);
	}*/
       
/**
 * Modifie la valeur du champ validite à 2 de la table ligneFraisHorsForfait
 
 * Modifie la valeur du champ validite à 2 de la table ligneFraisHorsForfait 
 * pour un visiteur et un mois donné - retire la mention "REFUSE" si elle est présente
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $idLeFrais identifiant du frais hors forfait à modifier
*/
	/*public function validerFraisHorsForfait($idVisiteur,$mois,$idLeFrais){
            $req = "update lignefraishorsforfait set lignefraishorsforfait.validite = 2
            where lignefraishorsforfait.idUtilisateur = '$idVisiteur' and lignefraishorsforfait.mois = '$mois'
            and lignefraishorsforfait.id = '$idLeFrais'";
            PdoGsb::$monPdo->exec($req);
            $req = "update lignefraishorsforfait 
                set lignefraishorsforfait.libelle = substr(lignefraishorsforfait.libelle,9) 
                where lignefraishorsforfait.idUtilisateur = '$idVisiteur' 
                and lignefraishorsforfait.mois = '$mois' and lignefraishorsforfait.id = '$idLeFrais' 
                and lignefraishorsforfait.libelle like 'REFUSE :%'";
            PdoGsb::$monPdo->exec($req);
            $req = "update fichefrais 
                set fichefrais.nbJustificatifs = fichefrais.nbJustificatifs + 1, fichefrais.montantValide = fichefrais.montantValide + (select lignefraishorsforfait.montant from lignefraishorsforfait where
                lignefraishorsforfait.idUtilisateur = '$idVisiteur' and lignefraishorsforfait.mois = '$mois'
                and lignefraishorsforfait.id = '$idLeFrais') where fichefrais.idUtilisateur = '$idVisiteur' and 
                    mois = '$mois'";
            PdoGsb::$monPdo->exec($req);
	}*/
        
        public function MajFraisHorsForfait($idVisiteur,$mois,$leFraisHorsForfait){
            $id = $leFraisHorsForfait['id'];
            $libelle = $leFraisHorsForfait['libelle'];
            $montant = $leFraisHorsForfait['montant'];
            $validite = $leFraisHorsForfait['validite'];
            echo $validite;
            echo $id;
            
            $req = "update lignefraishorsforfait set lignefraishorsforfait.libelle = '$libelle',
                    lignefraishorsforfait.montant = '$montant', lignefraishorsforfait.validite = '$validite'
                where lignefraishorsforfait.idUtilisateur = '$idVisiteur' and lignefraishorsforfait.mois = '$mois'
                and lignefraishorsforfait.id = '$id'";
            PdoGsb::$monPdo->exec($req);

	}
    
        
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
                $req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idUtilisateur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idUtilisateur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
        
        /**
 * Retourne tous les libelles de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesLibelleFrais(){
		$req = "select fraisforfait.libelle as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idUtilisateur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idutilisateur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idUtilisateur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idUtilisateur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idUtilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idUtilisateur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant',0)";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idUtilisateur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idUtilisateur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idUtilisateur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

/**
 * Renvoie un tableau contennt la liste de tous les visiteurs
 
 * @return type array()
 */        
        public function getLesVisiteurs() {
            $req = "select id,nom, prenom from Utilisateur where type = 1";
            $res = PdoGsb::$monPdo->query($req);
            $lesUtilisateurs =array();
            $laLigne = $res->fetch();
            while($laLigne != null) {
                $id = $laLigne['id'];
                $nom = $laLigne['nom'];
                $prenom = $laLigne['prenom'];
                        
                $lesUtilisateurs["$id"]=array(
                "id"=>"$id",    
                "nom"=>"$nom",
                "prenom"  => "$prenom"
                );
                $laLigne = $res->fetch(); 
            }
                    
            return $lesUtilisateurs;
        }
        
/** 
 * 
 * Transaction effectuant le report d'un frais hors forfait au mois suivant.
 * Le frais est supprimé et reporté sur la fiche du mois suivant. Si cette fiche
 *  n'existe pas, elle est automatiquement créer.
 
 * @param type $idVisiteur
 * @param type $idFraisHorsForfait
 * @param type $libelle
 * @param type $date sous la forme jj/mm/aaaa
 * @param type $montant
 * @param type $mois sous la forme aaaamm
 */        
        public function transactionReportHorsForfait($idVisiteur,$idFraisHorsForfait,$libelle,$date,$montant,$mois) {
            try {
                PdoGSB::$monPdo->begintransaction();
                $this->supprimerFraisHorsForfait($idFraisHorsForfait);
                $moisSuivant = addMois($mois);
                if($this->estPremierFraisMois($idVisiteur,$moisSuivant)){
                    $this->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);
                }
                
                $this->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date,$montant);
                
                PdoGsb::$monPdo->commit();
            }
            catch (Exception $e) {
                PdoGsb::$monPdo->rollback();
            }
        }
        
/** Récupère le montant correspondant à une fiche de frais identifier par les deux paramètres
 * 
 * @param type $idVisiteur
 * @param type $mois
 * @return type montant
 */        
        public function getMontantFicheFrais($idVisiteur,$mois) {
            $req = "select fichefrais.montantValide as montant from  fichefrais where fichefrais.idUtilisateur ='$idVisiteur' and fichefrais.mois = '$mois'";
            $res = PdoGsb::$monPdo->query($req);
            $laLigne = $res->fetch();
            return $laLigne['montant'];
        }
/**
 * Met à jour le montant d'une fiche de frais identifier par les deux premier paramètres par le montant du troisième paramètres
 * 
 * @param type $idVisiteur
 * @param type $mois
 * @param type $montant
 */        
        public function updateMontantFicheFrais($idVisiteur,$mois,$montant) {
            $req = "update fichefrais set fichefrais.montantValide = '$montant' where fichefrais.idUtilisateur ='$idVisiteur' and fichefrais.mois = '$mois'";
            PdoGsb::$monPdo->exec($req);
        }
        
        
        public function transactionModifierEtatHorsForfait($idVisiteur,$leFraisHorsForfait,$montant,$mois,$nbJustificatifs) {
            try {
                PdoGSB::$monPdo->begintransaction();
                $this->majNbJustificatifs($idVisiteur,$mois, $nbJustificatifs);
                $this->updateMontantFicheFrais($idVisiteur,$mois,$montant);
                $this->MajFraisHorsForfait($idVisiteur,$mois,$leFraisHorsForfait);
                PdoGsb::$monPdo->commit();
            }
            catch (Exception $e) {
                PdoGsb::$monPdo->rollback();
            }
        }
}
?>