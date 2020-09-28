<?php require_once "inc/header.inc.php"; ?>
<?php

//debug( $_GET );
//Affichage des informations concernant le produit choisi :
if( isset($_GET['id_produit']) ){ //S'il existe 'id_produit' dans l'URL

    $r = execute_requete(" SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
}
else{ //sinon, redirection vesr l'accueil

    header('location:boutique.php');
    exit();
}

$produit = $r->fetch(PDO::FETCH_ASSOC);
    //debug( $produit );

$content .= '<a href="boutique.php">Retour vers l\'accueil</a><br>';

$content .= "<a href='boutique.php?categorie=$produit[categorie]'>Retour vers la catégorie $produit[categorie] </a><br>";

$content .=  "<p>$produit[categorie]</p>";
$content .=  "<p>$produit[couleur]</p>";
$content .=  "<p>$produit[taille]</p>";

$content .= "<p><img src='$produit[photo]' width='200' ></p>";

$content .=  "<p>$produit[description]</p>";
$content .=  "<p>$produit[prix] €</p>";

//------------------------------------------------------------------------------------------
//Gestion du stock/bouton ajout panier :
if( $produit['stock'] > 0 ){ //Si le stock est supérieur à zero 

    $content .= "Nombre de produits disponibles : $produit[stock] <br><br> ";

    $content .= "<form method='post' action='panier.php' >";

        $content .= "<label>Quantité</label><br>";
        $content .= '<select name="quantite">';

            for( $i = 1; $i <= $produit['stock']; $i++){

                $content .= "<option> $i </option>";
            }

        $content .= '</select><br><br>';

        $content .= '<input type="hidden" name="id_produit" value="'.$produit['id_produit'].'">';
        //Input type="hidden" pour faire passer l'id_produit dans la page panier pour pouvoir récupérer les infos du produit sélectionné

        $content .= "<input type='submit' name='ajout_panier' value='Ajouter au panier' class='btn btn-secondary' >";

    $content .= '</form>';

}
else{ //Sinon... on affiche 'rupture de stock'

    $content .= "<p>Rupture de stock</p>";
}


?>
<h1><?= $produit['titre'] ?></h1>

<?= $content ?>

<?php require_once "inc/footer.inc.php"; ?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>