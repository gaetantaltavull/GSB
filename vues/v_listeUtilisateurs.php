 <div id="contenu">
    <h2>Valider fiche frais</h2>
    <h3>Fiche : </h3>
    <?php if ($modifier) { ?>
        <form action="index.php?uc=validerFrais" method="post">
        <?php } else { ?>
            <form action="index.php?uc=suivrePaiement" method="post">
            <?php } ?>
            <div class="corpsForm">

                <p>
                    <label for="lstUtilisateur" accesskey="n">Utilisateur : </label>
                        <?php if ($modifier) { ?>
                            <select id="lstUtilisateur" name="lstUtilisateur" onchange="changeModification(this.value)">
                            <?php } else { ?>
                                <select id="lstUtilisateur" name="lstUtilisateur" onchange="changeSuivie(this.value)">
                            <?php } 
                                foreach ($lesUtilisateurs as $unUtilisateur) {
                                    $id = $unUtilisateur['id'];
                                    $nom = $unUtilisateur['nom'];
                                    $prenom = $unUtilisateur['prenom'];
                                    if (isset($utilisateurSelectionner) && $utilisateurSelectionner == $id) {
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
                                </div>
                                <div class="piedForm">
                                    <p>
                                        <input id="ok" type="submit" value="Valider" size="20" />
                                    </p> 
                                </div>

                            </form>

