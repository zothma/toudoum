<?php 
    function generer_carte(array $film, float $pourcentage)
    {
        ?>
        <div class="carte_film">
            <img src="<?php echo $film["poster"]?>"alt="Affiche Top Gun">
            <h3><?php echo $film["nom"] ?></h3>
            <div class="carte_film--bloc">
                <p><?php echo $film["annee_sortie"]." - ".$film["genre"] ?></p>
                <div class="carte_film--bar-container">
                    <div class="carte_film--bar carte_film--bar__rouge"></div>
                    <div class="carte_film--bar carte_film--bar__vert" style="width:<?php echo $pourcentage ?>%"></div>
                </div>
                
                <div class="carte_film--likes">
                    <img src="./pictures/green_like.svg"alt="green like">
                    <p> <?php echo $pourcentage ?>% aiment</p>
                    <img src="./pictures/red_dislike.svg"alt ="red dislike">
                </div>             
            </div>
        </div>
        <?php
    }
    
?>