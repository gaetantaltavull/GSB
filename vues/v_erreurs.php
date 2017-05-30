<div class ="alert alert-danger messages">
<ul>
<?php 
foreach($_REQUEST['erreurs'] as $erreur)
	{
      echo "<li>$erreur</li>";
	}
?>
</ul></div>
