    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  
				<?php 
                                echo $_SESSION['typeUtilisateur'] . ":<br>";
                                echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
            <?php
            switch ($_SESSION['typeUtilisateur'])
            { 
                case "Visiteur" : ?>
                    <li class="smenu">
                       <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
                    </li>
                    <li class="smenu">
                       <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
                    </li>
                    <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
                    </li>
            <?php 
                break;
                case "Comptable" : ?>
                    <li class="smenu">
                       <a href="index.php?uc=validerFrais&action=choixUtilisateur" title="Valider fiche de frais ">Valider fiches frais</a>
                    </li>
                    <li class="smenu">
                       <a href="index.php?uc=suivrePaiement&action=choixUtilisateur" title="Consultation de mes fiches de frais">Suivre fiches frais</a>
                    </li>
                    <li class="smenu">
                       <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
                    </li>
            <?php        
            } ?>
         </ul>
        
    </div>
    