<?php
function generer_carte(string $id, array $film)
{
?>
    <a href="details.php?id=<?php echo $id ?>" class="carte_film">
        <div>
            <img src="<?php echo $film["poster"] ?>" alt="Affiche">
            <h3><?php echo $film["nom"] ?></h3>
            <div class="carte_film--bloc">
                <p><?php echo $film["annee_sortie"] . " - " . ($film["genre"] ?? $film["genres"][0]) ?></p>
                
                <div class="carte_film--stats">
                    <div class="carte_film--bar-container">
                        <div class="carte_film--bar carte_film--bar__rouge"></div>
                        <div class="carte_film--bar carte_film--bar__vert" style="width:<?php echo $film["popularite"] ?>%"></div>
                    </div>
    
                    <div class="carte_film--likes">
                        <img src="./pictures/green_like.svg" alt="green like">
                        <p><?php echo $film["popularite"] ?>% aiment</p>
                        <img src="./pictures/red_dislike.svg" alt="red dislike">
                    </div>
                </div>
                
            </div>
        </div>
    </a>
<?php
}

?>