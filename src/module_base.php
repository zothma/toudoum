<?php

    $link = mysqli_connect('localhost', 'alexie', '@ChiFouMi2022') or die ('Error
    connecting to mysqli: ' . mysqli_error($link).'\r\n');

    mysqli_select_db($link, 'base');
    
    function avis_aimer($id) {
        global $link;
        $sql = "SELECT SUM(aimer)/COUNT(*)*100 FROM Avis WHERE id_api = '$id';";

        if (!($result = mysqli_query($link, $sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }
        
        $row = mysqli_fetch_row($result);
        return (double) $row[0];
    }
    
    echo avis_aimer("f745");

?>


