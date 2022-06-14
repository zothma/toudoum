<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/polices.css" />
    <title>Connexion - TOUDOUM</title>
</head>
<body>
    <header class="nav-bar__noir">
        <h1><a href="/">TOU DOUM</a></h1>
    </header>
    <main class="menu-connexion-inscription">
        <h2 class="menu-connexion-inscription--titre">Bienvenue sur TOUDOUM</h2>
        <form class="menu-connexion-inscription--formulaire" action="./src/connexion.inc.php" method="POST">
            <label for="connexion_utilisateur">E-mail</label>
            <input type="email" name="utilisateur" id="connexion_utilisateur" placeholder="exemple@domaine.com" required>
            <label for="connexion_mdp">Mot de passe</label>
            <input type="password" name="mdp" id="connexion_mdp" placeholder="Mot de Passe" required>

            <?php if (isset($_GET["error"]))
            {
                if ($_GET["error"] == "wronglogin")
                {
                    echo "<p>L'email ou le mot de passe est erroné</p>";
                }
            }
            ?>

            <button type="submit" name="submit">Se connecter</button>
        </form>

        <p class="menu-connexion-inscription--redirection">Nouveau sur le site ? <a href="inscription.php">Inscrivez-vous</a> !</p>
    </main>
    <?php include('src/footer.php') ?>
</body>
</html>