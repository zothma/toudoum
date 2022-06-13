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
        <a href="#" class="en-tete-image--profil">
            <img src="pictures/profil/<?php echo $_SESSION['userpp'] ?>.png" alt="Profil">
            <!-- <?php print_r($_SESSION); ?> -->
        </a>
        <?php endif; ?>
    </div>
</div>