<!DOCTYPE html>
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
// CODE

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
        <p>Cette vulnérabilité permet à l\'attaquant de contourner l\'authentification de l\'utilisateur légitime en subtilisant les cookies de connexions de ce dernier. 
        Pour que l\'attaque fonctionne, le pirate doit avoir accès au navigateur de la victime.
        Une fois qu\'il a accès au navigateur, il pourra récupérer les cookies à partir de la console de développement ou à la commande `document.cookie`. 
        Les effets potentiels sont une perte de confiance et une perte de crédibilité envers le site web ou la personne.
        </p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
         <ul class="list">
            <li> Obtenir de l\'information sur des utilisateurs du site web.</li>
            <li> Discriminer la personne victime. </li>
            <li> Acquérir des données dans le but de discriminer l\'entreprise qui détient le site web.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li> La provenance du token n\'est pas vérifiée.</li>
            <li> La création du token n\'est pas suffisamment sécurisée.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong><a href="https://nvd.nist.gov/vuln/detail/CVE-2021-44151#match-9099598">Reprise Software (CVE-2021-44151)</a></strong><br />
                Une attaque de vol de cookies de session est survenue sur l\'hébergeur de licence <a href="https://reprisesoftware.com">Reprise Software</a>. Cette entreprise n\'utilise que des cookies de sessions qui ont une courte longueur.
                 L\'attaqunt pouvait donc survenir à passer et obtenir le cookie par attaque de force brute. Cette attaque par force brute pouvait survenir, car les cookies de session n\'avait que 4 caractères hexadécimales pour Windows ou 8 pour les sessions Linux.
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
            <p style="color: green;">L\'usager est connecté</p>
            ' : '
            <div class="form-group">
                <label for="email">Adresse email :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="email" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="email" type="email" />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="password" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="password" type="password" />
            </div>
            <div class="form-group">
                <input id="persistent" class="form-check'.((isset($error)) ? ' invalid' : '').'" name="persistent" type="checkbox" />
                <label for="persistent">Se souvenir de moi</label>
            </div>
            ').'
        </div>
        <footer>
            '.((isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit" value="disconnect" onclick='.logoutProcess().'>Déconnexion</button>
            ' : '
            <button name="connect" type="submit" value="connect" onclick='.((isset($_GET["toEmail"])) ? loginProcessVulnerable($_GET["toEmail"]) : loginProcess()).'>Connexion</button>
            ').'
        </footer>
    </form>
    <section>
        <h2>Résultat</h2>
        <p>[Contenu]</p>
    </section>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <ul class="list">
            <li><strong>Une base de données ne détenant pas assez d\'informations sur la provenance des utilsateurs<br />
                OU<br />
                n\'étant pas assez aléatoire sur la création du token (longueur pas assez longue lors de la création)</strong><br />
                Lors de la création de la base de données, on crée la table des utilisateurs connectés en ne fournissant que le minimum fonctionnel. Autrement dit, on ajoute que l\'identificateur de l\'utilisateur et son token. En plus, la longueur du token peut être trop faible et on ne la fait pas assez longue.
            </li>
            <li><strong>Avoir un formulaire de connexion</strong><br />
                Il est nécessaire d\'avoir un formulaire de connexion pour déclencher la requête de connexion d\'un utilisateur pour pouvoir exploiter la récupération de cookies qui seront créés lors de la connexion.
            </li>
            <li><strong>Avoir une extension de navigateur <br /> OU <br /> avoir un programme mouchard qui écoute et récupère les témoins</strong>
                Il est aussi nécessaire qu\'il y ait une extension active ou un programme qui puisse récupérer les cookies en temps réels et qui les envoient au pirate.
            </li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li><strong>Envoi d\'un courriel demandant à un utilisateur de se connecter</strong><br />
                L\'attaquant envoie une demande de connexion à un utilisateur quelconque. Cet utilisateur a été au préalable investigué par l\'attaquant pour permettre de l\'amadouer ou de le faire sentir dans l\'urgence de cliquer sur le lien pour permettre, admettons, de retrouver les accès à son compte, mais en se faisant, se retrouve à se connecter et à fournir le cookie généré à l\'attaquant.
            </li>
            <li><strong>Exploitation de la récupération d\'un cookie par une extension de navigateur vulnérable</strong><br />
                L\'attaquant crée une extension de navigateur et l\'envoie à plusieurs utilisateurs du site web.
            </li>
        </ul>
        <p>Toutes ces méthodes d\'exploitations mènent ensuite à l\'injection dans un navigateur de l\'attaquant le nom du cookie et une valeur associée et cela permet de se connecter sans avoir les identifiants à un utilisateur dans le site web.</p>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li><strong>Création du courriel</strong><br />
                Le courriel est créé par l\'attaquant selon l\'un des principes mentionnés dans les méthodes d\'attaques.
            </li>
            <li><strong>Attendre</strong><br />
                L\'attaquant attends que la victime télécharge et installe l\'extension, s\'il y a lieu. Il attends aussi la connexion de l\'utilisateur sur le site web pour recevoir les témoins des jetons de connexion sur son ordinateur. 
            </li>
            <li><strong>Connexion de l\'attaquant</strong><br />
                Une fois que l\'attaquant a reçu les témoins, il peut débuter la connexion au site. Il filtre d\'abord les témoins pour ne garder que les jetons reliés au site web. Ensuite, par attaque de force brute, l\'attaquant essaie les différentes combinaisons possibles pour se connecter. Si le site indique que l\'utilisateur est connecté, alors l\'attaque s\'est déroulée avec succès. 
            </li>
        </ul>
    </section>
    <section>
        <h2>Analyse du code vulnérable</h2>
        <p>Le code vulnérable ci-dessous est la requête de vérification de la connexion d\'un utilisateur</p>
        <pre class="line-numbers" data-line="5"><code class="language-php">
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
        }</code></pre>
        <p>on peut remarquer que cette ligne n\'ajoute pas la vérification de la provenance de l\'utilisateur.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de protection</h2>
        <ul class="list">
            <li><strong>Éducation des utilisateurs</strong><br />
                On peut éduquer les utilisateurs sur l\'attaque par courriel. De vérifier la provenance des courriels, autrement dit, l\'expéditeur, de vérifier les fautes d\'orthographe ou de ne pas ouvrir un courriel si on ne connait pas la provenance et de l\'expédier, si possible, à l\'équipe de sécurité qui gère les courriels indésirables.
            </li>
            <li><strong>Sécuriser à la création les cookies</strong><br />
                On peut ajouter les attributs `HTTP-Only` et `Same-site` des cookies pour ne permettre leur exécution que sur le site web qui l\'exploite.
            </li>
            <li><strong>Modifier la base de données</strong><br />
                On peut ajouter des champs de localisation, des informations de l\'agent utilisateur du navigateur, etc.
                <br />
                De plus, en installant les composants  <a href="https://dev.maxmind.com/geoip/geoip2/geolite2/">GeoIP2 </a> et
                <a href="https://github.com/maxmind/GeoIP2-php/releases">la dernière sortie de geoip2.phar</a> dans les fichiers sources du serveur PHP.
                <br />
                On peut ajouter ce bout de code, voir la section sur la correction de la correction de code vulnérable plus bas.               
            </li>
            <li><strong>Longueur des cookies</strong><br />
                On peut allonger la longugeur des cookies à une longueur suffisante pour éviter les attaques par force brute.
            </li>
            <li><strong>Outils de détection des menaces</strong><br />
                Si aucune des méthodes ci-dessus ne peut être effectué, la dernière option serait d\'ajouter un outils de détection des menaces actives. Cet outil détectera les possibles menaces avant qu\'elles n\'arrivent si un cookie est dérobé.
            </li>
        </ul>
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
        <h2>Correction du code vulnérable</h2>
        <p>Correction de la fonction is_logged_in et ajout de la fonction de recherche de la localisation de l\'utilisateur</p>
        <pre class="line-numbers"><code class="language-mysql">
        ALTER TABLE `tokens` 
            ADD `token_user_localization` VARCHAR(255) NOT NULL AFTER `token_user_id`, 
            ADD `token_user_ip` VARCHAR(255) NOT NULL AFTER `token_user_localization`, 
            ADD `token_navigator_user_agent` VARCHAR(255) NOT NULL AFTER `token_user_ip`; 
    </code></pre><br />
    Ensuite, on modifie la requête de vérification de la connexion en ajoutant les champs ajoutés à la base de données: <br />
    <pre class="line-numbers" data-line="1-13,16-23,25-28"><code class="language-php">
        require_once("geoip2.phar");
        use GeoIp2\Database\Reader;
        function clientIpAddress(){
            if(!empty($_SERVER[\'HTTP_CLIENT_IP\'])){
            $address = $_SERVER[\'HTTP_CLIENT_IP\'];
            }elseif(!empty($_SERVER[\'HTTP_X_FORWARDED_FOR\'])){
            $ address = $_SERVER[\'HTTP_X_FORWARDED_FOR\'];
            }else{
            $ address = $_SERVER[\'REMOTE_ADDR\'];
            }
            return $address;
        };
        function is_logged_in($token) {
            global $db;
            $reader = new Reader(\'/path/to/GeoLite2-Country.mmdb\');
            $client_ip = clientIpAddress();
            $client_record = $reader->country($client_ip);
            $req = "SELECT token_code " .
                    "FROM tokens WHERE token_code = ? " .
                    "AND token_user_localization = ? " .
                    "AND token_user_ip = ? " .
                    "AND token_navigator_user_agent = ?;";
            $stmt = mysqli_prepare($db, $req);
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_bind_param($stmt, "s","[".$client_record->location->latitude.",".$client_record->location->longitude."]");
            mysqli_stmt_bind_param($stmt, "s", $client_ip);
            mysqli_stmt_bind_param($stmt, "s", $_SERVER[\'HTTP_USER_AGENT\']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $nb = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);

            return $nb > 0;
        }
    </code></pre><br />
        <p>Les lignes 1 à 3, servent à l\'appel de la librairie de géolocalisation de l\'utilisateur. <br/>
        Les lignes 4 à 13 servent à la fonction de récupération de l\'adresse IP de l\'utilisateur, même si ce dernier est derrière un proxy. <br />
        Les lignes 16 à 18 permettent d\'initier la librairie de géolocalisation et de récupérer l\'adresse IP de l\'utilisateur dans une variable <br />
        Les lignes 19 à 23 sont la nouvelle version de la requête de vérification de connexion de l\'utilisation <br />
        Les lignes 25 à 28 sont les lignes de populations des variables de la requête. <br />
        La ligne 26 permet de récupérer la géolocalisation. <br />
        La ligne 28 permet de récupérer l\'agent utilisateur du navigateur de l\'utilisateur.
        </p>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href="https://developer.mozilla.org/fr/docs/Glossary/User_agent" target="_blank">Mozilla Developer Network [Agent utilisateur]</a></li>
            <li><a href="https://elsefix.com/fr/what-is-a-pass-the-cookie-attack-how-to-stay-logged-in-to-websites-safely.html" target="_blank">Elsefix [Qu\'est-ce qu\'une attaque Pass-the-Cookies]</a></li>
            <li><a href="https://whatmyuseragent.com/" target="_blank">What\'s My User Agent?</a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par cookies", $presentation, $demonstration, $exploit, $fix);