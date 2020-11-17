<?php require_once "inc/header.inc.php"; ?>
<?php
//Affichage des produits sur la apge d'accueil :
$r = execute_requete(" SELECT DISTINCT categorie FROM produit ");

$content .= '<div class="row">';
    //Affichage des catégories :
    $content .= '<div class="col-lg-3">';
        $content .= '<div class="list-group-item">';
        while( $categorie = $r->fetch( PDO::FETCH_ASSOC ) ){

            //debug( $categorie );
            $content .= "<a href='?categorie=$categorie[categorie]' class='list-group-item'>
                            $categorie[categorie]
                        </a>";
        }
        $content .= '</div>';
    $content .= '</div>';

    //Affichage des produits correspondant à la catégorie sélectionnée :
    $content .= '<div class="col-lg-8 offset-1">';    
        $content .= '<div class="row">';     
       //Si il existe une 'categorie' dans l'URL

          if($_GET){
            $r = execute_requete(" SELECT * FROM produit WHERE categorie = '$_GET[categorie]' ");

            while( $produit = $r->fetch( PDO::FETCH_ASSOC ) ){

                //debug($produit);
                $content .= '<div class="col-lg-4">';
                    $content .= '<div class="thumbnail" style="border:1px solid #eee;">';

                        $content .= "<a href='fiche_produit.php?id_produit=$produit[id_produit]'>
                                        <img src='$produit[photo]' width='100'>
                                    </a>";

                        $content .= '<div class="caption">';
                            $content .= "<p>$produit[titre]</p>";
                            $content .= "<p>$produit[prix]</p>";

                            $content .= "<a href='fiche_produit.php?id_produit=$produit[id_produit]'>
                                            Voir la fiche produit
                                         </a>";

                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            }
          }
        
        $content .= '</div>';
    $content .= '</div>';

$content .= '</div>';

//------------------------------------------------------------------------------------
?>

<h1>Bienvenue dans Votre Boutique</h1>

<?= $content; //affichage du contenu  ?>

<?php require_once "inc/footer.inc.php"; ?>
