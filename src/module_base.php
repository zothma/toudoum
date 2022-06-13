<?php
    include('credentials.php');

    $link = mysqli_connect(SQL_URL, SQL_USER, SQL_PASS) or die ('Error
    connecting to mysqli: ' . mysqli_error($link).'\r\n');

    mysqli_select_db($link, SQL_BASE);
    
    enum EtatFilm {
        case Like;
        case Dislike;
        case Rien;
    }

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

    function recuperer_etat_film(string $id_film, int $id_utilisateur): EtatFilm | bool {
        global $link;
        $sql = mysqli_prepare($link, "SELECT aimer FROM Avis WHERE id_util = ? AND id_api = ?;");
        mysqli_stmt_bind_param($sql, "is", $id_utilisateur, $id_film);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR : " . mysqli_error($link);
            return false;
        }

        $result = mysqli_stmt_get_result($sql);
        $ligne = mysqli_fetch_row($result);

        mysqli_stmt_close($sql);
        if (is_null($ligne)) {
            return EtatFilm::Rien;
        } elseif ($ligne[0] == true) {
            return EtatFilm::Like;
        } else {
            return EtatFilm::Dislike;
        }
    }
?>
