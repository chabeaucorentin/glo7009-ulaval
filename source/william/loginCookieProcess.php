<?php 
require("model.php");
if (isset($_POST["loginUser"]) && isset($_POST["loginPass"])) {
    $token = login($_POST["loginUser"], $_POST["loginPass"]);
    echo $token;
    if (isset($token)) {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;    
        if (isset($_POST["checkPersistent"]) && $_POST["checkPersistent"]) {
            setcookie('userIdentity', $token, time()+60*60*24*30, '/', $domain, false);
        } else {
            setcookie('userIdentity', $token, 0, '/', $domain, false);
        }
        header("Location: ./cookies.php");
    }
    else {
        header("Location: ./cookies.php?connectError=Le nom d'utilisateur ou le mot de passe est erroné. Veuillez réessayer.");
    }
}

?>