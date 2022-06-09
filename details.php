<?php
include('src/api.php');
include('src/carte_film.php');

$donnee_est_film = str_starts_with($_GET['id'], 'f');

if ($donnee_est_film) {
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
                    <?php if (!$donnee_est_film) : ?>
                        <div>
                            <div><?php echo $donnee["nb_saisons"] . " Saison" . ($donnee["nb_saisons"] > 1 ? 's' : '') ?></div>
                            <div><?php echo $donnee["nb_episodes"] . " Épisodes" ?></div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="icones_contenu">
            <div class="icones_contenu--actions">
                <img src="pictures/empty_like.svg" alt="J'aime" />
                <img src="pictures/empty_dislike.svg" alt="Je n'aime pas" />
                <img src="pictures/empty_watch_later.svg" alt="Regarder plus tard" />
                <img src="pictures/empty_comment.svg" alt="Commenter" />
            </div>
            <div class="icones_contenu--fournisseurs">
                <!-- Images des fournisseurs VOD fournis -->
                <?php foreach($donnee["plateformes"] as $plateforme): ?>
                    <img src="<?php echo $plateforme ?>" alt="Fournisseur VOD" />
                <?php endforeach ?>
            </div>
        </div>

        <p><?php echo $donnee["resume"] ?></p>
        <?php if (!$donnee_est_film): ?>
            <p>
                Créateur : <?php echo implode(', ', $donnee["createur"]) ?>
            </p>
        <?php endif ?>
        <p>Production : <?php echo implode(', ', $donnee["production"]) ?></p>
        <p>Distribution : <?php echo implode(', ', $donnee["acteurs"]) ?></p>

        <?php if (!$donnee_est_film): ?>
            <h2>Saisons</h2>
            <?php foreach($donnee["saisons"] as $num_saison => $resume_saison): ?>
                <div class="carte-saison">
                    <h3 class="carte-saison--titre">Saison <?php echo $num_saison ?></h3>
                    <p class="carte-saison--resume <?php if (strlen($resume_saison) == 0) echo "carte-saison--resume__indisponible" ?>">
                        <?php echo strlen($resume_saison) > 0 ? $resume_saison : "Résumé indisponible" ?>
                    </p>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </main>
</body>

</html>