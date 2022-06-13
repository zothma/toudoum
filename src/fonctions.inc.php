<?php

function emptyInputLogin($username, $pwd)
{
    $result;
    if(empty($username) || empty($pwd))
    {
        $result = true;
    }
    else
    {
        $result = false;
    }
    
    return $result;
}

function loginUser($conn, $username, $pwd)
{
    $uidExists = uidExists($conn, $username, $pwd);

    if ($uidExists === false)
    {
        header("location: ../connexion.php?error=wronglogin");
    }

    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);
    
    if ($checkPwd === false)
    {
        header("location: ../connexion.php?error=wronglogin");
    }
    else if ($checkPwd === true)
    {
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        header("location: ../index.php");
        exit();
    }
}

?>