<?php
include("./src/carte_film.php");
include("./src/api.php");

if (!array_key_exists("query", $_GET)) {
    header("location: /");
}

$donnee = rechercher($_GET["query"]);
$aucun_resultat = count($donnee) === 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche "<?php echo $_GET["query"] ?>" - TOUDOUM</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/polices.css">
</head>

<body>
    <?php if ($aucun_resultat): ?>
    <header class="en-tete-image en-tete-image__recherche en-tete-image__recherche_non_trouve">
    <?php else: ?>
    <header class="en-tete-image en-tete-image__recherche" style="background-image: url('<?php echo reset($donnee)["fond"]; ?>');">
        <div class="en-tete-image--degrade"></div>
    <?php endif; ?>
        <div class="en-tete-image--contenu">
            <?php include('src/header.php') ?>
            <form action="" method="GET">
                <h2>Résultats de la recherche</h2>
                <div class="en-tete-image--flex">
                    <div class="en-tete-image--input">
                        <img src="pictures/search-line.svg" alt="Icone Rechercher">
                        <input type="text" placeholder="Rechercher un film ou une série" value="<?php echo $_GET["query"] ?>" name="query" required>
                    </div>
                    <button type="submit">
                        <img src="pictures/arrow-right-line.svg" alt="Flèche Icone">
                    </button>
                </div>
            </form>
        </div>
    </header>
    <?php if($aucun_resultat): ?>
        <main class="main__recherche_non_trouve">
            <h3>Zut... Il semble que votre recherche n’ait pas abouti</h3>
            <img src="pictures/recherche_introuvable.svg" alt="Recherche introuvable">
        </main>
    <?php else: ?>
        <main class="main__recherche">
            <?php foreach ($donnee as $id => $film) {generer_carte($id, $film, 80);} ?>
            
        </main>
    <?php endif; ?>
</body>

</html>