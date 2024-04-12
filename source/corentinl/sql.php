<?php
/*****************************************************
 * sql.php                                           *
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
 *                   SQL INJECTION                   *
 *****************************************************/
$results = "Aucun résultat";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["firstname"])) {
        $firstname = $_POST["firstname"];
        $sql_result = query($firstname);
        $results = get_html_result($sql_result);
    } else {
        $error["firstname"] = "Veuillez entrer un prénom valide.";
    }
}

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>Vulnérabilités d’injection de code</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité, intégrité et disponibilité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>Une injection SQL se produit lorsqu’un attaquant exploite une entrée utilisateur pour altérer le déroulement
        normal d’une requête SQL. Le malfaiteur va tenter de sortir de la requête afin d’obtenir plus de résultats
        qu’attendu ou apporter des modifications à un plus grand nombre d’enregistrements. Les risques associés aux
        injections SQL sont nombreux et peuvent affecter la <strong>confidentialité</strong>,
        l’<strong>intégrité</strong> et la <strong>disponibilité</strong>.</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <ul class="list">
            <li>Dégrader les performances ou la disponibilité d’un service.</li>
            <li>Voler des données sensibles ou confidentielles.</li>
            <li>Apporter des modifications sur les données.</li>
            <li>Surcharger la base de données en exécutant des requêtes complexes.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>Les entrées utilisateurs ne sont pas vérifiées suffisamment.</li>
            <li>Les paramètres de la requête ne sont pas nettoyés.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><a href="https://www.cvedetails.com/cve/CVE-2022-21664/" target="_blank">WordPress (CVE-2022-21664)</a>
            <br />
                WordPress a été menacé par une faille critique qui permettait aux entrées d’être interprétées comme
                étant des requêtes SQL. Cela pouvait conduire à l’injection de requêtes SQL.
            </li>
            <li><a href="https://www.cvedetails.com/cve/CVE-2024-28816/" target="_blank">Student Information Chatbot
            (CVE-2024-28816)</a><br />
                Chatbot intégrait une vulnérabilité qui permettait la réalisation d’une attaque par injection SQL en
                altérant le nom d’utilisateur utilisé dans la fonction de connexion.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">
    <form method="POST">
        <div>
            <h2>Scénario</h2>
            '.((isset($sql_result)) ? '
            <div class="form-group">
                <div class="alert alert-success">La recherche a bien été effectuée.</div>
            </div>
            ' : '').'
            <div class="form-group">
                <label for="firstname">Prénom</label>
                '.((isset($error["firstname"])) ? '<p class="alert alert-danger">'.$error["firstname"].'</p>' : '').'
                <input id="firstname" class="form-control'.((isset($error["firstname"])) ? ' invalid' : '').'" name=
                "firstname" type="text" placeholder="Ex : Melissa"'.((isset($firstname)) ? ' value="'.htmlspecialchars(
                $firstname, ENT_QUOTES).'"' : '').' />
            </div>
        </div>
        <footer>
            <button type="submit">Rechercher</button>
        </footer>
    </form>
    <section>
        <h2>Résultat</h2>
        <p>'.$results.'</p>
    </section>
</div>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l’exploitation</h2>
        <ul class="list">
            <li>Les entrées utilisateurs ne sont pas vérifiées suffisamment.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d’exploitation</h2>
        <ul class="list">
            <li>Injecter depuis les paramètres d’une URL.</li>
            <li>Injecter depuis les champs d’un formulaire.</li>
            <li>Injecter des requêtes erronées pour obtenir des informations sensibles depuis le serveur.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l’attaque</h2>
        <ul class="list">
            <li>Trouver une entrée utilisateur vulnérable.</li>
            <li>Injecter et exécuter une requête SQL.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse d’un code vulnérable</h2>
        <p>Un exemple de cette vulnérabilité pourrait être une page qui affiche les informations d’un usager lorsque
        celui-ci entre son nom d’utilisateur. La requête serait construite comme suit :</p>
        <pre class="line-numbers"><code class="language-php">$sql_request = '.
        '"SELECT user_firstname, user_lastname FROM users WHERE user_email=’$user_input’";
return mysqli_query($db, $sql_request);</code></pre>
        <p>Une entrée malicieuse incluant le caractère d’échappement « \' » pourrait être de la forme
        <strong>x\' OR 1=\'1</strong>. Puisque cette entrée n’est pas vérifiée, la requête devient :</p>
        <pre class="line-numbers"><code class="language-php">$sql_request = '.
        '"SELECT user_firstname, user_lastname FROM users WHERE user_email=\'x\' OR 1=\'1\'";</code></pre>
        <p>La clause WHERE de la requête sera toujours vraie. Ainsi, les informations de tous les usagers seront
        retournées lors de l’exécution de la requête.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de prévention</h2>
        <ul class="list">
            <li>Convertir les caractères d’échappement.</li>
            <li>Traiter les requêtes avant de les exécuter.</li>
            <li>Limiter les messages d’erreur afin qu’ils ne contiennent aucune information sur la structure de la base
            de données.</li>
        </ul>
    </section>
    <section>
        <h2>Correction du code vulnérable</h2>
        <p>Une solution qui permet de corriger cette vulnérabilité est d’utiliser des requêtes paramétrables. On utilise
        des paramètres de substitution dans des espaces réservés pour les données (indiqué par un ‘?’), plutôt que
        d’insérer directement les valeurs de l’usager. Lorsque ces dernières sont insérées dans la requête, on s’assure
        qu’elles sont correctement échappées.</p>
        <pre class="line-numbers"><code class="language-php">$sql_statement = '.
        '$db->prepare("SELECT user_firstname, user_lastname FROM users WHERE user_firstname=?");

$sql_statement->bind_param("s", $value);
$sql_statement->execute();
return $sql_statement->get_result();</code></pre>
        <p>Cet exemple de code présente respectivement les étapes pour :</p>
        <ul class="list">
            <li>Préparer la requête,</li>
            <li>Assigner les paramètres,</li>
            <li>Exécuter la requête,</li>
            <li>Obtenir les résultats.</li>
        </ul>
    </section>
    <section>
        <h2>Documentation et ressources</h2>
        <ul class="list">
            <li><a href="https://owasp.org/www-community/attacks/SQL_Injection" target="_blank">SQL Injection</a></li>
        </ul>
    </section>
</div>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_malicious("Injection SQL", $presentation, $demonstration, $exploit, $fix);
