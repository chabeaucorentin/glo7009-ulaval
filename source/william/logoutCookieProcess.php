<?php
    require("model.php");

    if (isset($_COOKIE["userToken"])):
        logout($_COOKIE["userToken"]);
    endif;
    unset($_POST);
    header("Location: ./cookies.php");
?>