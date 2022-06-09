<?php
include('src/api.php');
include('src/carte_film.php');

if (str_starts_with($_GET['id'], 'f')) {
    $donnee = detail_film((int)substr($_GET['id'], 1));
} else {
    $donnee = detail_serie((int)substr($_GET['id'], 1));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/polices.css">
    <title><?php echo $donnee["nom"] . " - TOUDOUM" ?></title>
</head>
<body>
    <header class="en-tete-image" style="background-image: url('<?php echo $donnee['fond']; ?>');">
        <div class="en-tete-image--degrade"></div>
        <div class="en-tete-image--contenu">
            <div class="en-tete-image--navbar">TOUDOUM</div>
            <div>
                <h2 class="en-tete-image--titre"><?php echo $donnee["nom"] ?></h2>
                <div class="en-tete-image--infos">
                    <div>
                        <div>Recommandé à 80%</div>
                        <div><?php echo $donnee["annee_sortie"] . ' - ' . $donnee["origine"] ?></div>
                        <div><?php echo implode(', ', $donnee["genres"]) ?></div>
                    </div>
                    <?php if (str_starts_with($_GET['id'], 's')): ?>
                    <div>
                        <div><?php echo $donnee["nb_saisons"] . " Saison" . ($donnee["nb_saisons"] > 1 ? 's' : '') ?></div>
                        <div><?php echo $donnee["nb_episodes"] . " Épisodes" ?></div>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>
</body>
</html>