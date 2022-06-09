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
    
    function avis_commentaire($id) {
        global $link;
        $sql = "SELECT commentaire, aimer, prenom, nom, photo_pp FROM Avis NATURAL JOIN Utilisateur WHERE id_api='$id';";

        if (!($result = mysqli_query($link, $sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];

        while( $row = mysqli_fetch_row( $result ) ){
            array_push($T, [
                "commentaire" => $row[0],
                "aimer" => $row[1],
                "prenom" => $row[2],
                "nom" => $row[3],
                "photo" => $row[4]
            ]);
        }
        return $T;

    }
    
    function recuperer_liste_vu($id) {
        global $link;
        $sql = "SELECT id_api FROM Avis WHERE id_util = '$id';";

        if (!($result = mysqli_query($link, $sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];

        while( $row = mysqli_fetch_row( $result )) {
            array_push($T, $row[0]);
        }
        return $T;
    }

    function recuperer_liste_a_voir($id) {
        global $link;
        $sql = "SELECT id_api FROM Film_à_voir WHERE id_util = '$id';";

        if (!($result = mysqli_query($link, $sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];

        while( $row = mysqli_fetch_row( $result )) {
            array_push($T, $row[0]);
        }
        return $T;

    }

?>


