<?php 
    require_once "src/module_base.php";

    function afficher_nombre_utilisateur(){
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(id_util) from Utilisateur");

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }
        
        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    print_r("Nombre d'utilisateurs :\n");
    print_r(afficher_nombre_utilisateur());

    function afficher_nombre_commentaire(){
        global $link;
        $sql = mysqli_prepare($link, "SELECT COUNT(id_avis) from Avis");

        if (!(mysqli_stmt_execute($sql))) {
            echo "ERREUR " . mysqli_error($link);
            return false; # on retroune false pour dire que ça ne marche pas
        }
        
        $row = mysqli_fetch_row(mysqli_stmt_get_result($sql));
        return (double) $row[0];
    }
    print_r("Nombre de commentaires :\n");
    print_r(afficher_nombre_commentaire());

    function obtenir_commentaire($numero_utilisateur){
        global $link;
        $sql = mysqli_prepare($link, "SELECT nom from Utilisateur");
    }
?>