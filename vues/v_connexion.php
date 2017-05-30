

        ﻿<div id="connexion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Identification utilisateur</h3>
                </div>
                <div class="panel-body">

                     <form method="POST" action="index.php?uc=connexion&action=valideConnexion">

                       <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Login</span>
                            <input type="text" name="login" id="login" class="form-control" placeholder="" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">Mot de passe</span>
                            <input type="password" name="mdp" id="mdp" class="form-control" placeholder="" aria-describedby="basic-addon2">
                        </div>
                        <input class="btn btn-default pull-right" type="submit" name="valider" value="Se connecter">
                    </form>
                </div>
            </div>
        </div>
