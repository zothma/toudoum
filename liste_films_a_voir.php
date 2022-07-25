<?php
include("./src/carte_film.php");
include("./src/api.php");
include("./src/module_base.php");
session_start();
$donnee = recuperer_liste_a_voir($_SESSION["userid"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Films à visionner</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/polices.css">
</head>
<body>
    <header>
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
        <h2>Les films & séries à voir</h2>
    </header>
    <main class="main__recherche">
        <?php foreach ($donnee as $id) {
            $nv_id = intval(substr($id,1));
            if($id[0] == 'f') {
                $film = detail_film($nv_id);
                generer_carte($id, $film);
            }
            else {
                $serie = detail_serie($nv_id);
                generer_carte($id, $serie);
            }
        }
            ?>     
    </main>
    <?php include('src/footer.php') ?>
</body>
</html>