<!DOCTYPE html>
<?php
/*****************************************************
 *                       MODEL                       *
 *****************************************************/
require("model.php");

/*****************************************************
 *                      COOKIES                      *
 *****************************************************/
// CODE

function loginProcess() {
    if (isset($_POST["loginUser"]) && isset($_POST["loginPass"])) {
        $token = login($_POST["loginUser"], $_POST["loginPass"]);
        echo $token;
        if (isset($token)) {
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;    
            if (isset($_POST["checkPersistent"]) && $_POST["checkPersistent"]) {
                setcookie('userToken', $token, time()+60*60*24*30, '/', $domain, false);
            } else {
                setcookie('userToken', $token, 0, '/', $domain, false);
            }
            header("Location: ./cookies.php");
        }
        else {
            header("Location: ./cookies.php?connectError=Le nom d'utilisateur ou le mot de passe est erroné. Veuillez réessayer.");
        }
    }
}

function logoutProcess() {
    if (isset($_COOKIE["userToken"])):
        logout($_COOKIE["userToken"]);
        unset($_POST);
        echo 'window.location.href="./cookies.php"';
    endif;
}

?>
    <?php if (isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])): ?>
        <form method="post">
            <button type="submit" name="disconnect" value="disconnect" onclick=<?php logoutProcess(); ?>> Se déconnecter </button>
        </form>
        <br>
        <div><span>L'usager est connecté.</span></div>
    <?php else: ?>
        <?php setcookie("userToken", '',time()-1000, '/'); ?>
        <form method="post">
        <label for='loginUser'> Usager: </label>
        <input type='text' name='loginUser' id='loginUser' />
        <br />
        <label for='loginPass'> Mot de passe: </label>
        <input type='password' name='loginPass' id='loginPass' />
        <br />
        <label for="checkPersistent"> Se souvenir de moi </label>
        <input type="checkbox" name="checkPersistent" id="checkPersistent" />
        <br />
        <button type='submit' name="connect" value="connect" onclick=<?php loginProcess(); ?>> Se connecter </button>
        </form>
        <?php if(isset($_GET["connectError"])): ?>
            <br />
            <div><span><?php echo $_GET["connectError"]; ?></span></div>
            <?php endif; ?>
    <?php endif;?>