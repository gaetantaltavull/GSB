

<?php
if ($_POST['lstMois'] != "-1") {
    ?>

    <!------------ FORFAIT ---------------->



    <h2>Contrôle des frais forfaitisés pour le <?php echo $leMois ?></h2>
    <form action="index.php?uc=validerFrais&action=validerForfait" method="POST">
        <table class="table">
            <tr>
                <?php
                foreach ($lesLibelleFraisForfait as $unLibelleFrais) {
                    $libelle = current($unLibelleFrais);
                    echo "<th>$libelle</th>";
                }
                ?>
                <th>
                    Mettre à jours
                </th>
                <th>
                    Réinitialiser
                </th>
            </tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                $idFrais = $unFraisForfait['idfrais'];
                ?>
                <td>
                    <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>">
                </td>
                <?php
            }
            ?>
            <td>
                <input id="ok" type="submit" value="Mettre à jours" size="20" />
            </td>
            <td>
                <input id="annuler" type="reset" value="Annuler" size="20" />
            </td>
        </table>
    </form>



    <!------------ HORS FORFAIT ---------------->
    <?php if (count($lesFraisHorsForfait) > 0) { ?>
        <h2>Contrôle des frais hors forfait</h2>

        <!-----------TABLEAU---------------->

        <table class="listeLegere">

            <!-----------TABLEAU-ENTETE---------------->

            <tr>
                <th>Libellé</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Valider le frais</th>
                <th>Refuser le frais</th>
                <th>Reporter au mois suivant</th>
            </tr>

            <!-----------TABLEAU-CORPS---------------->

        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $idFraisHorsForfait = $unFraisHorsForfait['id'];
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            $mois = $unFraisHorsForfait['mois'];
            $date = $unFraisHorsForfait['date'];
            ?>
                <tr>
                    <td width="400px"><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td><?php echo $date ?></td>
                    <td><div class="form-group table-button-icon"><a class="form-control" href ="index.php?uc=validerFrais&action=validerHorsForfait&idHF=<?php echo $idFraisHorsForfait ?>" ><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></a></div></td>
                    <td><div class="form-group table-button-icon"><a class="form-control" href ="index.php?uc=validerFrais&action=rejeterHorsForfait&idHF=<?php echo $idFraisHorsForfait ?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></div></td>
                    <td><div class="form-group table-button-icon"><a class="form-control" href ="index.php?uc=validerFrais&action=reporterHorsForfait&idHF=<?php echo $idFraisHorsForfait ?>&libelleHF=<?php echo $libelle ?>&montantHF=<?php echo $montant ?>&dateHF=<?php echo $date ?>&moisHF=<?php echo $mois ?>"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a></div></td>
                </tr>
            <?php
        }
        ?>
        </table>

        <!-----------TABLEAU-FIN---------------->


        <!-----------MISE EN PAIEMENT---------------->   



    <?php } ?>
        <div id="valider-fiche">
            <a href="index.php?uc=validerFrais&action=validerFiche" class="btn btn-success">Valider la fiche de frais</a>
        </div>

<?php } else { ?>
    <p>Pas de fiche de frais. Veuillez sélectionner des critères différents</p>
<?php }
?>