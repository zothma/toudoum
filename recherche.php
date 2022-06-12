<?php
include("./src/carte_film.php");
include("./src/api.php");

$donnee = rechercher($_GET["query"]);
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
    <header class="en-tete-image en-tete-image__recherche" style="background-image: url('<?php echo reset($donnee)["fond"]; ?>');">
        <div class="en-tete-image--degrade"></div>
        <div class="en-tete-image--contenu">
            <div class="en-tete-image--navbar">TOUDOUM</div>
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
    <main class="main__recherche">
        <?php foreach ($donnee as $id => $film) {generer_carte($id, $film, 80);} ?>
    </main>
</body>

</html>