<?php 

if (isset($_POST["submit"]))
{
    $username = $_POST["utilisateur"];
    $pwd = $_POST["mdp"];

    require_once 'fonctions.inc.php';

    loginUser($username, $pwd);
}
else
{
    header("location: ../connexion.php");
}?>