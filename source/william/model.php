<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                     FUNCTIONS                     *
 *****************************************************/
function generate_token($size) {
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $length = strlen($characters);
    $code = "";

    for ($i = 0; $i < $size; $i++) {
        $rand = rand(0, $length - 1);
        $code .= $characters[$rand];
    }

    return $code;
}

function login_secure($email, $password) {
    global $db;

    $req = "SELECT user_id FROM users WHERE user_email = ? AND user_password = ?";
    $stmt = mysqli_prepare($db, $req);
    $password_hash = hash('sha256', md5($password));
    mysqli_stmt_bind_param($stmt, "ss", $email, $password_hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $res);
    mysqli_stmt_fetch($stmt);
    $id = $res;
    mysqli_stmt_close($stmt);
    $apiURL = 'https://freegeoip.app/json/'. $_SERVER['REMOTE_ADDR'];
    $curlRequest = curl_init($apiURL);
    curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($curlRequest);
    curl_close($curlRequest);
    $ipData = json_decode($apiResponse, true);
    if (!empty($ipData)){
        $latitude = $ipData['latitude'];
        $longitude = $ipData['longitude'];
        $city = $ipData['city'];
        $country = $ipData['country_code'];
        if (isset($id)) {
            $req = "INSERT INTO tokens (token_code, token_user_id, token_latitude, token_longitude, token_city, token_country) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $req);
            $token = generate_token(50);
            mysqli_stmt_bind_param($stmt, "si", $token, $id, $latitude, $longitude, $city, $country);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $token;
        }
    }

    return null;
}

function login($email, $password) {
    global $db;

    $req = "SELECT user_id FROM users WHERE user_email = ? AND user_password = ?";
    $stmt = mysqli_prepare($db, $req);
    $password_hash = hash('sha256', md5($password));
    mysqli_stmt_bind_param($stmt, "ss", $email, $password_hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $res);
    mysqli_stmt_fetch($stmt);
    $id = $res;
    mysqli_stmt_close($stmt);

    if (isset($id)) {
        $req = "INSERT INTO tokens (token_code, token_user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($db, $req);
        $token = generate_token(50);
        mysqli_stmt_bind_param($stmt, "si", $token, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $token;
    }

    return null;
}


function is_logged_in_secure($token) {
    global $db;
            $user ="SELECT user_email FROM users WHERE user_id = ?";
            $req = "SELECT token_code, token_user_id, token_latitude, token_longitude, token_city, token_country FROM tokens WHERE token_code = ?";
            $stmt = mysqli_prepare($db, $req);
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $nb = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
            $apiURL = 'https://freegeoip.app/json/'. $_SERVER['REMOTE_ADDR'];	
            $curlRequest = curl_init($apiURL);
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
            $apiResponse = curl_exec($curlRequest);
            curl_close($curlRequest);
            $ipData = json_decode($apiResponse, true);
            if (!empty($ipData)){
                $latitude = $ipData['latitude'];
                $longitude = $ipData['longitude'];
                $city = $ipData['city'];
                $country = $ipData['country_code'];
                if ($nb > 0 && $nb == 1) {
                    $result = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_row($result)) {
                        $token_user_id = $row[1];
                        $token_latitude = $row[2];
                        $token_longitude = $row[3];
                        $token_city = $row[4];
                        $token_country = $row[5];
                        $stmt -> free_result();
                        break;
                    }
                    $user_stmt = mysqli_prepare($db, $user);
                    mysqli_stmt_bind_param($user_stmt, "s", $token_user_id);
                    mysqli_stmt_execute($user_stmt);
                    mysqli_stmt_store_result($user_stmt);
                    $result_user = mysqli_stmt_get_result($user_stmt);
                    while($row = mysqli_fetch_row($result_user)) {
                        $user_email = $row[0];
                        break;      
                    }
                    if ($token_latitude != $latitude || $token_longitude != $longitude || $token_city != $city || $token_country != $country) {
                        $mail_to = $user_email;
                        $mail_subject = 'Une connexion inhabituelle est survenue';
                        $mail_message = 'Une connexion est survenue sur votre compte, si cela vient de vous, vous n\'avez rien à faire, autrement, 
                            on vous suggère de vous connecter à votre compte et d\'effectuer une modification de votre mot de passe, 
                            et de vérifier votre ordinateur contre les voleurs de témoins de connexions. ';
                        $mail_headers ='From : webmestre@glo7009.laval.university'. '\r\n'. 'Reply-To : webmestre@glo7009.laval.university'. '\r\n' . 'X-Mailer :PHP/'.phpversion();
                        mail($mail_to,$mail_subject,$mail_message,$mail_headers);
                    }
                return $nb > 0;
            }
             else {return 0;}
          }
            else {
                return 0;
        } 
    }
function is_logged_in($token) {
    global $db;

    $req = "SELECT token_code FROM tokens WHERE token_code = ?";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $nb = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    return $nb > 0;
}

function logout($token) {
    global $db;

    $req = "DELETE FROM tokens WHERE token_code = ?";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

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
        } else {
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
