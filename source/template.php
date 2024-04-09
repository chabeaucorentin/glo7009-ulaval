<?php
/*****************************************************
 * template.php                                      *
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
require("includes/bootstrap.php");

/*****************************************************
 *                     TEMPLATE                      *
 *****************************************************/
if (isset($_POST["fullname"]) && isset($_POST["email"]) && isset($_POST["message"]) && isset($_POST["file"])) {
    $error = "Ceci est un message d'erreur !";
}

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>[Nom de la catégorie](Ex : Vulnérabilité d’exécution de code arbitraire)</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>[Confidentialité, intégrité et disponibilité]</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>[Description de la vulnérabilité, ses effets potentiels et le risque qu’elle représente]</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des buts, intentions et avantages qu’un attaquant pourrait avoir en exploitant la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Objectif 1](Ex : Contourner les restrictions de mise en ligne pour contrôler un serveur.)</li>
            <li>[Objectif 2](Ex : Dégrader les performances ou la disponibilité d’un service.)</li>
            <li>[Objectif 3](Ex : Voler des données sensibles ou confidentielles.)</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des facteurs qui introduisent la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Cause 1](Ex : Aucune validation du nom, type, contenu ou taille pour les fichiers téléchargés.)</li>
            <li>[Cause 2](Ex : Le répertoire de mise en ligne autorise l’exécution des fichiers téléchargés.)</li>
            <li>[Cause 3](Ex : Le serveur contient des scripts obsolètes qui intègrent la vulnérabilité.)</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><a href="#" target="_blank">[Nom de l’attaque ou de la vulnérabilité 1](Ex : Drupalgeddon2 (CVE-2018-7600))</a><br />
                [Brève description de l’incident, du contexte et des conséquences]
            </li>
            <li><a href="#" target="_blank">[Nom de l’attaque ou de la vulnérabilité 2](Ex : Contact Form 7 (CVE-2020-35489))</a><br />
                [Brève description de l’incident, du contexte et des conséquences]
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            <div class="form-group">
                <label for="fullname">Nom complet :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="fullname" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="fullname"
                placeholder="Ex : John Doe" type="text" />
            </div>
            <div class="form-group">
                <label for="email">Adresse email :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="email" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="email"
                placeholder="Ex : test@mail.com" type="email" />
            </div>
            <div class="form-group">
                <label for="message">Message :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <textarea id="message" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="message" rows="6"></textarea>
            </div>
            <div class="form-group">
                <label for="file">Document :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="file" class="dropify'.((isset($error)) ? ' invalid' : '').'" name="file" type="file" />
            </div>
        </div>
        <footer>
            <button type="submit">Enregistrer</button>
        </footer>
    </form>
    <section>
        <h2>Résultat</h2>
        <p>[Contenu]</p>
    </section>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des conditions requises pour exploiter la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Condition 1](Ex : Le fichier mis en ligne n’est pas vérifié suffisamment.)</li>
            <li>[Condition 2](Ex : Le répertoire de mise en ligne est exécutable.)</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des méthodes qui permettent à un attaquant d’exploiter la vulnérabilité]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Méthode 1](Ex : Mettre en ligne un fichier exécutable sans vérification.)</li>
            <li>[Méthode 2](Ex : Mettre en ligne un fichier avec la dissimulation de son extension.)</li>
            <li>[Méthode 3](Ex : Mettre en ligne un fichier avec la dissimulation de son type.)</li>
            <li>[Méthode 4](Ex : Mettre en ligne d’un fichier en injectant un code malicieux dans l’en-tête.)</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <!-- Supprimer ce paragraphe -->
        <p><em>[Description des étapes qui permettent à un attaquant d’exécuter l’attaque]</em></p>
        <!-- FIN Supprimer ce paragraphe -->
        <ul class="list">
            <li>[Étape 1](Ex : Mettre en ligne un fichier malveillant.)</li>
            <li>[Étape 2](Ex : Trouver l’emplacement du fichier sur le serveur.)</li>
            <li>[Étape 3](Ex : Exécuter le fichier.)</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un code vulnérable</h2>
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
            <li>[Nom de la mesure 1](Ex : Stocker les fichiers mis en ligne en dehors du serveur web principal.)</li>
            <li>[Nom de la mesure 1](Ex : Rendre le répertoire de mise en ligne non exécutable.)</li>
            <li>[Nom de la mesure 1](Ex : Bloquer l’affichage de l’arborescence des répertoires.)</li>
            <li>[Nom de la mesure 1](Ex : Modifier le nom des fichiers mis en ligne.)</li>
            <li>[Nom de la mesure 1](Ex : Analyser les fichiers mis en ligne avec un outil antivirus.)</li>
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
echo render_malicious("Template", $presentation, $demonstration, $exploit, $fix);
