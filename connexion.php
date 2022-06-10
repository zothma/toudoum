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
        <h1>TOUDOUM</h1>
    </header>
    <main class="menu-connexion-inscription">
        <h2 class="menu-connexion-inscription--titre">Bienvenue sur TOUDOUM</h2>
        <form class="menu-connexion-inscription--formulaire" action="" method="POST">
            <label for="connexion_utilisateur">Identifiant</label>
            <input type="text" name="utilisateur" id="connexion_utilisateur" placeholder="Nom" required>
            <label for="connexion_mdp">Mot de passe</label>
            <input type="password" name="mdp" id="connexion_mdp" placeholder="Mot de Passe" required>

            <button type="submit" name="submit">Se connecter</button>
        </form>

        <p class="menu-connexion-inscription--redirection">Nouveau sur le site ? <a href="inscription.php">Inscrivez-vous</a> !</p>
    </main>
</body>
</html>