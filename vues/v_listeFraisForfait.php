<div id="contenu">
    <h2>Renseigner ma fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?></h2>

    <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
        <div class="form-little">

            <fieldset class="form-group">
                <legend>Eléments forfaitisés
                </legend>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = $unFrais['libelle'];
                    $quantite = $unFrais['quantite'];
                    ?>

                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" class="form-control" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>">
                    </div>

                    <?php
                }
                ?>
                
                <input id="ok" type="submit" value="Valider" size="20" class="pull-right" />
                <input id="annuler" type="reset" value="Effacer" size="20" class="pull-right" />
            </fieldset>
        </div>

    </form>
