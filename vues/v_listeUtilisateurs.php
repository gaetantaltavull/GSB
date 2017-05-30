
<div id="contenu">
    <?php if ($modifier) { ?>
    <h2>Valider fiches frais</h2>﻿ 
    <?php } else { ?>
    <h2>Suivre fiches frais</h2>﻿
    <?php } ?>
    <div class="form-little">
    <?php if ($modifier) { ?>
        <form action="index.php?uc=validerFrais" method="post">
        <?php } else { ?>
            <form action="index.php?uc=suivrePaiement" method="post">
            <?php } ?>
            <fieldset class="form-group">
                <legend>Fiche de frais</legend>
                <p>
                    <label for="lstUtilisateur" accesskey="n">Utilisateur : </label>
                    <?php if ($modifier) { ?>
                        <select id="lstUtilisateur" name="lstUtilisateur" onchange="changeModification(this.value)">
                        <?php } else { ?>
                            <select id="lstUtilisateur" name="lstUtilisateur" onchange="changeSuivie(this.value)">
                                <?php
                            }
                            foreach ($lesUtilisateurs as $unUtilisateur) {
                                $id = $unUtilisateur['id'];
                                $nom = $unUtilisateur['nom'];
                                $prenom = $unUtilisateur['prenom'];
                                if (isset($utilisateurEnSelection) && $utilisateurEnSelection == $id) {
                                    ?><option selected value="<?php echo $id ?>"><?php echo $nom . " " . $prenom ?> </option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $id ?>"><?php echo $nom . " " . $prenom ?> </option>
                                    <?php
                                }
                            }
                            ?>  
                        </select>
                </p>

                <p>
                    <label for="lstMois" accesskey="n">Mois : </label>
                    <select id="lstMois" name="lstMois">
                        <?php
                        if (count($lesMois) > 0) {
                            foreach ($lesMois as $unMois) {
                                $mois = $unMois['mois'];
                                $numAnnee = $unMois['numAnnee'];
                                $numMois = $unMois['numMois'];
                                if ($mois == $leMois) {
                                    ?>
                                    <option selected value="<?php echo $mois ?>"><?php echo $numMois . "/" . $numAnnee ?> </option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $mois ?>"><?php echo $numMois . "/" . $numAnnee ?> </option>
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <option selected value="-1">Aucunes fiche</option>
                            <?php
                        }
                        ?>    
                    </select>
                </p>
                <p>
                    <input class="pull-right" id="ok" type="submit" value="Valider" size="20" />
                </p>
            </fieldset>

        </form>
    </div>

