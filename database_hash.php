<?php

echo "<p>hashage...</p>";
require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

$pdo = PdoGsb::getPdoGsb();
$pdo->hashMotDePasse();


echo "<p>Terminer</p>";