    <!-- Division pour le sommaire -->
<div id="menuGauche">
    <img src="./images/logoGSB_158x98.png" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
    <div id="infosUtil">
    </div>  

    
    
    <?php 
    if (estConnecte() && (isset($_REQUEST['uc']) && $_REQUEST['action'] != 'deconnexion')) {
    ?>
    <div id="menuItem" class="list-group">
        <p href="#" class="list-group-item active">
            <?php
            echo $_SESSION['typeUtilisateur'] . ": ";
            echo $_SESSION['prenom'] . "  " . $_SESSION['nom']
            ?>
        </p>

        <?php
        switch ($_SESSION['typeUtilisateur']) {
            case "Visiteur" :
                ?>
                <a href="index.php?uc=gererFrais&action=saisirFrais" class="list-group-item">Saisie fiche de frais</a>
                <a href="index.php?uc=etatFrais&action=selectionnerMois" class="list-group-item">Mes fiches de frais</a>
                <?php
                break;
            case "Comptable" :
                ?>
                <a href="index.php?uc=validerFrais&action=choixUtilisateur" class="list-group-item">Valider fiches frais</a>
                <a href="index.php?uc=suivrePaiement&action=choixUtilisateur" class="list-group-item">Suivre fiches frais</a>
                <?php
                break;
        }
        ?>
        <a href="index.php?uc=connexion&action=deconnexion" class="list-group-item">Déconnexion</a>

    </div>
    <?php } ?>


</div>
