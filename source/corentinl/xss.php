<?php
/*****************************************************
 * xss.php                                           *
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
 *                   XSS INJECTION                   *
 *****************************************************/

$value_display = "<img src='rpc.jpg'/>";
if(array_key_exists('ajouterImage', $_POST)) { 
    $value_display = $_POST["xss"];
    #$input = htmlspecialchars($input);
}
if (isset($_GET['display'])) {
    $value_display = $_GET['display'];
}

$value_entered = "<img src=&quot;x&quot onerror=&quot;javascript:(()=>{wins_usr = 3;loadScore()})()&quot;/>";
if (isset($_GET['xss'])) {
    $value_entered = $_GET['xss'];
}

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$presentation = '<div class="table">
    <div class="row split">
        <section>
            <h2>Catégorie</h2>
            <p>Injection XSS</p>
        </section>
        <section>
            <h2>Impact potentiel</h2>
            <p>Confidentialité, intégrité et disponibilité</p>
        </section>
    </div>
    <section class="row">
        <h2>Description</h2>
        <p>Une injection XSS (Cross-Site Scripting) est un type d’injection très répandue qui se produit lorsqu’un utilisateur injecte un script malicieux (typiquement en JavaScript) dans des pages web utilisées par d’autres utilisateurs. Il existe différents types d’injection XSS :
            
        <ul class="list">
        <li><strong>Attaque XSS stockée</strong><br />
            Le script malicieux est conservé en permanence sur le serveur. Il n’est exécuté que lorsqu’un utilisateur accède à une page.        
        </li>
        <li><strong>Attaque XSS réfléchie</strong><br />
            L’attaquant crée une soumission de formulaire qui inclut le script à exécuter. Lorsqu’un utilisateur soumet le formulaire, les données contenant le script malveillant sont envoyées au serveur et retourné à l’utilisateur.        
        </li>
        <li><strong>XSS basé sur le DOM</strong><br />
            Le script malicieux est injecté dans le DOM (Document Object Model) d’une page web et interprété par le navigateur de l’utilisateur. Les injections se produisent donc une fois la page chargée.
        </li>
        </ul>
</section>
</p>
    </section>
    <section class="row">
        <h2>Objectifs</h2>
        <ul class="list">
            <li>Usurper l’identité d’un utilisateur légitime.</li>
            <li>Dégrader les performances ou la disponibilité d’un service.</li>
            <li>Voler des données sensibles ou confidentielles.</li>
        </ul>
    </section>
    <section class="row">
        <h2>Causes</h2>
        <ul class="list">
            <li>Les entrées utilisateurs et l’encodage en sortie ne sont pas vérifiés suffisamment.</li>
            <li>Les en-têtes n’utilisent pas des politiques de sécurité sur le contenu (CSP).</li>
        </ul>
    </section>
    <section class="row">
        <h2>Exemples marquants</h2>
        <ul class="list">
            <li><strong>Google Chrome (CVE-2023-5480)</strong><br />
                Une implémentation mal adaptée dans les outils de paiements permet à l’attaquant d’outrepasser les préventions contre les injections XSS par un fichier malicieux.
            </li>
            <li><strong>Fortinet (CVE-2023-45587)</strong><br />
                Une neutralisation impropre des entrées pendant la génération d’une page permet à un attaquant d’exécuter du code ou des commandes via des requêtes HTTP.
            </li>
        </ul>
    </section>
</div>';

$demonstration = '<div class="split">

    <form method="POST" onsubmit="return playGame()">
        <div>
            <h2>Scénario</h2>
            <div id="display">
                <label for="cpu_score">ORDINATEUR:</label> <span id="cpu_score">0</span><br>
                <label for="usr_score">UTILISATEUR:</label> <span id="usr_score">0</span><br><br>
            </div>
            <div class="form-group">
                <label for="choice">Choix :</label>
                <select id="choice" class="form-control" name="choice">
                    <option value="roche">Roche</option>
                    <option value="papier">Papier</option>
                    <option value="ciseau">Ciseau</option>
                </select>
            </div>
        </div>
        <footer>
            <button type="submit">Jouer</button>
        </footer>
    </form>
    <form method="POST">
        <div>
            <h2>Injection:</h2>
            <div class="form-group">
                <label for="xss"> Image:</label>
                <input id="xss" name="xss" type="text" value="'.$value_entered.'">
            </div>
            <div id="display">
                '.$value_display.'
            </div>
        </div>
        <footer>
            <button name="ajouterImage" type="submit">Ajouter</button>
        </footer>
    </form>
</div>
<script>
    let wins_cpu = 0;
    let wins_usr = 0;
    let computerChoice = "";
    let userChoice = "";

    function playGame() {
        userChoice = document.getElementById("choice").value;
        const choices = ["roche", "papier", "ciseau"];
        computerChoice = choices[Math.floor(Math.random() * choices.length)];
        let resultMessage;

        if (userChoice === computerChoice) {
            resultMessage = "Egalite!";
        } else if ((userChoice === "roche" && computerChoice === "ciseau") ||
            (userChoice === "papier" && computerChoice === "roche") ||
            (userChoice === "ciseau" && computerChoice === "papier")) {
            resultMessage = "Bravo, tu as gagne!";
            wins_usr++;
        } else {
            resultMessage = "Ooops, tu as perdu...";
            wins_cpu++;
        }

        //alert(
        //    "Ton choix : " + userChoice + "\n" +
        //    "Mon choix : " + computerChoice + "\n\n" +
        //    resultMessage + "\n\n" +
        //    "USER: " + wins_usr + "\n" +
        //    "CPU: " + wins_cpu);

        isGameOver();

        loadScore();

        return false;
    }

    function isGameOver() {
        if (wins_usr >= 3) {
            alert("Bravo, tu as gagne le jeu!");
            wins_usr = 0;
            wins_cpu = 0;
        }
        else if (wins_cpu >= 3) {
            alert("Ooops tu as perdu...");
            wins_usr = 0;
            wins_cpu = 0;
        }
    }
    
    function loadScore() {
        document.getElementById("usr_score").innerHTML = wins_usr;
        document.getElementById("cpu_score").innerHTML = wins_cpu; 
    }

</script>';

$exploit = '<div>
    <section>
        <h2>Conditions préalables pour l\'exploitation</h2>
        <ul class="list">
            <li>Le navigateur permet l’exécution de code JavaScript.</li>
            <li>Les entrées utilisateurs ne sont pas vérifiées suffisamment.</li>
        </ul>
    </section>
    <section>
        <h2>Méthodes d\'exploitation</h2>
        <ul class="list">
            <li>Injection de code dans une image ou dans les paramètres d’un URL.</li>
            <li>Injection dans le DOM.</li>
            <li>Redirection vers un site malicieux.</li>
        </ul>
    </section>
    <section>
        <h2>Exécution de l\'attaque</h2>
        <ul class="list">
            <li>Choisir un point d’entrée vulnérable.</li>
            <li>Injection d’un code malicieux.</li>
            <li>Exécution du code.</li>
        </ul>
    </section>
    <section>
        <h2>Analyse du code vulnérable</h2>
        <p>Un exemple de cette vulnérabilité serait une page qui accepte une entrée utilisateur sans modification. Cette entrée est ensuite affichée sur la page web. L’utilisateur peut donc entrer une balise image contenant un chemin indisponible et une fonction dans le champ ‘onerror’.</p>
        <pre class="line-numbers" data-line=""><code class="language-php">
            <img src=’rpc.jpg’ onerror=’javascript:(()=>{alert(‘XSS’);})()’/>"
        </code></pre>
        <p>La fonction du champ ‘onerror’ sera exécutée, car l’image n’est pas trouvée. Ce code affichera une alerte contenant le message ‘XSS’.</p>
    </section>
</div>';

$fix = '<div>
    <section>
        <h2>Mesures de protection</h2>
        <ul class="list">
            <li>Valider et nettoyer les entrées utilisateurs.</li>
            <li>Définir une politique de sécurité de contenu (CSP).</li>
            <li>Surveiller les tentatives de modification du DOM.</li>
        </ul>
    </section>
    <section>
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
        <p>Un CSP (Content Security Policy) est une sécurité permettant contrôler les sources de contenu autorisées à être chargées sur une page web. L’objectif étant de réduire les risques liés aux attaques par injection.</p>
        <pre class="line-numbers" data-line=""><code class="language-php">header("Content-Security-Policy: default-src ’self’;img-src ’self’;script-src ’self’;");</code></pre>
        <p>Cette ligne de code représente une politique de sécurité du contenu qui définit les directives de sécurité pour le chargement des ressources sur un site web.
            <ul class="list">
                <li>default-src ’self’ : spécifie que par défaut, les ressources doivent être chargées depuis le même domaine.</li>
                <li>img-src ’self’ : autorise le chargement des images uniquement depuis le même domaine. Si cet attribut est omis, le chargement des images ne se fera qu’en provenance des sites spécifiés par l’attribut default-src.</li>
                <li>script-src ’self’ : permet le chargement de scripts uniquement depuis le même domaine.  Si cet attribut est omis, les fonctions JavaScript n’exécuteront que les scripts provenant des sites spécifiés par l’attribut default-src.</li>
            </ul>
        </p>
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
echo render_malicious("Injection XSS", $presentation, $demonstration, $exploit, $fix);
