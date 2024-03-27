<?php
require("model.php");
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["connect"])) {
        $token = login($_POST["email"], $_POST["password"]);
       setcookie("userToken",$token, time()+(60*60*24));
       header('Location:./cookies.php?view=demonstration');
       exit();
    }
else {
    logoutProcess();
}
?>