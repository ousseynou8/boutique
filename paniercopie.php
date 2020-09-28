<?php require_once "inc/header.inc.php"; ?>


<?php

if (isset($_POST['ajout_panier'])) { //Ici, on verifie l'existance d'un "submit" dans la fiche_produit.php ('ajout_panier' provient de l'attribut 'name' de l'input 'submit)


    //debug($_POST);

    $r = execute_requete(" SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]' ");
    // $_POST['id_produit'] provient de l'input type='hidden' dans fiche_produit.php
    $produit_panier = $r->fetch(PDO::FETCH_ASSOC);

    //debug($produit_panier);

    ajout_panier($produit_panier['titre'], $produit_panier['id_produit'],  $_POST['quantite'], $produit_panier['prix']);




    //debug($_SESSION);
}
//--------------------------------------------------------
//Action de vider la panier :
if( isset($_GET['action']) && $_GET['action'] == 'vider'){
    unset($_SESSION['panier']);
    //unset() ; permet de detruire la variable $_SESSION['panier'] => qui revient a vider votre panier
}

//----------------------------------------------
//Action de retirer un produit la panier : 
    if(isset($_GET['action']) && $_GET['action'] == 'retirer'){
            retirer_proquit_panier($_GET['id_produit']);
    }


//----------------------------------------------
//Affichage du panier : 
$content .= '<table class="table" >';
$content .= "<tr>
                    <th>Titre</th>
                    <th>Quantite</th>
                    <th>Prix</th>
                    <th>Retirer</th>
                </tr>";

if (empty($_SESSION['panier']['id_produit'])) { //si ma session/panier/id_produit est vide, c'est que je n'ai pas produit dans mon panier 

    $content .= "<tr> <td colspan='3'> Votre panier est vide </td> </tr>";
} else {

    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        $content .= "<tr>";
        $content .= '<td>' . $_SESSION['panier']['titre'][$i] . '</td>';
        $content .= '<td>' . $_SESSION['panier']['quantite'][$i] . '</td>';
        $content .= '<td>' . $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i] . '</td>';


        $content .= '<td>
                    
                    <a href="?action=retirer&id_produit= ' . $_SESSION['panier']['id_produit'][$i] . ' ">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                  <td>'; 

  
        $content .= "</tr>";
    }

    $content .= '<tr  > <td colspan="2">&nbsp;<strong>Montant Total  </strong>  </td><td>=</td><th>' . montant_total() . "</th></tr>";

    if (userConnect()) { //Si l'utilisateur est connecté 

        $content .= "<form method='post' action='admin/gestion_commande.php'>";
        $content .= "<tr>";       
        $content .= "<td colspan='2'>";

            $content .= '<input type="submit"  value="Payer" name="payer" class="btn btn-secondary">';
            
        $content .= "<td>";
 

        $content .= "<tr>";
        $content .= "</form>";
    } else {

        $content .= "<tr>";
        $content .= "<td colspan='3'>";

        $content .= 'Veuillez vous <a href="connexion.php" class="btn btn-secondary"> connecter </a> ou vous <a href="inscription.php" class="btn btn-secondary"> inscrire</a>';


        $content .= "</td>";
        $content .= "</tr>";
    }



    $content .= "<tr>";
    $content .= "<td>";

    $content .= '<a href="?action=vider" class="btn btn-secondary"> Vider mon panier </a>';


    $content .= "</td>";
    $content .= "</tr>";
}

$content  .= '</table >';

?>
<h1>Panier</h1>



<?php echo $content; ?>



<?php require_once "inc/footer.inc.php"; ?>pa