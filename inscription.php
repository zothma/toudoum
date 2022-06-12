<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/polices.css" />
    <title>Inscription - TOUDOUM</title>
</head>
<body>
    <header class="nav-bar__noir">
        <h1>TOUDOUM</h1>
    </header>
    <main class="menu-connexion-inscription">
        <h2 class="menu-connexion-inscription--titre">Bienvenue sur TOUDOUM</h2>
        <form class="menu-connexion-inscription--formulaire" action="" method="POST">
            <h3>Créez votre compte</h3>
            <label for="inscription_email">E-mail</label>
            <input type="text" name="mail" id="inscription_email" placeholder="exemple@domaine.com" required>
            <label for="inscription_nom">Nom</label>
            <input type="text" name="mail" id="inscription_nom" placeholder="Nom" required>
            <label for="inscription_prenom">Prenom</label>
            <input type="text" name="mail" id="inscription_prenom" placeholder="Prenom" required>
            <label for="connexion_mdp">Mot de passe</label>
            <input type="password" name="mdp" id="connexion_mdp" placeholder="Mot de Passe" required>
            <label for="connexion_re_mdp">Confirmez le mot de passe</label>
            <input type="password" name="mdp" id="connexion_re_mdp" placeholder="Mot de Passe" required>

            <button type="submit" name="submit">S'inscrire</button>
        </form>

        <p class="menu-connexion-inscription--redirection">Déjà inscrit ? <a href="connexion.php">Connexion</a></p>
    </main>
</body>
</html>