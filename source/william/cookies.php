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
            <p>Confidentialité, intégrité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>Cette vulnérabilité permet à l\'attaquant de contourner l\'authentification de l\'utilisateur légitime en subtilisant les cookies de connexions de ce dernier. 
        Pour que l\'attaque fonctionne, le pirate doit simuler la page de connexion du site web cible et envoi à l\utilisateur ciblé un courriel demandant à se dernier de se connecter à son compte pour ne pas perdre son accès. 
        Les effets potentiels de cette vulnérabilité sont une atteinte à la crédibilité de l\'entreprise visée, une perte de confiance des utilisateurs envers les clients potentiels, si le site web est un réseau social.
        </p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
         <ul class="list">
            <li> Obtenir de l\'information sur des utilisateurs du site web.</li>
            <li> Acquérir des données dans le but de discriminer l\'entreprise qui détient le site web.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li> La provenance du token n\'est pas vérifiée.</li>
            <li> La création du token n\'est pas suffisament sécurisée.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong><a href="https://nvd.nist.gov/vuln/detail/CVE-2021-44151#match-9099598">Reprise Software (CVE-2021-44151)</a></strong><br />
                Une attaque de vol de cookies de session est survenue sur l\'hébergeur de licence <a href="https://reprisesoftware.com">Reprise Software</a>. Cette entreprise n\'utilise que des cookies de sessions qui ont une courte longueur.
                 L\'attaqunt pouvait donc survenir à passer et obtenir le cookie par attaque de force brute. Cette attaque par force brute pouvait survenir, car les cookies de session n\'avait que 4 caractères hexadécimales pour Windows ou 8 pour les sessions Linux.
                 De plus l\'attaquant peut déterminer facilement récupérer le token en accédant à l\'un des formulaires du site et récupérer le nom du cookie avec la réponse envoyée. Ensuite, l\'attaquant requête la même page en changeant la valeur par une valeur aléatoire.
                 Si la valeur du cookie touche une valeur qui existe dans la base de données au niveau de la table des usagers connectés et que la comparaison est valide entre la valeur reçue et celle de la table, il y a un retour avec une page autorisée à s\'afficher. 
            </li>
            <li><strong><a href="https://www.cve.news/cve-2023-5723/"> Firefox Vulnerability - Unrestricted Cookies Hijack via Insecure `document.cookie` Usage (CVE-2023-5723))</a></strong><br />
                Une attaque de vol de cookies est possible sur toutes les versions de Firefox v.119 et précédents, car l\'appel à la fonction javascript `document.cookie` peut permettre à un attaquant d\'avoir temporairement accès aux cookies stockés dans le navigateur et d\'avoir accès à l\'exécution d\'un script sur un site web.
                Un exemple tel que cité par le site est :<br /> 
                <pre class="line-numbers" data-line="2">
                <code class="language-js">
                // Exploit code snippet
                document.cookie = "insecure_cookie=test%00value";
                </code></pre><br /> 
                Donc le code permet avec le code hexadécimal du NULL (%00) de pouvoir exécuter du code et d\'avoir un comportement possiblement non désiré du navigateur.                
                Cela permet donc de l\'exploitation de code XSS, d\'une brèche de confidentialité et d\'atteinte à l\'intégrité des données des utilisateurs.
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
                <label for="persistent">Se souvenir de moi :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="persistent" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="persistent" type="checkbox" />
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
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li><strong>Envoi d\'un courriel demandant à un utilisateur de se connecter</strong><br />
                L\'attaquant envoie une demande de connexion à un utilisateur quelconque. Cet utilisateur a été au préalable investigué par l\'attaquant pour permettre de l\'amadouer ou de le faire sentir dans l\'urgence de cliquer sur le lien pour permettre, admettons, de retrouver les accès à son compte, mais en se faisant, se retrouve à se connecter et à fournir le cookie généré à l\'attaquant.
            </li>
            <li><strong>Exploitation de la récupération d\'un cookie par une extension de navigateur vulnérable</strong><br />
                L\'attaquant crée une extension de navigateur et l\'envoie à plusieurs utilisateurs du site web en vantant les principes de facilité de navigation sur le site web par exemple. Cette extension n\'améliore en rien la navigation du site, mais récupère les cookies du site et les retransmet à l\'attaquant.
            </li>
        </ul>
        <p>Toutes ces méthodes d\'exploitations mènent ensuite à l\'injection dans un navigateur de l\'attaquant le nom du cookie et une valeur associée et cela permet de se connecter sans avoir les identifiants à un utilisateur dans le site web.</p>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li><strong>Création du courriel</strong><br />
                [Brève description du contexte]
            </li>
            <li><strong>[Étape 2](Ex : Télécharger le fichier malveillant)</strong><br />
                [Brève description du contexte]
            </li>
            <li><strong>[Étape 3](Ex : Activer le script)</strong><br />
                [Brève description du contexte]
            </li>
        </ul>
    </section>
    <section>
        <h2>Analyse du code vulnérable</h2>
        <p>[Description du code]</p>
        <pre class="line-numbers" data-line="2"><code class="language-php">function addition($a, $b) {
    $result = $a + $b; // Pas de vérification des valeurs
    return $result;
}

echo addition(1 + 2); // 3</code></pre>
        <p>[Description de la/les ligne(s) qui introdui(sen)t la/les vulnérabilité(s)]</p>
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
                On peut ajouter ce bout de code, par exemple: 
                <pre class="line-numbers" data-lines="4"><code class="language-sql">
                    ALTER TABLE `tokens` 
                        ADD `token_user_localization` VARCHAR(255) NOT NULL AFTER `token_user_id`, 
                        ADD `token_user_ip` VARCHAR(255) NOT NULL AFTER `token_user_localization`, 
                        ADD `token_navigator_user_agent` VARCHAR(255) NOT NULL AFTER `token_user_ip`; 
                </code></pre>
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
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des outils de détection]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li><strong>[Outil 1](Ex : Nikto)</strong><br />
                [Brève description de la mesure]
            </li>
            <li><strong>[Outil 2](Ex : ZAP)</strong><br />
                [Brève description de la mesure]
            </li>
            <li><strong>[Outil 3](Ex : Skipfish)</strong><br />
                [Brève description de la mesure]
            </li>
        </ul>
    </section>
    <section>
        <h2>Correction du code vulnérable</h2>
        <p>[Description du code]</p>
        <pre class="line-numbers" data-line="2,5-7"><code class="language-php">function addition($a, $b) {
    if (is_int($a) && is_int($b)) { // Vérification que $a et $b sont des entiers
        $result = $a + $b;
        return $result;
    else {
        return "Les 2 paramètres doivent être des entiers !";
    }
}

echo addition(1 + 2); // 3</code></pre>
        <p>[Description de la/les ligne(s) modifiée(s)]</p>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href="#" target="_blank">[Nom de la ressource 1]</a></li>
            <li><a href="#" target="_blank">[Nom de la ressource 2]</a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par cookies", $presentation, $demonstration, $exploit, $fix);