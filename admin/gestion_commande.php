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
<?php require_once('../inc/header.inc.php'); ?>
<?php
//Restreindre l'accès à cette page lorsque l'on n'est pas admin
if( !adminConnect() ){

	header('location:../index.php');
	exit();
}
//-------------------------------------------------------------------------
//Affichage des commandes (sous forme de tableau): afficher id_commande, id_membre, montant, date, etat, pseudo, adresse, ville, cp
	//L'id_commande doit etre cliquable (lien a) pour voir le detail de la commande
$content .= '<h1>Listing des commandes</h1>';

$info_commande = execute_requete(" 

	SELECT c.*, m.pseudo, m.adresse, m.ville, m.cp 
	FROM membre as m, commande as c
	WHERE m.id_membre = c.id_membre
");

	$content .= "Nombre de commande(s) : " . $info_commande->rowCount();

$content .= "<table border='1' cellpadding='5'>";
	$content .= '<tr>';
		for ($i=0; $i < $info_commande->columnCount() ; $i++) { 
			
			$colonne = $info_commande->getColumnMeta($i);

			$content .= "<th>$colonne[name]</th>";
		}
	$content .= '</tr>';

	$chiffre_affaire = 0;

	while( $ligne = $info_commande->fetch(PDO::FETCH_ASSOC) ){
		//debug($ligne);

		$chiffre_affaire += $ligne['montant'];

		$content .= '<tr>';
			foreach ($ligne as $key => $value) {
				
				if( $key == 'id_commande' ){

					$content .= '<td>
									<a href="?suivi='. $ligne['id_commande'] .'"> Voir la commande '. $ligne['id_commande'] .'</a>
								</td>';
				}
				else{
					$content .= "<td>$value</td>";
				}
			}
		$content .= '</tr>';
	}
$content .= "</table>";

$content .= "<p>CA de la boutique : $chiffre_affaire € </p>";

//-------------------------------------------------------------------------
//Affichage suivi commande SI on a cliqué sur l'id_commande (sous forme de tableau)
if( isset($_GET['suivi']) ){

	$content .= '<h1>Voici le détails de la commande '. $_GET['suivi'] .'</h1>';

	$info_de_ma_commande = execute_requete(" SELECT * FROM details_commande WHERE id_commande=$_GET[suivi] ");

	$content .= '<table border="3" cellpadding="5" style="border-color:red;">';
		$content .= '<tr>';
			for ($i=0; $i < $info_de_ma_commande->columnCount(); $i++) { 
				
				$colonne = $info_de_ma_commande->getColumnMeta($i);

				$content .= "<th>$colonne[name]</th>";
			}
		$content .= '</tr>';
		while ($ligne = $info_de_ma_commande->fetch(PDO::FETCH_ASSOC) ) {
			
			$content .= '<tr>';
				foreach ($ligne as $key => $value) {
					
					$content .= "<td>$value</td>";
				}
			$content .= '</tr>';
		}
	$content .= '</table>';
}

?>

<?= $content ?>

<?php require_once('../inc/footer.inc.php'); ?>