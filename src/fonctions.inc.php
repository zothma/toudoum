<?php

include ('module_base.php');

function loginUser($email, $pwd)
{
    $pwd_crypt = password_hash($pwd, PASSWORD_DEFAULT);
    $correct = user_exists($email, $pwd);

    if (!$correct)
    {
        header("location: ../connexion.php?error=wronglogin");
    } else {
        $infos_user = recup_info_user($email);

        session_start();
        $_SESSION["userid"] = $infos_user["id"];
        $_SESSION["userpp"] = $infos_user["photo"];
        header("location: ../index.php");
        exit();
    }
}

?>