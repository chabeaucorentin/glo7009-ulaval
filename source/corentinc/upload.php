<?php
/*****************************************************
 * upload.php                                        *
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
 *                  UPLOAD EXECUTION                 *
 *****************************************************/
$image = $_FILES["image"];
$identity_image = $config["site_link"]."/assets/images/identity/id.png";
$image_name = "Photo d’identité";

if ($image) {
    $image_name = basename($image["name"]);
    $image_path = "uploads/".$image_name;
    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $allowed_extensions = array("jpg", "jpeg", "png");

    if ($image["error"] == UPLOAD_ERR_NO_FILE) {
        $error["image"] = "Veuillez mettre en ligne une photo.";
    } else if (!$image["error"] && !in_array($image_extension, $allowed_extensions)) {
        $error["image"] = "La photo doit-être au format JPG, JPEG ou PNG.";
    } else if (!$image["error"] && !move_uploaded_file($image["tmp_name"], $image_path)) {
        $error["upload"] = "Une erreur s’est produite lors de la mise en ligne de la photo.";
    } else {
        $success = "La photo a bien été mise à jour.";
        $identity_image = $config["site_link"]."/corentinc/".$image_path;
    }
}

date_default_timezone_set("America/Toronto");
setlocale(LC_TIME, "fr_CA.utf8", "fr_CA.UTF-8");

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
        <p>Un bon nombre de sites web offrent la possibilité de mettre en ligne des fichiers, que ce soit pour partager
        des documents, mettre à jour une photo de profil, etc. Cette fonctionnalité pourrait introduire une
        vulnérabilité par mise en ligne de fichiers (File Upload Vulnerability). Cette dernière survient lorsqu’un
        serveur ne procède pas à des vérifications suffisantes lors de la mise en ligne.</p>
        <p>Des fichiers malveillants peuvent être téléchargés sur le serveur et potentiellement exécutés, ce qui
        pourrait affecter la <strong>confidentialité</strong>, l’<strong>intégrité</strong> et la <strong>
        disponibilité</strong>. Un risque significatif est donc introduit pour la sécurité du serveur et des
        utilisateurs dont il est important de se prémunir.</p>
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
            <li>Aucune validation du nom, type, contenu ou taille pour les fichiers téléchargés.</li>
            <li>Le répertoire de mise en ligne autorise l’exécution des fichiers téléchargés.</li>
            <li>Le serveur contient des scripts obsolètes qui intègrent la vulnérabilité.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><a
            href="https://www.securityjourney.com/post/drupalgeddon2-cve-2018-7600-vulnerability" target="_blank">
            Drupalgeddon2 (CVE-2018-7600)</a><br />
                Le CMS Drupal contenait une faille de sécurité qui permettait de mettre en ligne et d’exécuter un
                fichier sans nécessiter d’authentification préalable. Elle est présente dans toutes les versions de la
                7.58 à la 8.5.1, ce qui l’a rendue critique. Un attaquant pouvait réaliser une attaque en envoyant
                simplement une requête de type POST. Elle avait été principalement utilisée pour miner des
                cryptomonnaies, installer des ransomwares et voler des données privées.
            </li>
            <li><a href="https://blog.wpsec.com/contact-form-7-vulnerability/" target="_blank">Contact Form 7
            (CVE-2020-35489)</a><br />
                Cette vulnérabilité était présente dans le module Contact Form 7 de WordPress. Il était installé sur
                plus de 5 millions de sites web au moment de la découverte. N’importe quel fichier pouvait être
                téléchargé et exécuté sans aucune restriction en exploitant les caractères spéciaux dans les noms de
                fichiers.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form enctype="multipart/form-data" method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($success)) ? '
            <div class="form-group">
                <div class="alert alert-success">'.$success.'</div>
            </div>
            ' : '').
            ((isset($error["upload"])) ? '
            <div class="form-group">
                <div class="alert alert-danger">'.$error["upload"].'</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="image">Photo d’identité</label>
                '.((isset($error["image"])) ? '<div class="alert alert-danger">'.$error["image"].'</div>' : '').'
                <input id="image" class="dropify'.((isset($error["image"])) ? ' invalid' : '').'" name="image"
                type="file" data-default-file="'.$identity_image.'" />
            </div>
        </div>
        <footer>
            <button type="submit">Modifier</button>
        </footer>
    </form>
    <section>
        <h2>Résultat</h2>
        <p class="mb-5">Carte d’identité</p>
        <div class="identity">
            <header>
                <img src="'.$config["site_link"].'/assets/images/identity/logo.svg" alt="Université Laval" />
            </header>
            <p class="validity">Carte valide en date du <strong>'.strftime("%e %B %Y - %k h %M").'</strong></p>
            <div>
                <img class="image" src="'.$identity_image.'" alt="'.$image_name.'" />
                <p class="firstname">Doe</p>
                <p class="lastname">John</p>
                <p class="student">Carte étudiante</p>
                <p class="ni">Numéro d’identification (NI): <strong>123 456 789</strong></p>
                <img class="code" src="'.$config["site_link"]."/assets/images/identity/code.svg".'" alt="Code" />
            </div>
        </div>
    </section>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>Le fichier mis en ligne n’est pas vérifié suffisamment.</li>
            <li>Le répertoire de mise en ligne est exécutable.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <ul class="list">
            <li>Mettre en ligne un fichier exécutable sans vérification.</li>
            <li>Mettre en ligne un fichier avec la dissimulation de son extension.<br />
                Ex : <strong>vul.php</strong>.jpg, <strong>vul</strong>%2E<strong>php</strong> (encodage URL), etc.
            </li>
            <li>Mettre en ligne un fichier avec la dissimulation de son type.<br />
                Ex : Les fichiers JPEG commencent toujours avec les bytes FF D8 FF.
            </li>
            <li>Mettre en ligne d’un fichier en injectant un code malicieux dans l’en-tête.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <ul class="list">
            <li>Mettre en ligne un fichier malveillant.</li>
            <li>Trouver l’emplacement du fichier sur le serveur.</li>
            <li>Exécuter le fichier.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un code vulnérable</h2>
        <p>Un exemple basique de cette vulnérabilité pourrait être une application web qui permet aux utilisateurs de
        mettre en ligne une photo de profil en vérifiant uniquement son extension et non son type ou son contenu.</p>
        <pre class="line-numbers" data-line="11,13"><code class="language-php">$image = $_FILES["image"];

if ($image) {
    $image_name = basename($image["name"]);
    $image_path = "uploads/".$image_name;
    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $allowed_extensions = array("jpg", "jpeg", "png");

    if ($image["error"] == UPLOAD_ERR_NO_FILE) {
        $error = "Veuillez mettre en ligne une photo !";
    } else if (!$image["error"] && !<strong>in_array($image_extension, $allowed_extensions)</strong>) {
        $error = "La photo doit-être au format JPG, JPEG ou PNG.";
    } else if (!$image["error"] && !move_uploaded_file($image["tmp_name"], $image_path)) {
        $error = "Une erreur s’est produite lors de la mise en ligne de la photo !";
    }
}</code></pre>
        <p>Lorsqu’un fichier est mis en ligne sur un serveur en utilisant un formulaire PHP, il est tout d’abord placé
        dans un dossier temporaire sur ce serveur. La fonction <strong>move_uploaded_file()</strong> permet de déplacer
        un fichier en vérifiant qu’il ait bien été mis en ligne avec un formulaire et la méthode POST. Cette
        vérification ne suffit pas ici étant donné que n’importe quel fichier jpg, jpeg ou png peut être envoyé par
        cette méthode.</p>
        <p>Cette attaque est généralement réalisée en appelant la fonction <strong>system()</strong> à l’intérieur du
        fichier envoyé. Cette fonction permet d’exécuter des commandes dans le terminal du serveur distant, tout comme
        pour la librairie C.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de prévention</h2>
        <ul class="list">
            <li>Stocker les fichiers mis en ligne en dehors du serveur web principal.</li>
            <li>Rendre le répertoire de mise en ligne non exécutable.</li>
            <li>Bloquer l’affichage de l’arborescence des répertoires.</li>
            <li>Modifier le nom des fichiers mis en ligne.</li>
            <li>Analyser les fichiers mis en ligne avec un outil antivirus.</li>
        </ul>
    </section>
    <section>
        <h2>Correction du code vulnérable</h2>
        <p>Une simple vérification de l’extension du fichier pourrait tout de même mener à sa mise en ligne si
        l’attaquant la modifie sous la forme ‘<strong>vul.php</strong>.jpg’. Une vérification plus fiable consiste à
        obtenir le type MIME du fichier à l’aide de la fonction <strong>mime_content_type()</strong>, puis à vérifier
        qu’il se trouve bien dans la liste des types autorisés avec la fonction <strong>in_array()</strong>.</p>
        <pre class="line-numbers" data-line="1,2,6,7">
<code class="language-php">    $image_type = mime_content_type($image["tmp_name"]);
    $allowed_types = array("image/jpg", "image/jpeg", "image/png");

    if ($image["error"] == UPLOAD_ERR_NO_FILE) {
        ...
    } elseif (!$image["error"] && !in_array($image_type, $allowed_types)) {
        $error = " La photo doit-être au format JPG, JPEG ou PNG.";
    } elseif (...) {
        ...</code></pre>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href="https://portswigger.net/web-security/file-upload" target="_blank">File Upload Vulnerabilities
            </a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Mise en ligne de fichiers", $presentation, $demonstration, $exploit, $fix);
