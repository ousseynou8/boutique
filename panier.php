<?php require_once "inc/header.inc.php"; ?>
<?php
//debug( $_POST );
if( isset($_POST['ajout_panier']) ){ //Ici, on vérifie l'existence d'un "submit" dans la fiche_produit.php ('ajout_panier' provient de l'attribut 'name' de l'input submit)

    //debug( $_POST );

    $r = execute_requete(" SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]' ");
    //$_POST['id_produit'] provient de l'input type='hidden' dans fiche_produit.php

    $produit_panier = $r->fetch( PDO::FETCH_ASSOC );
        //debug( $produit_panier );

    ajout_panier( $produit_panier['titre'], $produit_panier['id_produit'], $_POST['quantite'], $produit_panier['prix']  );
    //$_POST['quantite'] provient de fiche_produit.php
}

//-------------------------------------------------------------
//Action de vider la panier :
if( isset($_GET['action']) && $_GET['action'] == 'vider' ){

    unset( $_SESSION['panier'] );
    //unset() : permet de détruire la variable $_SESSION['panier'] => qui reivent à vider votre panier
}

//debug( $_SESSION['panier'] );
//-------------------------------------------------------------
//Action de retirer un produit du panier :
if( isset( $_GET['action']) && $_GET['action'] == 'retirer' ){

    retirer_produit_panier( $_GET['id_produit'] );
}

//------------------------------------------------------------------
//------------------------------------------------------------------
//Validation du panier :
if( isset($_POST['payer']) ){ //Lorsque je clique sur 'payer' pour valider mon panier

	//Insertion de la commande :
	execute_requete(" INSERT INTO commande(id_membre, montant, date) 
					VALUES(". $_SESSION['membre']['id_membre'].", ". montant_total() .", NOW() ) ");

	$id_commande = $pdo->lastInsertId();

	$content .= "<div class='alert alert-success' role='alert'>Merci pour votre commande, votre numéro de commande est le $id_commande </div> ";

	//Insertion du détail de la commande
	for ($i=0; $i < count($_SESSION['panier']['id_produit']); $i++) { 
	
		execute_requete("INSERT INTO details_commande( id_commande, id_produit, quantite, prix ) 
			VALUES( 
				$id_commande, ".
				$_SESSION['panier']['id_produit'][$i] .", ".
				$_SESSION['panier']['quantite'][$i] .", ".
				$_SESSION['panier']['prix'][$i] ."
			) ");

		//Modification du stock en conséquence de la commande :
		execute_requete("UPDATE produit SET 

			stock = stock - ". $_SESSION['panier']['quantite'][$i] ."
			 WHERE id_produit = ".
			$_SESSION['panier']['id_produit'][$i] ."

		");
	}
	//Vider mon panier :
	unset( $_SESSION['panier']);
}

//-------------------------------------------------------------
//-------------------------------------------------------------
//affichage du panier :

$content .= '<table class="table" >';
    $content .= "<tr>
                    <th>Titre</th>    
                    <th>Quantite</th>    
                    <th>Prix</th> 
                    <th>Retirer</th>   
                </tr>";

    if( empty( $_SESSION['panier']['id_produit'] ) ){   //Si ma session/panier/id_produit est vide, c'est que je n'ai pas de produit dans mon panier

        $content .= "<tr> <td colspan='3'>Votre panier est vide</td> </tr>";
    }else{

        for( $i = 0; $i < count( $_SESSION['panier']['id_produit'] ); $i++ ){

            $content .= "<tr>";
                $content .= '<td>'. $_SESSION['panier']['titre'][$i] .'</td>';
                $content .= '<td>'. $_SESSION['panier']['quantite'][$i] .'</td>';
                $content .= '<td>'. $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i] .'</td>';

                $content .= '<td> 
                                <a href="?action=retirer&id_produit='. $_SESSION['panier']['id_produit'][$i] .' " >
                                    <i class="fas fa-trash-alt"></i>                                
                                </a>
                            </td>';

            $content .= "</tr>";
        }
        $content .= '<tr><td colspan="2">&nbsp;</td> <th> ' . montant_total() . " €</th> </tr>";

        if( userConnect() ){ //Si l'utilisateur est connecté

            $content .= "<form method='post'>";
                $content .= "<tr>";
                    $content .= "<td>";
                        $content .= '<input type="submit" value="Payer" name="payer" class="btn btn-secondary">';
                    $content .= "</td>";
                $content .= "</tr>";
            $content .= "</form>";
        }
        else{ //Sinon, c'est que je ne suis pas connecté

            $content .= "<tr>";
                $content .= "<td colspan='3'>";
                    $content .= 'Veuillez vous <a href="connexion.php"> connecter </a> ou vous <a href="inscription.php"> inscrire </a>.';
                $content .= "</td>";
            $content .= "</tr>";
        }
        
        $content .= '<tr>';
            $content .= '<td>';
                $content .= '<a href="?action=vider">Vider mon panier</a>';
            $content .= '</td>';
        $content .= '</tr>';

    }
$content .= '</table>';
?>

<h1>Panier</h1>

<?= $content; //affichage du contenu ?>

<?php require_once "inc/footer.inc.php"; ?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>