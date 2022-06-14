<?php

require_once 'module_base.php';

function loginUser($email, $pwd)
{
    $correct = user_exists($email, $pwd);

    if (!$correct)
    {
        header("location: ../connexion.php?error=wronglogin");
    } else {
        $infos_user = recup_info_user($email);

        if ($infos_user["valide"]) {
            session_start();
            $_SESSION["userid"] = $infos_user["id"];
            $_SESSION["userpp"] = $infos_user["photo"];
            header("location: ../index.php");
        } else {
            header("location: ../connexion.php?error=invalid");
        }
    }
    exit();
}

# Partie Inscription
function pwdMatch($pwd, $pwdRepeat)
{
    if ($pwd !== $pwdRepeat)
    {
        $result = false;
    }
    else
    {
        $result = true;
    }
    return $result;
}

?>