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
    
    function compte_film_aimes_pas($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(id_api) FROM Avis WHERE id_util = ? AND aimer = false;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function compte_film_aimes($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(id_api) FROM Avis WHERE id_util = ? AND aimer = true;");
        mysqli_stmt_bind_param($sql, "s", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function amis_valide($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT DISTINCT u.nom, u.prenom, u.photo_pp FROM Amis a INNER JOIN Utilisateur u ON a.id_util2 = u.id_util WHERE id_util1 = ? AND valider = True;");
        mysqli_stmt_bind_param($sql, "i", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];
        $resultat = mysqli_stmt_get_result($sql);
        while( $row = mysqli_fetch_row($resultat) ){
            array_push($T, [
                "nom" => $row[0],
                "prenom" => $row[1],
                "photo" => $row[2]
            ]);
        }
        return $T;
    }
    
    function amis_pas_valide($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT DISTINCT u.nom, u.prenom, u.photo_pp FROM Amis a INNER JOIN Utilisateur u ON a.id_util2 = u.id_util WHERE id_util1 = ? AND valider = False;");
        mysqli_stmt_bind_param($sql, "i", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $T = [];
        $resultat = mysqli_stmt_get_result($sql);
        while( $row = mysqli_fetch_row($resultat) ){
            array_push($T, [
                "nom" => $row[0],
                "prenom" => $row[1],
                "photo" => $row[2]
            ]);
        }
        return $T;
    }
    
    function compte_film_a_voir($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(id_api) FROM Film_à_voir WHERE id_util = ?;");
        mysqli_stmt_bind_param($sql, "i", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function avis_laisses($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(commentaire) FROM Avis WHERE id_util = ?;");
        mysqli_stmt_bind_param($sql, "i", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function compte_amis_valide($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT DISTINCT COUNT(*) FROM Amis a INNER JOIN Utilisateur u ON a.id_util2 = u.id_util WHERE id_util1 = '1' AND valider = True;");
        mysqli_stmt_bind_param($sql, "i", $id);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }

    function compte_util() {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(*) FROM Utilisateur;");

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function compte_commentaire() {
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(commentaire) FROM Avis;");

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }

        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    
    function affiche_commentaire($id) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT commentaire, nom, prenom, photo_pp, aimer FROM Avis NATURAL JOIN Utilisateur WHERE commentaire is not null AND id_api = ?;");
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
                "nom" => $row[1],
                "prenom" => $row[2],
                "photo_pp" => $row[3],
                "aimer" => $row[4]
            ]);
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

    function user_exists($email, $pass): bool {
        global $link;
        $sql = mysqli_prepare($link, "SELECT mdp FROM Utilisateur WHERE email = ?");
        mysqli_stmt_bind_param($sql, 's', $email);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR : " . mysqli_error($link);
            return false;
        }

        $result = mysqli_stmt_get_result($sql);
        $ligne = mysqli_fetch_row($result);

        return !is_null($ligne) && password_verify($pass, $ligne[0]);
    }

    function recup_info_user($email) {
        global $link;
        $sql = mysqli_prepare($link, "SELECT id_util, photo_pp FROM Utilisateur WHERE email = ?;");
        mysqli_stmt_bind_param($sql, 's', $email);

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR : " . mysqli_error($link);
            return false;
        }

        $result = mysqli_stmt_get_result($sql);
        $ligne = mysqli_fetch_row($result);

        return [
            "id" => $ligne[0],
            "photo" => $ligne[1]
        ];
    }

    function create_user($nom, $prenom, $email, $pwd) : bool {
        global $link;

        $sql = "INSERT INTO Utilisateur(nom, prenom, email, mdp) VALUES (?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($link);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            return false;
        }
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ssss", $nom, $prenom, $email, $hashedPwd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
}
