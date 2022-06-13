<?php

require_once 'module_base.php';

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

function createUser($link, $nom, $prenom, $email, $pwd)
{
    $sql = "INSERT INTO Utilisateur(nom, prenom, email, mdp) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($link);
    if(!mysqli_stmt_prepare($stmt, $sql))
    {
        header("location: ../inscription.php?error=stmtfailed");
        exit();
    }
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $nom, $prenom, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../inscription.php?error=none");
    exit();
}

?>