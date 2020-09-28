<?php require_once "inc/header.inc.php"; ?>
<?php

//DECONNEXION :
if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ){
//Si il y a une 'action' dans l'URL ET que cette action est égale à 'deconnexion', alors on détruit la session

    session_destroy();
}

//--------------------------------------------------------------

if( userConnect() ){ //Si l'internaute est connecté, on le redirige vers la page de profil

    //redirection vers le profil.php
    header('location:profil.php');
    exit();
}

//--------------------------------------------------------------

if( $_POST ){ //Si je valide le formulaire ("submit")
    //debug( $_POST );

    $r = execute_requete(" SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");

    //Si il y a une correspondance dans la table 'membre', '$r' renverra UNE ligne de résultat
    if( $r->rowCount() >= 1  ){ 

        $membre = $r->fetch( PDO::FETCH_ASSOC );
        debug( $membre );

        //Verification du mot de passe : Si le mdp est correct, on renseigne des informations dans notre fichier de session
        if( password_verify( $_POST['mdp'], $membre['mdp'] ) ){

            $_SESSION['membre']['id_membre'] = $membre['id_membre'];
            $_SESSION['membre']['pseudo'] = $membre['pseudo'];
            $_SESSION['membre']['mdp'] = $membre['mdp'];
            $_SESSION['membre']['prenom'] = $membre['prenom'];
            $_SESSION['membre']['nom'] = $membre['nom'];
            $_SESSION['membre']['email'] = $membre['email'];
            $_SESSION['membre']['sexe'] = $membre['sexe'];
            $_SESSION['membre']['ville'] = $membre['ville'];
            $_SESSION['membre']['cp'] = $membre['cp'];
            $_SESSION['membre']['adresse'] = $membre['adresse'];
            $_SESSION['membre']['statut'] = $membre['statut'];

            //debug( $_SESSION );
            //Redirection vers la page profil.php
            header('location:profil.php');
        }
        else{

            $error .= '<div class="alert alert-danger">Erreur mdp</div>';
        }
    }
    else{ //Sinon c'est que le pseudo n'est pas bon, on affiche un msg d'erreur

        $error .= '<div class="alert alert-danger">Erreur pseudo</div>';
    }
}

//-------------------------------------------------------------------------------------
?>
<h1>Connexion</h1>

<?= $error; //affichage des erreurs ?>

<form method="post">
    <label>Pseudo</label><br>
    <input type="text" name="pseudo" class="form-control" placeholder="Votre pseudo"><br>

    <label>Mot de passe</label><br>
    <input type="text" name="mdp" class="form-control" placeholder="Votre mot de passe"><br>

    <input type="submit" value="Connexion" class="btn btn-secondary">
</form>

<?php require_once "inc/footer.inc.php"; ?>
