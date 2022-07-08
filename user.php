<?php 
function carte_user($nom, $prenom, $photo)
{
?>
    <img src="./pictures/profil/<?php echo $photo ?>.png" alt="photo de profil" class="user--card__profil_pp">
    <h2><?php echo $prenom . "\n" . $nom ; ?></h2>
<?php
} 
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="css/style.css" class="css">
    <link rel="stylesheet" href="css/header.css" class="css">
    <link rel="stylesheet" href="css/polices.css" class="css">
    <link rel="stylesheet" href="css/temp.css" class="css">
</head>
<body>
        <div class="nav-bar__noir2">
            <?php include('src/header.php');
                if (!isset($_SESSION["userid"]))
                {
                    header("location: ./connexion.php");
                    exit();
                }
                require_once 'src/module_base.php';
            ?>
        </div>

        <main class="user--card">
            <div class="user--card__profil">
                <?php $array = recup_toutes_info_user($_SESSION["userid"]); ?>
               <?php echo carte_user($array["nom"], $array["prenom"], $array["photo"]); ?>
               <a href="./src/deconnexion.inc.php" class="logout-button" title="Se déconnecter">
                    <img src="./pictures/logout_button.svg" alt="bouton logout">
            </a>
            </div>
            <div class="user--card__nb">
                <div>
                    <h3><?php echo compte_film_aimes($_SESSION["userid"]) ; ?></h3>
                    <p>Films aimés</p>
                </div>
                <div>
                    <h3><?php echo  nb_commentaires_par_user($_SESSION["userid"]) ; ?></h3>
                    <p>Nombre de commentaires</p>
                </div>
                <div>
                    <h3><?php echo  compte_film_aimes_pas($_SESSION["userid"]) ; ?></h3>
                    <p>Films non aimés</p>
                </div>
                <div>
                    <h3><?php echo  nb_amis($_SESSION["userid"]) ; ?></h3>
                    <p>Nombre d'amis</p>
                </div>
            </div>
            <div class="user--card__amis">
                <h2> Mes amis</h2>
                <div>
                    <?php $tab_amis = amis_utilisateur($_SESSION["userid"]);
                    foreach($tab_amis as $ami) { carte_user($ami["nom"], $ami["prenom"], $ami["photo"]); }?>
                </div>
            </div>
            <div class="user--card__lists">
                <button  onclick="window.location.href = 'liste_films_a_voir.php'">
                    <div class="user--card__left_button">
                        <img src ="pictures/clock_button.svg" alt="logo films à voir">
                        <p>Films & séries à voir </p>
                    </div>
                    <img src ="pictures/arrow.svg" alt="logo flèche">
                </button>
                <button onclick="window.location.href = 'liste_films_visionnées.php'">
                    <div class="user--card__left_button">
                        <img src="pictures/eye_button.svg" alt="logo films visionnés">
                        <p>Films & séries visionnées </p>
                    </div>
                    <img src ="pictures/arrow.svg" alt="logo flèche">
                </button>
            </div>  
        </main>
    <h1 style= "font-size: 90px"></h1>
    <?php include('src/footer.php') ?>
</body> 
</html>