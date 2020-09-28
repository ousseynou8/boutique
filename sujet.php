<?php
//EXERCICE :
/*
1 - validation du panier : (panier.php)

    -> si je clique sur "payer" => insert dans la table commande

                                    INDICE : pour l'id_commande => $pdostatement->last_insert_id() 

                                => insert details_commande (chaque produit !! boucle..)

                                => modification du stock ! (chaque produit !! boucle..)

                                => vider le panier

    (toutes les infos sont disponibles dans $_SESSION)

//-------------------------------------------------------------

2 - gestion_commande.php 
     
    -> Restreindre l'accès à cette page lorsque l'on n'est pas admin

    -> Affichage des commandes (sous forme de tableau): afficher id_commande, id_membre, montant, date, etat, pseudo, adresse, ville, cp (voir capture d'écran)
        -> L'id_commande doit etre cliquable (lien a) pour voir le detail de la commande

    -> Affichage suivi commande SI on a cliqué sur l'id_commande (sous forme de tableau)
*/

$pdostatement =execute_requete(" INSERT INTO commande(id_membre, montant, date) 
VALUES(". $_SESSION['membre']['id_membre'].", ". montant_total() .", NOW() ) ");
