<?php
include("./src/carte_film.php");
include("./src/api.php");

$donnee_nv_movies = nouveautes_films();
$donnee_nv_series = nouveautes_series();
$donnee_popu_movies = populaires_films();
$donnee_popu_series = populaires_series();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOUDOUM</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/polices.css">
</head>
<body>
    <header class="en-tete-image en-tete-image__recherche" style="background-image: url('<?php echo reset($donnee_nv_movies)["fond"]; ?>');">
        <div class="en-tete-image--degrade"></div>
        <div class="en-tete-image--contenu">
            <?php include('src/header.php') ?>
            <form action="recherche.php" method="GET">
                <div class="en-tete-image--flex">
                    <div class="en-tete-image--input">
                        <img src="pictures/search-line.svg" alt="Icone Rechercher">
                        <input type="text" placeholder="Rechercher un film ou une série" name="query" required>
                    </div>
                    <button type="submit">
                        <img src="pictures/arrow-right-line.svg" alt="Flèche Icone">
                    </button>
                </div>
            </form>
        </div>
    </header>

    <main  class="main__recherche">
        <h2>Films en vogue</h2>
        <div class = "recommendations">   
            <?php foreach($donnee_nv_movies as $id => $film) {generer_carte($id, $film, 80);}  ?>
        </div>
        <h2>Séries en vogue</h2>
        <div class="recommendations">
            <?php foreach($donnee_nv_series as $id => $film) {generer_carte($id, $film, 80);} ?>
        </div>
        <h2>Films les plus populaires</h2>
        <div class="recommendations">
            <?php foreach($donnee_popu_movies as $id => $film) {generer_carte($id, $film, 80);} ?>
        </div>
    </main>
    <?php include('src/footer.php') ?>
</body>

</html>