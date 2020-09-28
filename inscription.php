<?php require_once "inc/header.inc.php"; ?>
<?php

if( userConnect() ){ //Si l'utilisateur est connecté, on le redirige vers son profil

    header('location:profil.php');
    exit();
}

//----------------------------------------------

if( $_POST ){ //Si on valide le formulaire

    //debug( $_POST );

    if( strlen( $_POST['pseudo'] ) <= 3 || strlen( $_POST['pseudo']) >= 21 ){ //Si la taille du pseudo est inférieure ou égal à 3 OU que la taille est supérieure ou égale à 21, ALORS j'affiche un msg d'erreur

        $error .= '<div class="alert alert-danger" >Erreur taille pseudo</div>';
    }

    //Tester si le pseudo est disponible :
    $r = execute_requete(" SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");

    if( $r->rowCount() >= 1 ){
        //Si la requête renvoie au moins une ligne de résultat, c'est que le pseudo est déjà attribué

        $error .= '<div class="alert alert-danger" >Pseudo indisponible</div>';
    }

    //Boucle sur les saisies afin de les passer dans les fonctions htmlspecialchars et addslashes
    foreach( $_POST as $index => $value ){

        $_POST[$index] = htmlspecialchars( addslashes( $value ) );
    }

    //Cryptage du mot de passe :
    $_POST['mdp'] = password_hash( $_POST['mdp'], PASSWORD_DEFAULT );

    if( empty( $error ) ){ //Si ma variable $error est vide

        execute_requete(" INSERT INTO membre( pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut ) VALUES ( '$_POST[pseudo]',
                                    '$_POST[mdp]', 
                                    '$_POST[nom]', 
                                    '$_POST[prenom]', 
                                    '$_POST[email]',  
                                    '$_POST[sexe]', 
                                    '$_POST[ville]', 
                                    '$_POST[cp]', 
                                    '$_POST[adresse]',
                                     '0' ) ");

        echo '<div class="alert alert-success">
                Inscription validée
                <a href="' . URL . 'connexion.php">
                    Cliquez ici pour vous connecter
                </a>
            </div>';
    }
}

//----------------------------------------------------------------------------------------
?>
<h1>Inscription</h1>

<?= $error; //affichage des erreurs ?>

<form method="post">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" name="pseudo" id="pseudo" class="form-control" ><br>

    <label for="mdp">Mot de passe</label><br>
    <input type="text" name="mdp" id="mdp" class="form-control" ><br>

    <label for="prenom">Prenom</label><br>
    <input type="text" name="prenom" id="prenom" class="form-control" ><br>

    <label for="nom">Nom</label><br>
    <input type="text" name="nom" id="nom" class="form-control" ><br>

    <label for="email">Email</label><br>
    <input type="text" name="email" id="email" class="form-control" ><br>

    <label>Civilite</label><br>
    <input type="radio" name="sexe" value="m" checked>Homme<br>
    <input type="radio" name="sexe" value="f" >Femme<br><br>

    <label for="ville">ville</label><br>
    <input type="text" name="ville" id="ville" class="form-control" ><br>

    <label for="cp">Code postal</label><br>
    <input type="text" name="cp" id="cp" class="form-control" ><br>
    
    <label for="adresse">adresse</label><br>
    <input type="text" name="adresse" id="adresse" class="form-control" ><br>

    <input type="submit" class="btn btn-secondary" value="S'inscrire">
</form>

<?php require_once "inc/footer.inc.php"; ?>