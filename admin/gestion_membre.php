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
<?php require_once "../inc/header.inc.php";  ?>
<?php

//Restriction d'accès à la page d'administration :
if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, alors on le redirige vers la page de connexion.

    header('location:../connexion.php');
    exit();
}
//-------------------------------------------------------------
//SUPPRESSION :
//debug( $_GET );
if( isset( $_GET['action'] ) && $_GET['action'] == 'suppression' ){

    execute_requete(" DELETE FROM membre WHERE id_membre = '$_GET[id_membre]' ");
}

//-------------------------------------------------------------
//MODIFICATION :
if( isset($_GET['action']) && $_GET['action'] == 'modification') :

    $r = execute_requete(" SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]' ");

    $membre_a_modifier = $r->fetch(PDO::FETCH_ASSOC);
        //debug( $membre_a_modifier );

    $pseudo = $membre_a_modifier['pseudo']; 
    $nom = $membre_a_modifier['nom']; 
    $prenom = $membre_a_modifier['prenom']; 
    $email = $membre_a_modifier['email']; 
    $sexe = $membre_a_modifier['sexe']; 
    $ville = $membre_a_modifier['ville']; 
    $cp = $membre_a_modifier['cp']; 
    $adresse = $membre_a_modifier['adresse']; 
    $statut = $membre_a_modifier['statut']; 

    debug( $_POST );
    if( isset($_POST['modifier']) ){ //Si il existe $_POST['modifier'], c'est que j'ai validé le formulaire (où l'input submit à l'attribut 'name' égal à 'modifier')

        execute_requete(" UPDATE membre SET pseudo = '$_POST[pseudo]',
                                            prenom = '$_POST[prenom]',
                                            nom = '$_POST[nom]',
                                            ville = '$_POST[ville]',
                                            cp = '$_POST[cp]',
                                            adresse = '$_POST[adresse]',
                                            statut = '$_POST[statut]',
                                            email = '$_POST[email]',
                                            sexe = '$_POST[sexe]'
                                            WHERE id_membre = '$_GET[id_membre]'
                        ");
        //redirection pour l'affichage
        header('location:gestion_membre.php');
    }

?>

<form method="post">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $pseudo ?>"><br>

    <label for="prenom">Prenom</label><br>
    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $prenom ?>"><br>

    <label for="nom">Nom</label><br>
    <input type="text" name="nom" id="nom" class="form-control" value="<?= $nom ?>"><br>

    <label for="email">Email</label><br>
    <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>"><br>

    <label>Civilite</label><br>
    <input type="radio" name="sexe" value="m" <?php if( $sexe == 'm') echo 'checked'; ?>  >Homme<br>
    <input type="radio" name="sexe" value="f" <?php if( $sexe == 'f') echo 'checked'; ?> >Femme<br><br>

    <label for="ville">ville</label><br>
    <input type="text" name="ville" id="ville" class="form-control" value="<?= $ville ?>" ><br>

    <label for="cp">Code postal</label><br>
    <input type="text" name="cp" id="cp" class="form-control"value="<?= $cp ?>" ><br>
    
    <label for="adresse">adresse</label><br>
    <input type="text" name="adresse" id="adresse" class="form-control" value="<?= $adresse ?>"><br>
    
    <label for="statut">Statut</label><br>
    <input type="text" name="statut" id="statut" class="form-control" value="<?= $statut ?>" ><br>

    <input type="submit" name="modifier" class="btn btn-secondary" value="Modifier">
</form><hr>

<?php
endif; //fin de la condition de la modification
//-------------------------------------------------------------
//Affichage des membres :
$r = execute_requete(" SELECT * FROM membre ");

$content .= "<h2>Affichage des " . $r->rowCount() . " membres.</h2>";

$content .= '<table border="1" cellpadding="5">';
    $content .= "<tr>";
        for( $i = 0; $i < $r->columnCount(); $i++ ){
    
            $colonne = $r->getColumnMeta( $i ); 
            //debug( $colonne );
            $content .= "<th>$colonne[name]</th>";
        }
        $content.="<th>Suppression</th>";
        $content.="<th>Modification</th>";
    $content .= "</tr>";

    while( $membre = $r->fetch( PDO::FETCH_ASSOC ) ) {
        //debug( $membre );
        $content .= '<tr>';
            foreach( $membre as $cle => $value ){ //Une boucle foreach c'est fait pour parcourir EXCLUSIVEMENT des tableaux (arrays)

                $content .= "<td>$value</td>";
            }
            $content .= "<td class='text-center'>
                            <a href='?action=suppression&id_membre=". $membre['id_membre'] ."' onclick='return( confirm(\" En êtes vous certain ?\") )' >
                                <i class='fas fa-trash-alt'></i>
                            </a>
                        </td>";
            $content .= "<td class='text-center'>
                            <a href='?action=modification&id_membre=". $membre['id_membre'] ."' )' >
                                <i class='fas fa-edit'></i>
                            </a>
                        </td>";
        $content .= '</tr>';
    }
$content .= '</table>';

//--------------------------------------------------------------------------------------------
?>
<h1>Gestion des membres</h1><br>

<?php echo $content; ?>

<?php require_once "../inc/footer.inc.php";  ?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>