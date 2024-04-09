<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                INCLUSION EXECUTION                *
 *****************************************************/
if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
} else {
    $lang = "fr";
}

include($lang.".php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Exécution par inclusion</title>
    </head>
    <body>
        <h1>Exécution par inclusion</h1>
        <div>
            <h2>Présentation</h2>
            <section>
                <p>Présenter la vulnérabilité</p>
            </section>
        </div>
        <hr />
        <div>
            <h2>Démonstration</h2>
            <section>
                <h3>Sélection de la langue</h3>
                <form method="GET">
                    <label for="lang">Sélectionnez une langue :</label>
                    <select id="lang" name="lang">
                        <option value="fr"<?php if ($lang == "fr") echo " selected"; ?>>Français</option>
                        <option value="en"<?php if ($lang == "en") echo " selected"; ?>>Anglais</option>
                    </select>
                    <input type="submit" value="Valider" />
                </form>
            </section>
            <section>
                <h3>Contenu</h3>
                <p><?php echo $text; ?></p>
            </section>
        </div>
        <hr />
        <div>
            <h2>Exploitation</h2>
            <section>
                <h3>1. Mise en ligne d'un fichier malveillant</h3>
                <a href="upload.php" target="_blank"><button>Mettre en ligne</button></a>
            </section>
            <section>
                <h3>2. Inclusion malveillante</h3>
                <form method="GET">
                    <label for="malicious">Entrez le nom du fichier :</label>
                    <input id="malicious" name="lang" type="text" />
                    <input type="submit" value="Valider" />
                </form>
            </section>
        </div>
        <hr />
        <div>
            <h2>Correction</h2>
            <section>
                <p>Présenter la correction</p>
            </section>
        </div>
    </body>
</html>
