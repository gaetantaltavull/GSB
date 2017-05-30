 <div id="contenu">
      <h2>Mes fiches de frais</h2>
      <form action="index.php?uc=etatFrais&action=voirEtatFrais" method="post">
      <div class="form-little">
          <fieldset class="form-group"> 
                <legend>Fiche frais</legend>
      <p>
	 
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
			    $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}
           
		   ?>    
            
        </select>
      </p>
      
        <input class="pull-right" id="ok" type="submit" value="Valider" size="20" />
        <input class="pull-right" id="annuler" type="reset" value="Effacer" size="20" />
        </fieldset>
      </div>
      </form>