<?php
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
    $lang = "fr";
}

include($lang.".php");

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>[Nom de la catégorie](Ex : Vulnérabilité d\'exécution de code arbitraire)</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>[Confidentialité, intégrité et disponibilité]</p>
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
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            <div class="form-group">
                <label for="lang">Nom du fichier :</label>
                '.((isset($error)) ? '<div class="alert">'.$error.'</div>' : '').'
                <input id="lang" class="form-control'.((isset($error)) ? ' invalid' : '').'" name="lang" type="text" />
            </div>
        </div>
        <footer>
            <button type="submit">Enregistrer</button>
        </footer>
    </form>
    <section>
        <h2>Résultat</h2>
        <p>'.$text.'</p>
    </section>
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
echo render_malicious("Exécution par inclusion", $presentation, $demonstration, $exploit, $fix);
