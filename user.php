<?php 
function carte_user($nom, $prenom, $photo)
{
?>
    <img src="./pictures/profil/<?php echo $photo ?>.png" alt="photo de profil">
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
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css" class="css">
    <link rel="stylesheet" href="css/header.css" class="css">
    <link rel="stylesheet" href="css/polices.css" class="css">
    <link rel="stylesheet" href="css/temp.css" class="css">
</head>
<body>
        <header class="en-tete-image--contenu">
            <div class="nav-bar__noir">
                <?php require_once 'src/header.php' ; 
                    session_start();
                    if (!isset($_SESSION["userid"]))
                    {
                        header("location: ./connexion.php");
                        exit();
                    }
                    require_once 'src/module_base.php';
                ?>
            </div>
        </header>
        <main class="user--card">
            <div class="user--card__profil">
                <?php 
                echo carte_user("De la Flamme", "Marc", $_SESSION["userid"]); ?>
            </div>
            <div class="user--card__nb">
                <div>
                    <?php echo compte_film_aimes($_SESSION["userid"]) ; ?>
                    <p>Films aimés</p>
                </div>
            </div>  
            
        </main>
    <h1 style= "font-size: 90px"></h1>
    <?php include('src/footer.php') ?>
</body>
</html>