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
            <h2>Scénario 1</h2>
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
    <!--<section>
        <h2>Résultat</h2>
        <p>[Contenu]</p>
    </section>-->
    <section>
        <h2>Scénario 2</h2>
        <ul>
            <ol> Exemple avec un compte Google </ol>
            <ol> Utiliser une extension (Cookie Editor) </ol>
            <ol> Récupérer les cookies et passer sur le même navigateur sur un autre ordinateur (préférablement sur un autre réseau) </ol>
        </ul>
    </section>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <ul class="list">
            <li>Pour être capable de subtiliser des témoins de connexions, on doit connaître le site que l\'on désire attaquer.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li><strong>Création d\'une extension vulnérable</strong><br />
            L\'attaquant doit créer une extension de navigateur vulnérable à l\'usurpation des témoins de connexions.</li>
            <li><strong>Retrouver un équipement informatique détenant des informations non détruites</strong><br />
            L\'attaquant peut retrouver un ordinateur ou un téléphone de l\'utilisateur du site. </li>
            <li><strong>Option « Se souvenir de moi » </strong><br />
            L\'utilisateur doit aussi avoir cliquer, si disponible, l\'option « se souvenir de moi », qui permet de conserver un témoin de l\'état de connexion au service du site web, autrement, selon le site web, ce dernier pourrait être automatiquement éliminé lors de la fermeture du navigateur et force la reconnexion en demandant à nouveau les identifiants, 
            ou le site web conserve un témoin de connexion avec une durée de validité variant selon la conception du site web.
            </li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li><strong>Attendre</strong><br />
                Si l\'option de créer une extension a été priorisée, on doit attendre qu\'un utilisateur du site web la télécharge et l\'installe sur le navigateur ciblé pour l\'attaque. 
                Une fois cela effectué, l\'attaquant peut récupérer les témoins de connexion en recevant de façon régulière, ces dernières des utilisateurs qui ont téléchargé l\'extension vulnérable.
            </li>
            <li><strong>Filtration des témoins</strong><br />
                L\'attaquant filtre les témoins pour ne conserver que ceux en lien avec le site web vulnérable à l\'attaque
            </li>
            <li><strong>Injection des valeurs dans le navigateur</strong><br />
            L\'attaquant modifie les valeurs de propriétés des témoins de son navigateur qui est le même navigateur que celui ciblé pour l\'attaque en les remplaçant.
            </li>
            <li><strong>Connexion sans identifiants sur le site web</strong><br />
                l\'attaquant ouvre le navigateur et se dirige sur le site web pour vérifier que la connexion sans les identifiants est un succès.
                Si la connexion est un succès, l\'attaquant peut modifier toutes les informations au compte de la victime, 
                autrement si cela est un échec, alors l\'attaquant devra trouver et investiguer plus en profondeur, 
                en regardant si l\'authentification multi facteur est activé ou toutes autres actions ou services qui surveille 
                les activités frauduleuses sur les comptes du site web.
            </li>
        </ul>
    </section>
    <section>
        <!--<h2>Analyse du code vulnérable</h2>
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
        <p>on peut remarquer que cette ligne n\'ajoute pas la vérification de la provenance de l\'utilisateur.</p>-->
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de protection</h2>
        <p>Il n\'existe pas réellement de correction au problème au vol de témoins de connexion, 
            mais on peut mitiger le problème en documentant et en informant principalement les usagers sur le risque du vol, 
            en suivant l\'une des trois options ci-dessous, et que la première option est celle qui devrait être la plus privilégiée et faite régulièrement.
        </p>
        <ul class="list">
            <li><strong>Éducation des utilisateurs</strong><br />
                On peut effectuer de la formation en informant les utilisateurs de ne pas télécharger sans vérifier la provenance de l\'extension que 
                l\'on désire installer à partir du navigateur. Par exemple, le Google Chrome Web Store qui répertorie les extensions compatibles avec le 
                navigateur Google Chrome est surveillé périodiquement par Google pour repérer et éliminer les extensions malveillantes, 
                ou si le navigateur est installé dans le réseau d\'une entreprise, pourra être contrôlé par les équipes informatiques pour empêcher 
                l\'installation et le téléchargement des extensions. 
                Dans le cas de Mozilla Firefox, on peut voir une notice de Mozilla indiquant qu\'ils sont incapables de valider l\'authenticité de l\'extension, 
                et ce sont ces derniers qu\'ils devraient être évités lors du téléchargement d\'extensions.    
            </li>
            <li><strong>Outils de surveillance des activités suspectes</strong><br />
                Le site web peut mettre en place des outils de surveillances des activités suspectes sur les comptes pour aider à réduire les problèmes liés au vol
                 des témoins de connexions, en envoyant par courriel, au destinataire du compte, qu\'il y a eu une tentative de connexion à son compte, 
                 et de cliquer sur un lien, si cela n\'est pas lié à une activité que la victime a fait elle-même pour changer la sécurité de son compte et 
                 la renforcer. 
            </li>
            <li><strong>Ajouter une couche de protection supplémentaire</strong><br />
                Les propriétaires du site web peuvent ajouter l\'authentification multi facteur pour limiter le vol. 
                À partir de l\'authentification multi facteur, on peut choisir un téléphone, un gestionnaire de mot de passe à usage temporel unique (OTP) 
                ou une clé physique de type FIDO, par exemple Yubikey.
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
       <!-- <h2>Correction du code vulnérable</h2>
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
        </p>-->
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <!--<li><a href="https://developer.mozilla.org/fr/docs/Glossary/User_agent" target="_blank">Mozilla Developer Network [Agent utilisateur]</a></li>-->
            <li><a href="https://elsefix.com/fr/what-is-a-pass-the-cookie-attack-how-to-stay-logged-in-to-websites-safely.html" target="_blank">Elsefix [Qu\'est-ce qu\'une attaque Pass-the-Cookies]</a></li>
            <!--<li><a href="https://whatmyuseragent.com/" target="_blank">What\'s My User Agent?</a></li>-->
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par cookies", $presentation, $demonstration, $exploit, $fix);