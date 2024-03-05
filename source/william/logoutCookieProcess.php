<?php
    require("model.php");

    if (isset($_COOKIE["userIdentity"])):
        logout($_COOKIE["userIdentity"]);
    endif;
    unset($_POST);
    header("Location: ./cookies.php");
?>