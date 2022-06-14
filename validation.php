<?php
if (!isset($_GET["id"])) {
    header("location: /");
    exit();
}

require_once('src/module_base.php');

$id_utilisateur = intval(explode('_', $_GET["id"])[0]);
$id_lien = implode('_', array_slice(explode('_', $_GET["id"]), 1));

if (valider_user($id_utilisateur, $id_lien)) {
    header("location: /connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validation du compte - TOUDOM</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/polices.css">
</head>
<body>
    <header class="en-tete-image en-tete-image__noir">
        <div class="en-tete-image--contenu">
            <?php include('src/header.php') ?>
        </div>
    </header>
    <main>
        <h2>Lien introuvable</h2>
        <p>Le lien de validation est invalide ou a déjà été validé, merci de le vérifier.</p>
    </main>
</body>
</html>