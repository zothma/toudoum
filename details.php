<?php
include('src/api.php');
include('src/carte_film.php');
include_once('src/module_base.php');
include ('src/carte_commentaire.php');

$donnee_est_film = str_starts_with($_GET['id'], 'f');
$javascript = "";
$avis_film = EtatFilm::Rien;

session_start();
if (isset($_SESSION["userid"])) {
    if (isset($_GET["aimer"])) {
        like_film($_GET["id"], $_SESSION["userid"], $_GET["aimer"] === '1');
    }
    $avis_film = recuperer_etat_film($_GET["id"], $_SESSION["userid"]);
}

function ellipser_texte(string $texte)
{
    $texte_complet = explode(' ', $texte);
    $texte_ellipse = array_slice($texte_complet, 0, 40);
    $resultat = implode(' ', $texte_ellipse);

    if (count($texte_complet) > count($texte_ellipse)) {
        $resultat .= '...';
    }

    return $resultat;
}


function ellipser_resume_saison(int $nb_saison, string $resume)
{
    global $javascript;
    $texte_complet = explode(' ', $resume);

    if (count($texte_complet) > 50) {
        $texte_ellipse = array_slice($texte_complet, 0, 50);
        $resultat = implode(' ', $texte_ellipse) . '...';

        $javascript .= "
            document.getElementById('lien_saison_$nb_saison').addEventListener('click', (e) => {
                const etat = e.currentTarget.getAttribute(\"data-toggle\");
                if (etat === 'plus') {
                    document.getElementById('resume_saison_$nb_saison').innerHTML = `$resume`;
                    e.currentTarget.innerHTML = 'Cacher...';
                    e.currentTarget.setAttribute('data-toggle', 'moins');
                } else {
                    document.getElementById('resume_saison_$nb_saison').innerHTML = `$resultat`;
                    e.currentTarget.innerHTML = 'Lire la suite...';
                    e.currentTarget.setAttribute('data-toggle', 'plus');
                }
            });
        ";

        return "<span id='resume_saison_$nb_saison'>$resultat</span>" .
            "<span id='lien_saison_$nb_saison' class='carte-saison--lien' data-toggle='plus'>Lire la suite...";
    } else {
        return "<span id='resume_saison_$nb_saison'>$resume</span>";
    }
}

if ($donnee_est_film) {
    $donnee = detail_film((int)substr($_GET['id'], 1));
} else {
    $donnee = detail_serie((int)substr($_GET['id'], 1));
}

$images_likes = [
    "pouce_haut" => ($avis_film === EtatFilm::Like) ? 'pictures/green_like.svg' : 'pictures/empty_like.svg',
    "pouce_bas" => ($avis_film === EtatFilm::Dislike) ? 'pictures/red_dislike.svg' : 'pictures/empty_dislike.svg'
];
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
            <?php include('src/header.php') ?>
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
                <a href="?aimer=1&id=<?php echo $_GET["id"] ?>">
                    <img src="<?php echo $images_likes["pouce_haut"] ?>" alt="J'aime" />
                </a>
                <a href="?aimer=0&id=<?php echo $_GET["id"] ?>">
                    <img src="<?php echo $images_likes["pouce_bas"] ?>" alt="Je n'aime pas" />
                </a>
                <img src="pictures/empty_watch_later.svg" alt="Regarder plus tard" />
                <a href="#commentaires">
                    <img src="pictures/empty_comment.svg" alt="Commenter" />
                </a>
            </div>
            <div class="icones_contenu--fournisseurs">
                <!-- Images des fournisseurs VOD fournis -->
                <?php foreach ($donnee["plateformes"] as $plateforme) : ?>
                    <img src="<?php echo $plateforme ?>" alt="Fournisseur VOD" />
                <?php endforeach ?>
            </div>
        </div>

        <p><?php echo $donnee["resume"] ?></p>
        <?php if (!$donnee_est_film) : ?>
            <p>
                Créateur : <?php echo implode(', ', $donnee["createur"]) ?>
            </p>
        <?php endif ?>
        <p>Production : <?php echo implode(', ', $donnee["production"]) ?></p>
        <p>Distribution : <?php echo implode(', ', $donnee["acteurs"]) ?></p>

        <?php if (!$donnee_est_film) : ?>
            <h2>Saisons</h2>
            <?php foreach ($donnee["saisons"] as $num_saison => $resume_saison) : ?>
                <div class="carte-saison" id="carte-saison-<?php echo $num_saison ?>">
                    <h3 class="carte-saison--titre">Saison <?php echo $num_saison ?></h3>
                    <p class="carte-saison--resume <?php if (strlen($resume_saison) == 0) echo "carte-saison--resume__indisponible" ?>">
                        <?php echo strlen($resume_saison) > 0 ? ellipser_resume_saison($num_saison, $resume_saison) : "Résumé indisponible" ?>
                    </p>
                </div>
            <?php endforeach ?>
        <?php endif ?>

        <?php if ($donnee_est_film && count($donnee["collection"]) > 0) : ?>
            <h2>Dans la même collection</h2>
            <?php foreach ($donnee["collection"] as $id_film => $film_collection) : ?>
                <a href="details.php?id=<?php echo $id_film ?>" class="carte-collection" style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('<?php echo $film_collection["fond"] ?>')">
                    <div class="carte-collection--contenu">
                        <h3 class="carte-collection--titre"><?php echo $film_collection["nom"] . ' - ' . $film_collection["annee_sortie"] ?></h3>
                        <p class="carte-collection--resume"><?php echo ellipser_texte($film_collection["resume"]) ?></p>
                    </div>
                    <img src="pictures/right_arrow_white.svg" alt="Accéder à la page" class="carte-collection--icone">
                </a>
            <?php endforeach ?>
        <?php endif ?>

        <?php if (count($donnee["recommendations"]) !== 0): ?>
            <h2>Dans le même genre</h2>
            <div class="recommendations">
                <?php array_walk($donnee["recommendations"], fn ($el, $id) => generer_carte($id, $el, 80)) ?>
            </div>
        <?php endif ?>

        <h2 id="commentaires">Commentaires (<?php echo compte_commentaire_film($_GET["id"]); ?>)</h2>
        <form class="zone-edition-commentaire" action="details.php" method="get">
            <!-- Le champs caché permettra de remettre l'id dans l'URL -->
            <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
            <textarea required class="commentaire commentaire__edition" name="commentaire" id="commentaire_personnel" placeholder="Laisser un commentaire..." cols="100" rows="2"></textarea>
            <div class="commentaire--boutons">
                <input type="radio" name="aimer" id="commentaire_like" value="1" hidden required />
                <label for="commentaire_like">
                    <img class="commentaire--bouton-image commentaire--bouton-image__desactive" src="pictures/empty_like.svg" alt="J'aime" />
                    <img class="commentaire--bouton-image commentaire--bouton-image__active" src="pictures/green_like.svg" alt="J'aime" />
                </label>

                <input type="radio" name="aimer" id="commentaire_dislike" value="0" hidden />
                <label for="commentaire_dislike">
                    <img class="commentaire--bouton-image commentaire--bouton-image__desactive" src="pictures/empty_dislike.svg" alt="J'aime" />
                    <img class="commentaire--bouton-image commentaire--bouton-image__active" src="pictures/red_dislike.svg" alt="J'aime" />
                </label>

                <button class="commentaire--bouton-envoyer" type="submit">Envoyer</button>
            </div>
        </form>
    </main>

    <?php      
        $array = avis_commentaire($_GET["id"]);
        foreach($array as $comm)
        {
            generer_comm($comm["prenom"], $comm["commentaire"], $comm["aimer"], $comm["photo"]);
        }
    ?>

    <?php include('src/footer.php') ?>

    <?php if (!$donnee_est_film) : ?>
        <script>
            <?php echo $javascript ?>
        </script>
    <?php endif ?>
</body>

</html>