<?php
function generer_comm($nom, $comm, $aime, $photo)
{
?>
    <div class="carte_commentaire">
    <div class="comm">
        <div class="comm--profil">
            <?php echo '<img src="./pictures/profil/'. $photo . '.png" alt="photo de profil">' ; ?>
            <h4><?php echo $nom ; ?></h4>
        </div>
        <div class="comm--libelle">
            <?php
                if($aime)
                {
                    echo '<img src="./pictures/green_like.svg" alt="green like">';
                }
                else
                {
                    echo '<img src="./pictures/red_dislike.svg" alt="red like">';
                }
            ?>
            <p><?php echo $comm ;?></p>
        </div>       
    </div>
    </div>
<?php
}
?>