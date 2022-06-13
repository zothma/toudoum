<?php
    include('credentials.php');

    $link = mysqli_connect(SQL_URL, SQL_USER, SQL_PASS) or die ('Error
    connecting to mysqli: ' . mysqli_error($link).'\r\n');

    mysqli_select_db($link, SQL_BASE);
    
    function avis_aimer($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT SUM(aimer)/COUNT(*)*100 FROM Avis WHERE id_api = ?;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }
        
        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    

    function avis_commentaire($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT commentaire, aimer, prenom, nom, photo_pp FROM Avis NATURAL JOIN Utilisateur WHERE id_api= ?;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];
        $resultat = mysqli_stmt_get_result($sql);
        while( $row = mysqli_fetch_row($resultat) ){
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
        $sql = mysqli_prepare($link, "SELECT id_api FROM Avis WHERE id_util = ?;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];
        $result = mysqli_stmt_get_result($sql);

        while( $row = mysqli_fetch_row( $result )) {
            array_push($T, $row[0]);
        }
        return $T;
    }
    

    function recuperer_liste_a_voir($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT id_api FROM Film_à_voir WHERE id_util = ?;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];
        $result = mysqli_stmt_get_result($sql);
        while( $row = mysqli_fetch_row( $result )) {
            array_push($T, $row[0]);
        }
        return $T;

    }
    print_r(recuperer_liste_a_voir(1));
?>


