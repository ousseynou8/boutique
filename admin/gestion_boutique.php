<style>
  body {
      background-color: rgba(248, 142, 20, 0.733) !important;
      width: 100% !important;
      height: 100% !important;
  }
  
  form {
      background-color: rgb(238, 168, 88) !important;
      padding: 3%;
      border-radius: 10px;
      font-size: 1.5rem !important;
      margin: 0 auto;
  }
  
  h1 {
      text-align: center !important;
      font-size: 3rem !important;
      padding: 10px;
      box-shadow: 2px 3px 0px black;
      text-shadow: 2px 3px 0px black;
      color: white;
  }
  
  footer {
      bottom: 0 !important;
      top: 50px !important;
  }
</style>
<?php require_once "../inc/header.inc.php"; ?>
<?php

//Restriction d'accès à la page :
if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page de connexion

    header('location:../connexion.php');
    exit();
}
//----------------------------------------------------------------
//SUPPRESSION :

//debug( $_GET );
if( isset( $_GET['action']) && $_GET['action'] == 'suppression' ){
//S'il y a une 'action' dans l'URL ET que cette 'action' est égale à 'suppression'

    //Récupération de l'adresse de l'image en bdd (à supprimer):
    $r = execute_requete(" SELECT photo FROM produit WHERE id_produit = '$_GET[id_produit]' ");

    //Application de la émthode fetch() pour pouvoir exploiter les données :
    $photo_a_supprimer = $r->fetch( PDO::FETCH_ASSOC );
        //debug( $photo_a_supprimer );

    $chemin_photo_a_supprimer = str_replace( "http://localhost", $_SERVER['DOCUMENT_ROOT'], $photo_a_supprimer['photo'] );
        //debug( $chemin_photo_a_supprimer );    

    //str_replace( arg1, arg2, arg3 )
        //arg1 : La chaine que l'on veut remplacer
        //arg2 : la chaine de remplacement
        //arg3 : sur quelle chaine je veux effectuer les changements

    /*Ici, je remplace : 'http://localhost
                    par : C:/xampp/htdocs (=> $_SERVER['DOCUMENT_ROOT'])
                    dans : $photo_a_supprimer['photo'] (=> qui correspond au chemin de la photo en BDD)
    */

    if( !empty( $chemin_photo_a_supprimer ) && file_exists( $chemin_photo_a_supprimer ) ){
    // Si le chemin de la photo a supprimer n'est pas vide ET que le fichier existe

        chmod( $chemin_photo_a_supprimer, 0777 ); //mode 0777 : donne l'autorisation A TOUT LE MONDE d'écrire, de lire et d'exécuter le chemin
        unlink( $chemin_photo_a_supprimer );
        //unlink() : permet de supprimer un fichier
            //methode pour permission denied : /propriétés/ décocher lecture seule
    }

    //Suppression :
    execute_requete(" DELETE FROM produit WHERE id_produit = '$_GET[id_produit]' ");

    //redirection pour l'affichage :
    header('location:gestion_boutique.php?action=affichage');
}

//----------------------------------------------------------------
//INSERTION / MODIFICATION :
if( !empty($_POST) ){ //Si le formulaire a été validé et qu'il n'est pas vide

    //On passe les valeurs postées dans htmlentities() et addslashes()
    foreach( $_POST as $key => $value ){

        $_POST[$key] = htmlentities( addslashes( $value ) );
        //htmlentities() : Convertit tous les caractères éligibles en entités HTML (ex: '<' => &lt;)
    }

    //----------------------------------------------
    if( isset( $_GET['action']) && $_GET['action'] == 'modification' ){
    //Si je suis dans le cadre d'une modification, je récupère le chemin en bdd (grâce à l'input type="hidden")

        $photo_bdd = $_POST['photo_actuelle'];
    }
    //----------------------------------------------
     //debug($_POST);
     //debug($_FILES);
     //debug($_SERVER);

    if( !empty( $_FILES['photo']['name'] ) ){ //Si le nom de la photo n'est pas vide, c'est que l'on a uploadé un fichier

        //Ici, je renomme la photo :
        $nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];

        //Chemin pour accéder à la photo en BDD : ( http://localhost/boutique/photo )
        $photo_bdd = URL . "photo/$nom_photo";
            //debug( $photo_bdd );

        //Ou est-ce que l'on veut stocker la photo : ( C:/xampp/htdocs/boutique/photo )
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/boutique/photo/$nom_photo";
            //$_SERVER['DOCUMENT_ROOT'] => C:/xampp/htdocs
            //debug( $photo_dossier );

        copy( $_FILES['photo']['tmp_name'], $photo_dossier );
        // copy( arg1, arg2 )
            // arg1 : chemin du fichier source
            // arg2 : chemin de destination
    }

    //----------------------------------------------
    if( isset($_GET['action']) && $_GET['action'] == 'modification' ){
    //Si je suis dans le cadre d'une modification 

        execute_requete(" UPDATE produit SET 
                                                reference = '$_POST[reference]',
                                                categorie = '$_POST[categorie]',
                                                titre = '$_POST[titre]',
                                                description = '$_POST[description]',
                                                couleur = '$_POST[couleur]',
                                                taille = '$_POST[taille]',
                                                sexe = '$_POST[sexe]',
                                                photo = '$photo_bdd',
                                                prix = '$_POST[prix]',
                                                stock = '$_POST[stock]'
                                            WHERE id_produit = '$_GET[id_produit]'
                        ");
        //redirection pour l'affichage :
        header('location:gestion_boutique.php?action=affichage');
    }
    else{ //sinon, c'est que je ne suis pas dans une modification donc je fais une insertion

        execute_requete(" INSERT INTO produit( reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock) VALUES (  '$_POST[reference]', '$_POST[categorie]','$_POST[titre]','$_POST[description]','$_POST[couleur]','$_POST[taille]','$_POST[sexe]','$photo_bdd','$_POST[prix]','$_POST[stock]') ");

        //redirection pour l'affichage :
        header('location:gestion_boutique.php?action=affichage');
    }
}
//-------------------------------------------------------------------------
//Affichage des produits :
if( isset( $_GET['action'] ) && $_GET['action'] == "affichage" ){
//Si il existe une 'action' dans l'URL ET que cette 'action' est égale à 'affichage'

    $r = execute_requete(" SELECT * FROM produit ");

    $content .= "<h2>Liste des produits</h2>";
    $content .= "<p>Nombre d'articles dans la boutique : ". $r->rowCount() ."</p>";

    $content .= '<table border="1" cellpadding="5">';
        $content .= "<tr>";
            for( $i = 0; $i < $r->columnCount(); $i++ ){

                $colonne = $r->getColumnMeta( $i );
                //debug( $colonne );
                $content .= "<th>$colonne[name]</th>";
            }
            $content .= "<th>Suppression</th>";
            $content .= "<th>Modification</th>";
        $content .= "</tr>";

        while( $ligne = $r->fetch(PDO::FETCH_ASSOC) ) :
            $content .= '<tr>';
                //debug( $ligne );
                foreach( $ligne as $key => $value ) :
                    //Afficher la photo (et non pas l'adresse ) dans le tableau

                    if( $key == 'photo' ){ //Si l'indice est égal à 'photo' alors je crée une cellule avec une balise img et la valeur correspondante dans l'attribut 'src' de la balise

                        $content .= "<td><img src='$value' width='80'></td>";
                    }
                    else{ //Sinon, on affiche les valeurs dans des cellules simples

                        $content .= "<td>$value</td>";
                    }
                endforeach;

                $content .= '<td class="text-center">
                                <a href="?action=suppression&id_produit='. $ligne['id_produit'] .'" onclick="return( confirm(\'En êtes vous certain ?\') )" >
                                    <i class="fas fa-trash-alt"></i>                                
                                </a>
                            </td>';
                $content .= '<td class="text-center">
                                <a href="?action=modification&id_produit='. $ligne['id_produit'] .'" >
                                    <i class="fas fa-edit"></i>                                
                                </a>
                            </td>';

            $content .= '</tr>';
        endwhile;
    $content .= '</table>';
}

//-------------------------------------------------------------------------------------------------
?>
<h1>GESTION BOUTIQUE</h1>

<a href="?action=affichage">Affichage des articles</a><br>
<a href="?action=ajout">Ajout d'un article</a><hr>

<?= $content; //affichage du contenu ?>

<?php if( isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification' ) ) : 
    //Si il y a une 'action' dans l'URL ET que cette 'action' est égale à 'ajout' OU à 'modification', alors on affiche le formulaire    

    if( isset( $_GET['id_produit'] ) ){
        //Si il existe 'id_produit' dans l'URL, c'est forcément que je suis dans le cadre d'une modification

        //Je récupére les infos du produit à modifier :
        $r = execute_requete(" SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");

        $article_actuel = $r->fetch( PDO::FETCH_ASSOC );
            //debug( $article_actuel );
    }

    if( isset( $article_actuel['reference'] ) ){

        $reference = $article_actuel['reference'];     
    }
    else{
        $reference = '';
    }
    //version ternaire de la ligne du dessus :
    //$reference = ( isset($article_actuel['reference']) ) ? $article_actuel['reference'] : '';

    $categorie = ( isset($article_actuel['categorie']) ) ? $article_actuel['categorie'] : '';
    $titre = ( isset($article_actuel['titre']) ) ? $article_actuel['titre'] : '';
    $description = ( isset($article_actuel['description']) ) ? $article_actuel['description'] : '';
    $couleur = ( isset($article_actuel['couleur']) ) ? $article_actuel['couleur'] : '';
    $prix = ( isset($article_actuel['prix']) ) ? $article_actuel['prix'] : '';
    $stock = ( isset($article_actuel['stock']) ) ? $article_actuel['stock'] : '';

    $sexe_f = ( isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'f' ) ? 'checked' : '';
    $sexe_m = ( isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'm' ) ? 'checked' : '';

    $taille_s = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'S' ) ? 'selected' : '';
    $taille_m = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'M' ) ? 'selected' : '';
    $taille_l = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'L' ) ? 'selected' : '';
    $taille_xl = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'XL' ) ? 'selected' : '';

//----------------------------------------------------------------------------------------------------
?>

<form method="post" enctype="multipart/form-data" >
<!-- enctype="multipart/form-data" : INDISPENSABLE lorsque l'on veut uploader un fichier -->

    <label>Référence</label><br>
    <input type="text" name="reference" class="form-control" value="<?= $reference ?>" ><br>

    <label>Catégorie</label><br>
    <input type="text" name="categorie" class="form-control" value="<?= $categorie ?>" ><br>

    <label>Titre</label><br>
    <input type="text" name="titre" class="form-control" value="<?= $titre ?>" ><br>

    <label>Description</label><br>
    <input type="text" name="description" class="form-control" value="<?= $description ?>"><br>

    <label>Couleur</label><br>
    <input type="text" name="couleur" class="form-control" value="<?= $couleur ?>" ><br>

    <label>Taille</label><br>
    <select name="taille" class="form-control">
        <option value="S" <?= $taille_s ?> >S</option>
        <option value="M" <?= $taille_m ?> >M</option>
        <option value="L" <?= $taille_l ?> >L</option>
        <option value="XL" <?= $taille_xl ?> >XL</option>
    </select><br>

    <label>Civilité</label><br>
    <input type="radio" name="sexe" value="f" <?= $sexe_f ?> >Femme <br>
    <input type="radio" name="sexe" value="m" <?= $sexe_m ?> >Homme <br><br>

    <label>Photo</label><br>
    <input type="file" name="photo"><br><br>

    <?php

        if( isset( $article_actuel['photo'] ) ){ //SI il existe $article_actuel, c'est que je suis dans le cadre d'une modification

            echo '<i>Vous pouvez uplaoder une nouvelle photo.</i><br>';
            echo "<img src='$article_actuel[photo]' width='80' ><br><br>";

            echo "<input type='hidden' name='photo_actuelle' value='$article_actuel[photo]' >";
        }
    ?>

    <label>Prix</label><br>
    <input type="text" name="prix" class="form-control" value="<?= $prix ?>" ><br>

    <label>Stock</label><br>
    <input type="text" name="stock" class="form-control" value="<?= $stock ?>" ><br>

    <input type="submit" class="btn btn-secondary" value="<?php echo ucfirst( $_GET['action'] ); ?>">
    <!-- ucfirst() : permet de passer la première lettre en majuscule -->
</form>

<?php endif; ?>

<?php require_once "../inc/footer.inc.php"; ?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
