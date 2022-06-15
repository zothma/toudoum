<?php
include_once '../src/module_base.php';
include_once '../src/carte_commentaire.php';

if (isset($_GET["supprimer"])) {
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Sus</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/polices.css">
    <link rel="stylesheet" href="/admin/admin.css">
</head>

<body>
    <main class="admin-center">
        <div class="admin-gauche">
            <h1>Dashboard</h1>
            <div class="admin-wrapper-info">
                <div class="carte-info">
                    <p class="chiffre">
                        <?php
                        echo compte_util();
                        ?>
                    </p>
                    <p>Utilisateurs inscrits</p>
                </div>
                <div class="carte-info">
                    <p class="chiffre">
                        <?php
                        echo compte_commentaire();
                        ?>
                    </p>
                    <p>Nombre de commentaires</p>
                </div>
            </div>
            </div>
        <div class="admin-droit">
            <h2>Commentaires</h2>
            <div id="commentaires">
                <?php
                    $array = avis_commentaire_all();
                    foreach($array as $comm)
                    {
                        generer_comm($comm["prenom"], $comm["commentaire"], $comm["aimer"], $comm["photo"], $comm["id"]);
                    }
                ?>
            </div>
        </div>
    </main>

    <script>
        let liste_avis = document.getElementById("commentaires");
        liste_avis.childNodes.forEach(el => {
            if (el.nodeType == Node.ELEMENT_NODE) {
                el.addEventListener("dblclick", commentaire => {
                    id = el.id;
                    window.location.href = "?supprimer=" + id;
                })
            }
        });
    </script>
</body>

</html>