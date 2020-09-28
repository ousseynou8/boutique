<?php require_once "inc/header.inc.php"; ?>
<?php

if( !userConnect() ){ //Si l'internaute N'EST PAS connecté

    //redirection vers la page de connexion
    header('location:connexion.php');
    exit(); //exit() : termine la lecture du script courant
}

//--------------------------------------------------------------------
if( adminConnect() ){ //Si l'administateur est connecté

    $content .= "<h2 style='color:red;'>ADMINISTRATEUR</h2>";
}

//--------------------------------------------------------------------

//debug( $_SESSION );

$content .= "<h2> Bienvenu ". $_SESSION['membre']['pseudo'] ."</h2>";

$content .= "<p>Voici vos informations personnelles :</p>";

$content .= "<p>Votre prénom : " . $_SESSION['membre']['prenom'] . "</p>";
$content .= "<p>Votre nom : " . $_SESSION['membre']['nom'] . "</p>";
$content .= "<p>Votre email : " . $_SESSION['membre']['email'] . "</p>";

$content .= "<p>Votre adresse : " . $_SESSION['membre']['adresse'] . " - " . $_SESSION['membre']['cp'] ." - " . $_SESSION['membre']['ville'] ."</p><br><br>";

//--------------------------------------------------------------------------------
?>
<h1>Profil</h1>

<?= $content; //affichage du contenu ?>

<?php require_once "inc/footer.inc.php"; ?>