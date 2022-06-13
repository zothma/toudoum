<?php
    if(isset($_POST["submit"]))
    {
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $email = $_POST["mail"];
        $pwd = $_POST["mdp"];
        $pwdRepeat = $_POST["mdpRepeat"];


     require_once 'module_base.php';
     require_once 'fonctions.inc.php';

    if(!pwdMatch($pwd, $pwdRepeat))
    {
        header("location: ../inscription.php?error=passwordsdontmatch");
        exit();
    }

    if (user_exists($email, $pwd))
    {
        header("location: ../inscription.php?error=usernametaken");
        exit();
    }

    createUser($link, $nom, $prenom, $email, $pwd);
    }
    else
    {
        header("location: ../inscription.php");
     }
?>