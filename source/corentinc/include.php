<?php
/*****************************************************
 * include.php                                       *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                INCLUSION EXECUTION                *
 *****************************************************/
if (isset($_POST["lang"])) {
    $lang = $_POST["lang"];
} else {
    $lang = "fr.php";
}

$include = include($lang);

$load = htmlspecialchars(file_get_contents($lang), ENT_QUOTES);
$success = (isset($_POST["include"]) && ($include || $load));
$error = (isset($_POST["include"]) && !$success);

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>Vulnérabilités de manipulation de fichiers</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité, intégrité et disponibilité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>En vue d’une meilleure organisation du code, il nous arrive souvent de structurer nos projets en les
        découpant en plusieurs fichiers. L’objectif principal étant de faciliter la réutilisation de parties communes ou
        d’importer du code dépendant de certaines conditions. Une telle organisation du code pourrait introduire une
        vulnérabilité par inclusion de fichiers (File Inclusion Vulnerability). La raison étant souvent causée par une
        vérification insuffisante des entrées utilisateurs.</p>
        <p>Cette attaque pourrait être exploitée lorsque l’<a href="'.$config["site_link"]."/".$menu[0]["folder"]."/".
        $menu[0]["files"][0]["name"].'">exécution par mise en ligne</a> a mené au téléchargement d’un fichier malicieux
        sur le serveur. Deux types d’inclusions sont possibles :</p>
        <ul class="list">
            <li><strong>Inclusion d’un fichier local (LFI)</strong><br />
                Le fichier est déjà présent sur le serveur distant pouvant permettre à l’attaquant d’accéder à des
                fichiers locaux. Des données sensibles du système pourraient être accessibles tels que l’accès au
                fichier ‘/etc/passwd’ qui contient la liste de tous les utilisateurs. Bien qu’il ne contienne pas les
                mots de passe, un attaquant peut tenter une attaque par brute force.
            </li>
            <li><strong>Inclusion d’un fichier distant (RFI)</strong><br />
                Le fichier se trouve sur un autre serveur et est injecté depuis une adresse externe. L’attaquant
                pourrait inclure un fichier malveillant depuis un serveur qu’il contrôle. La configuration du serveur
                PHP doit autoriser l’option ‘allow_url_include’ afin de permettre une telle inclusion.
            </li>
        </ul>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <ul class="list">
            <li>Contourner les restrictions de mise en ligne pour contrôler un serveur.</li>
            <li>Dégrader les performances ou la disponibilité d’un service.</li>
            <li>Voler des données sensibles ou confidentielles.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>Les entrées utilisateurs ne sont pas vérifiées suffisamment.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><a href="https://vuldb.com/?id.114758" target="_blank">Site Editor Local File Inclusion
            (CVE-2018-7422)</a><br />
                Ce module WordPress contenait une vulnérabilité d’inclusion d’un fichier local. Le chemin vers le
                fichier inclus était construit en utilisant le paramètre ‘ajax_path’ de la requête qui n’admettait
                aucune vérification. Le site web était donc exposé à des attaques en permettant l’accès et l’exécution
                des fichiers situés sur le serveur.
            </li>
            <li><a
            href="https://infosecwriteups.com/tackling-cve-2021-41277-using-a-vulnerability-database-5e960b8a07c5"
            target="_blank">Metabase Local File Inclusion (CVE-2021-41277)</a><br />
                Cette vulnérabilité est située dans la plateforme open-source d’analyse de données Metabase. Elle est
                causée par la fonction d’import de carte GeoJSON personnalisée qui ne vérifie pas l’URL qui est chargée.
                Cela pouvait conduire à une inclusion d’un fichier local.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($_POST["save"])) ?
            '<div class="form-group">
                <div class="alert alert-success">La langue a bien été modifiée.</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="lang">Langue</label>
                <select id="lang" class="form-control" name="lang">
                    <option value="fr.php">Français</option>
                    <option value="en.php"'.(($lang == "en.php") ? ' selected' : '').'>Anglais</option>
                </select>
            </div>
        </div>
        <footer>
            <button name="save" type="submit">Enregistrer</button>
        </footer>
    </form>
    <div class="table">
        <form class="row" method="POST">
            <h2>Inclusion</h2>
            '.(($success) ?
            '<div class="form-group">
                <div class="alert alert-success">Le fichier a bien été importé.</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="path">Chemin vers le fichier</label>
                '.(($error) ? '<p class="alert alert-danger">Le chemin vers le fichier n’est pas valide.</p>' : '').'
                <input id="path" class="form-control'.(($error) ? ' invalid' : '').'" name="lang"
                type="text" value="'.$lang.'" />
            </div>
            <div>
                <button name="include" type="submit">Inclure</button>
            </div>
        </form>
        <section class="row">
            <h2>Résultat</h2>
            '.(($load) ? '<pre class="line-numbers"><code class="language-php">'.$load.'</code></pre>' :
            (($include) ? '<p class="error">Erreur lors de l’affichage du fichier "'.$lang.'"</p>' : '<p class="error">
            Erreur lors du chargement du fichier "'.$lang.'"</p>')).'
        </section>
    </div>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>L’entrée utilisateur n’est pas vérifiée suffisamment.</li>
            <li>Le chemin du fichier inclus est dynamique.</li>
            <li>Pour RFI, l’option ‘allow_url_include’ doit être activée sur le serveur.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <ul class="list">
            <li>Altérer une entrée utilisateur.</li>
            <li>Récupérer le contenu d’un fichier ou l’exécuter.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <ul class="list">
            <li>Trouver une entrée utilisateur vulnérable.</li>
            <li>Trouver l’emplacement d’un fichier.</li>
            <li>Inclure le fichier dans l’entrée utilisateur.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un code vulnérable</h2>
        <p>Pour illustrer cette vulnérabilité, prenons l’exemple d’un site web qui importe un fichier de langue sans
        vérification de l’entrée de l’utilisateur. Cette attaque peut être exploitée pour exécuter un fichier
        malveillant précédemment mis en ligne ou afficher des données sensibles.</p>
        <pre class="line-numbers" data-line="7"><code class="language-php">if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
} else {
    $lang = "fr.php";
}

include($lang);</code></pre>
        <p>Dans le cas où aucun paramètre n’est donné, le code inclura simplement le fichier ‘fr.php’. Un attaquant peut
        altérer la valeur de ‘lang’ pour accéder localement à un fichier. Une attaque d’inclusion de fichier distant est
        également possible si l’option ‘allow_url_include’ est activée sur le serveur. Voici un exemple de requête qui
        permettrait d’exécuter un fichier ‘vul.php’ mis en ligne avec le code présenté dans l’<a href="'.
        $config["site_link"]."/".$menu[0]["folder"]."/".$menu[0]["files"][0]["name"].'?view=exploit">exécution par mise
        en ligne</a> :</p>
        <div class="border">
            <p><strong>/fichier.php?lang=uploads/vul.php.jpg</strong></p>
        </div>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de prévention</h2>
        <ul class="list">
            <li>Définir une liste blanche d’entrées.</li>
            <li>Désactiver l’option ‘allow_url_include’ sur le serveur.</li>
        </ul>
    </section>
    <section>
        <h2>Correction du code vulnérable</h2>
        <p>La prévention contre cette attaque consiste à vérifier les entrées utilisateur avant de les utiliser. Une
        vérification de la langue est donc nécessaire pour s’assurer qu’elle se trouve bien dans la liste des langues
        autorisées.</p>
        <pre class="line-numbers" data-line="1-3"><code class="language-php">$allowed_langs = ["fr", "en"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $allowed_langs)) {
    $lang = $_GET["lang"];
} else {
    $lang = "fr";
}

include($lang.".php");</code></pre>
        <p>La désactivation de l’option ‘allow_url_include’ permet également de réduire les risques d’une attaque
        d’inclusion d’un fichier distant. Elle est par défaut désactivée sur les serveurs ce qui la rend plus
        difficilement exploitable.</p>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href=
            "https://spanning.com/blog/file-inclusion-vulnerabilities-lfi-rfi-web-based-application-security-part-9/"
            target="_blank">File Inclusion Vulnerabilities</a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Exécution par inclusion", $presentation, $demonstration, $exploit, $fix);
