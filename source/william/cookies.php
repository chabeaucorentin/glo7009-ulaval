<!DOCTYPE html>
<?php
/*****************************************************
 *                       MODEL                       *
 *****************************************************/
require("model.php");

/*****************************************************
 *               COOKIES AUTHENTICATION              *
 *****************************************************/
// CODE
?>
    <?php if (isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])): ?>
        <form method="post" action="./logoutCookieProcess.php">
            <button type="submit" name="disconnect" value="disconnect"> Se déconnecter </button>
        </form>
        <br>
        <div><span>L'usager est connecté.</span></div>
    <?php else: ?>
        <?php setcookie("userToken", '',time()-1000, '/'); ?>
        <form method="post" action="./loginCookieProcess.php">
        <label for='loginUser'> Usager: </label>
        <input type='text' name='loginUser' id='loginUser' />
        <br />
        <label for='loginPass'> Mot de passe: </label>
        <input type='password' name='loginPass' id='loginPass' />
        <br />
        <label for="checkPersistent"> Se souvenir de moi </label>
        <input type="checkbox" name="checkPersistent" id="checkPersistent" />
        <br />
        <button type='submit' name="connect" value="connect"> Se connecter </button>
        </form>
        <?php if(isset($_GET["connectError"])): ?>
            <br />
            <div><span style="color: red;"><?php echo $_GET["connectError"]; ?></span></div>
            <?php endif; ?>
    <?php endif;?>