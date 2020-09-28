<?php
//Fonction debug() : permet d'effectuer print_r "amélioré"
function debug( $arg ){

    echo '<div style="background:#fda500; padding: 5px; z-index:1000;">';

    $trace = debug_backtrace(); //Fonction prédéfinie de php qui retourne un array contenant des informations

    echo 'Debug demandé dans le fichier ' . $trace[0]['file'] . ' a la ligne ' . $trace[0]['line'] ;

        print '<pre>';
            print_r( $arg );
        print '</pre>';

    echo '</div>';
}

//------------------------------------------------------
//Fonction execute_requete() : permet d'effectuer une requête
function execute_requete( $req ){

    global $pdo; //Je rappatrie la variable $pdo de l'espace global dans le scope de la fonction

    $pdostatement = $pdo->query( $req );

    return $pdostatement;
}

//------------------------------------------------------
//Fonction userConnect() : si l'internaute est connecté
function userConnect(){

    //Si la session membre n'existe pas, cela signifie que l'on est pas connecté, on retourne 'false'
    if( !isset( $_SESSION['membre'] ) ){

        return false;
    }
    else{ //Sinon, c'est que l'on est connecté, on retourne 'true'

        return true;
    }
}

//------------------------------------------------------
//Fonction adminConnect() : si l'internaute est connecté ET qu'il est administrateur
function adminConnect(){

    //Si l'internaute est connecté ET qu'il est administrateur donc que son statut est égale à 1 (en bdd)
    if( userConnect() && $_SESSION['membre']['statut'] == 1 ){

        return true;
    }
    else{

        return false;
    }
}

//------------------------------------------------------
//Fonction creation_panier() : 
function creation_panier(){

    if( !isset( $_SESSION['panier']) ){ //Si la session/panier N'EXISTE PAS et bien je la crée

        $_SESSION['panier'] = array();

        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

//------------------------------------------------------
//Fonction ajout_panier() :
function ajout_panier( $titre, $id_produit, $quantite, $prix ){

    creation_panier();
    //Soit le panier n'existe pas et donc on le crée
    //Soit le panier existe et on l'utilise

    $position_produit = array_search( $id_produit, $_SESSION['panier']['id_produit'] );
    //array_search( arg1, arg2 ) : la fonction retournera l'indice où se trouve l'élément recherché (ou false)
        //arg1 : ce que l'on cherche
        //arg2 : dans quel tableau on effectue la recherche
    
    if( $position_produit !== false ){ //Si le produit est déjà présent dans le panier

        $_SESSION['panier']['quantite'][$position_produit] += $quantite;
        //Ici, on va précisément à l'indice du produit concerné et on y ajoute la nouvelle quantité
    }
    else{ //Sinon, si le produit n'existe pas dans le panier, on ajoute les informations sélectionnées (en paramètre de la fonction). Les crochets vides permettent de passer à l'indice suivant.

        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
    }
}

//------------------------------------------------------
//Fonction montant_total() :
function montant_total(){

    $total = 0;

    for( $i = 0; $i < count( $_SESSION['panier']['id_produit'] ); $i++ ){

        $total += $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i];
    }
    return $total;
}

//------------------------------------------------------
//Fonction retirer_produit_panier()
function retirer_produit_panier( $id_produit_a_retirer ){

    $position_produit = array_search( $id_produit_a_retirer, $_SESSION['panier']['id_produit'] );

    if( $position_produit !== false ){ //Si le produit est dans la panier

        array_splice( $_SESSION['panier']['titre'], $position_produit, 1 );
        array_splice( $_SESSION['panier']['id_produit'], $position_produit, 1 );
        array_splice( $_SESSION['panier']['quantite'], $position_produit, 1 );
        array_splice( $_SESSION['panier']['prix'], $position_produit, 1 );

        // array_splice( arg1, arg2, arg3 ) : permet de supprimer un/des élément(s) d'un tableau
            //arg1 : représente le tableau dans lequel on recherche
            //arg2 : l'élément que l'on souhaite supprimer
            //arg3 : le nombre à supprimer
    }
}
