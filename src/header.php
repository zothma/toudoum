<?php session_start(); ?>
<div class="en-tete-image--navbar">
    <h1> <a href="/">TOU DOUM</a></h1>
    <div class="bouton-connexion-inscription">
        <?php if (!array_key_exists("userid", $_SESSION)): ?>
        <div class="connexion">
            <a href="connexion.php">Connexion</a>
        </div>
        <div class="inscription">
            <a href="inscription.php">Inscription</a>
        </div>
        <?php else: ?>
        <p>Connecté</p>
        <?php endif; ?>
    </div>
</div>