<?php require_once "init.inc.php"; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <!-- CSS BOOTSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- CDN FONT AWESOME-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>boutique.php">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>panier.php">Panier</a>
                </li>

                <?php if( userConnect() ) : ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= URL ?>profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>connexion.php?action=deconnexion">Deconnexion</a>
                </li>

                <?php else : ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= URL ?>inscription.php">Inscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= URL ?>connexion.php">Connexion</a>
                </li>

                <?php endif; ?>

                <?php if( adminConnect() ) : ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        BackOffice
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion boutique</a>
                        <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membre</a>
                        <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commande</a>
                    </div>
                </li>

                <?php endif; ?>

            </ul>
        </div>
    </nav>
    <div class="container">