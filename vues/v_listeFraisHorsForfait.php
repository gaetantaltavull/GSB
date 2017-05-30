
<table class="listeLegere">
    <caption>Descriptif des éléments hors forfait
    </caption>
    <tr>
        <th class="date">Date</th>
        <th class="libelle">Libellé</th>  
        <th class="montant">Montant</th>  
        <th class="action">Supprimer</th>              
    </tr>

    <?php
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
        $libelle = $unFraisHorsForfait['libelle'];
        $date = $unFraisHorsForfait['date'];
        $montant = $unFraisHorsForfait['montant'];
        $id = $unFraisHorsForfait['id'];
        ?>		
        <tr>
            <td> <?php echo $date ?></td>
            <td><?php echo $libelle ?></td>
            <td><?php echo $montant ?></td>
            <td><div class="form-group table-button-icon">
                    <a class="form-control" href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=<?php echo $id ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>
            </div>
        </tr>
        <?php
    }
    ?>	  

</table>

<div class="form-little">
    <form action="index.php?uc=gererFrais&action=validerCreationFrais" method="post">
        <fieldset class="form-group">
            <legend>Nouvel élément hors forfait
            </legend>
            <div class="form-group">
                <label for="txtDateHF">Date (jj/mm/aaaa): </label>
                <input class="form-control" type="text" id="txtDateHF" name="dateFrais" size="10" maxlength="10" value=""  />
            </div>
            <div class="form-group">
                <label for="txtLibelleHF">Libellé</label>
                <input class="form-control" type="text" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
            </div>
            <div class="form-group">
                <label for="txtMontantHF">Montant : </label>
                <input class="form-control" type="text" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
            </div>
            

        <input class="pull-right" id="ajouter" type="submit" value="Ajouter" size="20" />
        <input class="pull-right" id="effacer" type="reset" value="Effacer" size="20" />
        </fieldset>
    </form>
</div>
</div>


