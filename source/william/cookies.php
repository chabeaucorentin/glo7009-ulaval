<?php
/*****************************************************
 * cookies.php                                       *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                       MODEL                       *
 *****************************************************/
require("model.php");

/*****************************************************
 *               COOKIES AUTHENTICATION              *
 *****************************************************/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["modifyCookie"]) && isset($_POST["cookieField"])) {
        if (!isset($_COOKIE["userToken"])) {
            setcookie("userToken", $_POST["cookieField"], time() + (60*60*24));
            header("Location:".$config["site_link"]."/".$menu[2]["folder"]."/".$menu[2]["files"][0]["name"]."?view=demonstration");
            exit();
        } else {
            unset($_COOKIE["userToken"]);
            setcookie("userToken",$_POST["cookieField"], time() + (60*60*24));
            header("Location:". $config["site_link"]."/".$menu[2]["folder"]."/".$menu[2]["files"][0]["name"]."?view=demonstration");
            exit();
        }
    } else if (isset($_POST["connect"])) {
        $error = array();
        if (isset($_POST["email"])) {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error["email"] = "Veuillez entrer une adresse courriel valide.";
            }
        } else {
            $error["email"] = "Veuillez entrer une adresse courriel.";
        }

        if (!isset($_POST["password"]) || (isset($_POST["password"]) && empty($_POST["password"]))){ 
            $error["password"] = "Veuillez entrer un mot de passe.";
        }

        if (!isset($error["email"]) && !isset($error["password"])) {
            $token = login($_POST["email"], $_POST["password"]);
            if (isset($token)) {
                if (isset($_POST["persistent"])) {
                    setcookie("userToken", $token, time() + (60*60*24*30));
                } else {
                    setcookie("userToken",$token, time()+(60*60*24));
                }
                header("Location:".$config["site_link"]."/".$menu[2]["folder"]."/".$menu[2]["files"][0]["name"]."?view=demonstration");
                exit();
            } else {
                $error["token"] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    }
     else if (isset($_POST["disconnect"])) {
        logout($_COOKIE["userToken"]);
        unset($_POST);
        unset($_COOKIE);
    }
}

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>Vulnérabilité d\'authentification par le vol de cookies (Pass-the-cookies)</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité et intégrité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>La subtilisation des témoins (cookies) est une vulnérabilité permettant à l\'usurpateur de récupérer les témoins, cette attaque est aussi nommée Pass-the-cookie. 
            Une fois que les témoins sont récupérés par cet usurpateur, il pourra effectuer des opérations sur le site web, 
            tout en se faisant passer pour l\'utilisateur légitime. 
        </p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
         <ul class="list">
            <li>Obtenir de l\'information sur des utilisateurs légitimes</li>
            <li>Effectuer des actes répréhensibles sur ses utilisateurs</li>
            <li>Acquérir de l\'information dans le but de discriminer les propriétaires du site.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li> La provenance du jeton n\'est pas vérifiée.</li>
            <li> La création du témoin n\'est pas suffisamment sécurisée.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong><a href="https://nvd.nist.gov/vuln/detail/CVE-2021-44151#match-9099598">Reprise Software (CVE-2021-44151)</a></strong><br />
                Une attaque de vol de cookies de session est survenue sur l\'hébergeur de licence <a href="https://reprisesoftware.com">Reprise Software</a>. Cette entreprise n\'utilise que des cookies de sessions qui ont une courte longueur.
                 L\'attaquant pouvait donc survenir à passer et obtenir le cookie par attaque de force brute. Cette attaque par force brute pouvait survenir, car les cookies de session n\'avait que 4 caractères hexadécimales pour Windows ou 8 pour les sessions Linux.
            </li>
            <li><strong><a href="https://www.cve.news/cve-2023-5723/"> Firefox Vulnerability - Unrestricted Cookies Hijack via Insecure `document.cookie` Usage (CVE-2023-5723))</a></strong><br />
                Une attaque de vol de cookies est possible sur toutes les versions de Firefox v.119 et précédents, car l\'appel à la fonction javascript `document.cookie` peut permettre à un attaquant d\'avoir temporairement accès aux cookies stockés dans le navigateur et d\'avoir accès à l\'exécution d\'un script sur un site web.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])) ? '
            <p style="color: green;">L\'usager est connecté.</p>':'
            <div class="form-group">
                '.((isset($error["token"])) ? '<div class="alert">'.$error["token"].'</div>' : '').'
            </div>
            <div class="form-group">
                <label for="email">Adresse email :</label>
                '.((isset($error["email"])) ? '<div class="alert">'.$error["email"].'</div>' : '').'
                <input id="email" class="form-control'.((isset($error["email"])) ? ' invalid' : '').'" name="email" type="email"'.((isset($error["email"])) ? ' value="'.$_POST["email"].'"' : '').' />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                '.((isset($error["password"])) ? '<div class="alert">'.$error["password"].'</div>' : '').'
                <input id="password" class="form-control'.((isset($error["password"])) ? ' invalid' : '').'" name="password" type="password" />
            </div>
            <div class="form-group">
                <input id="persistent" class="form-check" name="persistent" type="checkbox" />
                <label for="persistent">Se souvenir de moi</label>
            </div>
            ').'
        </div>
        <footer>
            '.((isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit" value="disconnect">Déconnexion</button>
            ' : '
            <button name="connect" type="submit" value="connect">Connexion</button>
            ').'
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Configuration</h2>
            <div class="form-group">
                <label for="cookieField">Valeur du témoin (cookie)</label>
                <input id="cookieField" class="form-control" name="cookieField" type="text" value="'.$_COOKIE["userToken"].'"/>
            </div>
        </div>
        <footer>
            <button name="modifyCookie" type="submit" value="modifyCookie">Modifier le témoin</button>
        </footer>
    </form>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <ul class="list">
            <li>Extension de navigateur vulnérable (récupère les témoins et les envoie à l\'attaquant)</li>
            <li>L\'attaquant est connecté sur le même réseau.</li>
            <li>L\'attaquant peut récupérer un équipement informatique détenant des informations non détruites sécuritairement.</li>
        </ul>
    </section>
    <section>
        <h2>Vecteur d\'attaque</h2>
        <ul class="list">
            <li>Attaque active permettant d\'accéder aux identifiants d\'un utilisateur par ingénierie sociale.
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li><strong>Attendre</strong><br />
                Après attente de l\'installation de l\'extension et l\'extension récupère les jetons et les envoient à l\'attaquant.    
            </li>
            <li><strong>Filtration des témoins</strong><br />
                Ensuite, l\'attaquant filtre les témoins pour ne conserver que ceux en lien avec le site web vulnérable à l\'attaque
            </li>
            <li><strong>Injection des valeurs dans le navigateur</strong><br />
            L\'attaquant modifie les valeurs de propriétés des témoins de son navigateur qui est le même navigateur que celui ciblé pour l\'attaque en les remplaçant.
            </li>
        </ul>
    </section>
    <section>
        <h2>Analyse du code vulnérable</h2>
        <p>La fonction PHP de connexion ci-dessous est vulnérable, car on n\'insère pas, 
            pendant la connexion, la localisation de l\'adresse IP permettant de repérer 
            l\'endroit de la connexion. 
            Autrement dit, la fonction login ne met pas les valeurs de sécurités pour notifier l\'utilisateur en cas de connexion à un nouvel emplacement.
        </p>
        <pre class="line-numbers" data-line="5-10"><code class="language-php">
            function login($email, $password) {
                …
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
        </code></pre>
        <p>La seconde fonction PHP de connexion ci-dessous est aussi vulnérable, car il n\'y a pas de vérification de l\'endroit d\'où vient la connexion.</p>
        <pre class="line-numbers" data-line="4,11"><code class="language-php">
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
        </code></pre>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de protection</h2>
        <ul class="list">
            <li><strong>Éducation des utilisateurs</strong><br />
                Éduquer et informer les utilisateurs sur les risques du vol des témoins de connexions. 
                (Cette option est celle à privilégier, car elle permet de limiter le plus possible des dégâts)
            </li>
            <li><strong>Outils de surveillance des activités suspectes</strong><br />
                Mettre en place des outils de surveillance des activités. 
                (Envoi d\'un courriel à l\'utilisateur pour spécifier une nouvelle connexion à un nouvel emplacement)
            </li>
            <li><strong>Ajouter une couche de protection supplémentaire</strong><br />
                Ajouter l\'authentification multi facteur. 
                (Limiter les risques en ajoutant une clé physique FIDO (Yubikey) ou d\'ajouter un gestionnaire de mot de passe temporelle à usage unique (OTP))
            </li>
        </ul>
    </section>
    <section>
        <h2>Détection de la vulnérabilité</h2>
        <ul class="list">
            <li>La détection ne pourra pas être 100% efficace.
                Avec des outils de surveillance des activités, on pourra effectuer un envoi de courriel, 
                si la localisation du client diffère de celle actuellement stocké sur la BD des connexions actives.
            </li>
        </ul>
    </section>
    <section>
       <h2>Correction du code vulnérable</h2>
       <p> Pour être capable de corriger le code précédent, on doit modifier la table des connexions actives dans la base de données. Voici le code: </p>
       <pre class="line-numbers"><code class="language-mysql">
        ALTER TABLE `tokens` ADD `token_latitude` VARCHAR(30) NOT NULL AFTER `token_user_id`, 
                             ADD `token_longitude` VARCHAR(30) NOT NULL AFTER `token_latitude`, 
                             ADD `token_city` VARCHAR(50) NOT NULL AFTER `token_longitude`, 
                             ADD `token_country` VARCHAR(10) NOT NULL AFTER `token_city`;
       </code></pre>
        <p>En reprenant le code précédent, la version corrigée du code de connexion d\'un usager est le suivant :</p>
        <pre class="line-numbers" data-line="4-14,16,19"><code class="language-php">
            function login($email, $password) {
                …
                $apiURL = \'https://freegeoip.app/json/\'. $_SERVER[\'REMOTE_ADDR\'];
                $curlRequest = curl_init($apiURL);
                curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
                $apiResponse = curl_exec($curlRequest);
                curl_close($curlRequest);
                $ipData = json_decode($apiResponse, true);
                if (!empty($ipData)){
                $latitude = $ipData[\'latitude\'];
                $longitude = $ipData[\'longitude\'];
                $city = $ipData[\'city\'];
                $country = $ipData[\'country_code\'];
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
                return 0;
            }
        </code></pre><br />
        <p>Les lignes 4 à 14, permettent de générer le code servant à retrouver la localisation du client à partir de son adresse IP. <br/>
        La ligne 16 sert à la création de la requête d\'ajout du jeton d\'accès lorsque le client se connecte. <br />
        La ligne 19 permet de lier les variables définis aux paramètres de la requête avant de l\'exécuter.
        </p>
        <p> De même pour la vérification de la connexion </p>
        <pre class="line-numbers" data-line="4-5,12-22,24-50"><code class="language-php">
        function is_logged_in($token) {
            global $db;
            $user ="SELECT user_email FROM users WHERE user_id = ?";
            $req = "SELECT token_code, token_user_id, token_latitude, token_longitude, token_city, token_country FROM tokens WHERE token_code = ?";
            $stmt = mysqli_prepare($db, $req);
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $nb = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
            $apiURL = \'https://freegeoip.app/json/\'. $_SERVER[\'REMOTE_ADDR\'];	
            $curlRequest = curl_init($apiURL);
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
            $apiResponse = curl_exec($curlRequest);
            curl_close($curlRequest);
            $ipData = json_decode($apiResponse, true);
            if (!empty($ipData)){
                $latitude = $ipData[\'latitude\'];
                $longitude = $ipData[\'longitude\'];
                $city = $ipData[\'city\'];
                $country = $ipData[\'country_code\'];
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
                        $mail_subject = \'Une connexion inhabituelle est survenue\';
                        $mail_message = \'Une connexion est survenue sur votre compte, si cela vient de vous, vous n\'avez rien à faire, autrement, 
                            on vous suggère de vous connecter à votre compte et d\'effectuer une modification de votre mot de passe, 
                            et de vérifier votre ordinateur contre les voleurs de témoins de connexions. \';
                        $mail_headers =\'From : webmestre@glo7009.laval.university\'. \'\r\n\'. \'Reply-To : webmestre@glo7009.laval.university\'. \'\r\n\' . \'X-Mailer :PHP/\'.phpversion();
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
        
        </code></pre>
        <p> Les lignes 4 et 5 présentent la création de la requête de récupération du jeton d\'accès pour la validation de la connexion courante. <br />
        Les lignes 12 à 22 présentent la requête d\'acquisition de l\'emplacement de l\'utilisateur selon son adresse IP. <br />
        Les lignes 24 à 30 présentent la récupération de l\'enregistrement de l\'utilisateur dans la table des connexions actives. <br />
        Les lignes 32 à 34 présentent la récupération de l\'adresse courriel de l\'utilisateur. <br />
        Les lignes 36 à 50 présentent la vérification de l\'emplacement de l\'utilisateur selon ce qui est inscrit dans la base de donnée, et de l\'envoi d\'un courriel si la vérification d\'exactitude echoue.
        </p>
    </section>
    <section>
        <h2>Outils de détection</h2>
        <ul class="list">
            <li><strong>Nikto</strong><br />
                Pour Nikto, aucune détection du vol de témoins possible, elles ne sont pas détecté.
            </li>
            <li><strong>ZAP</strong><br />
            Pour ZAP, la détection ne considère pas la détection du vol de témoin, mais détecte uniquement qu\'il y a une connexion.
            </li>
            <li><strong>Skipfish</strong><br />
            Pour Skipfish, la détection ne considère pas la détection du vol de témoin, mais détecte uniquement qu\'il y a une connexion.
            </li>
        </ul>
    </section>
   
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href="https://elsefix.com/fr/what-is-a-pass-the-cookie-attack-how-to-stay-logged-in-to-websites-safely.html" target="_blank">Elsefix [Qu\'est-ce qu\'une attaque Pass-the-Cookies]</a></li>
            <li><a href="https://www.crowdstrike.com/cybersecurity-101/pass-the-hash/" target="_blank">Crowdstrike [What is a pass-the-hash attack]</a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par cookies", $presentation, $demonstration, $exploit, $fix);